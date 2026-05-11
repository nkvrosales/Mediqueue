<?php

namespace App\Models;

use CodeIgniter\Model;

class TicketModel extends Model
{
    protected $table            = 'tickets';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = [
        'ticket_number', 'department_id', 'service_id', 'counter_id',
        'served_by', 'patient_name', 'status', 'priority',
        'issued_at', 'called_at', 'completed_at', 'queue_date'
    ];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    /**
     * Generate next ticket number for a department on current date
     */
    public function generateTicketNumber($departmentCode)
    {
        $today = date('Y-m-d');
        $lastTicket = $this->where('queue_date', $today)
                           ->like('ticket_number', $departmentCode . '-', 'after')
                           ->orderBy('id', 'DESC')
                           ->first();

        if ($lastTicket) {
            $parts = explode('-', $lastTicket['ticket_number']);
            $nextNum = intval(end($parts)) + 1;
        } else {
            $nextNum = 1;
        }

        return $departmentCode . '-' . str_pad($nextNum, 3, '0', STR_PAD_LEFT);
    }

    /**
     * Create a new ticket
     */
    public function createTicket($departmentId, $serviceId = null, $patientName = null, $priority = 'normal')
    {
        $deptModel = new DepartmentModel();
        $dept = $deptModel->find($departmentId);

        if (!$dept) {
            return false;
        }

        $ticketNumber = $this->generateTicketNumber($dept['code']);

        $data = [
            'ticket_number' => $ticketNumber,
            'department_id' => $departmentId,
            'service_id'    => $serviceId,
            'patient_name'  => $patientName,
            'status'        => 'waiting',
            'priority'      => $priority,
            'issued_at'     => date('Y-m-d H:i:s'),
            'queue_date'    => date('Y-m-d'),
        ];

        $this->insert($data);
        return $this->find($this->getInsertID());
    }

    /**
     * Get waiting tickets for a department today, ordered by priority then issue time
     */
    public function getWaitingTickets($departmentId)
    {
        return $this->select('tickets.*, services.name as service_name')
                    ->join('services', 'services.id = tickets.service_id', 'left')
                    ->where('tickets.department_id', $departmentId)
                    ->where('tickets.queue_date', date('Y-m-d'))
                    ->where('tickets.status', 'waiting')
                    ->orderBy("FIELD(tickets.priority, 'priority', 'normal')", '', false)
                    ->orderBy('tickets.issued_at', 'ASC')
                    ->findAll();
    }

    /**
     * Get currently serving ticket for a department
     */
    public function getCurrentServing($departmentId)
    {
        return $this->select('tickets.*, services.name as service_name, counters.name as counter_name, users.full_name as staff_name')
                    ->join('services', 'services.id = tickets.service_id', 'left')
                    ->join('counters', 'counters.id = tickets.counter_id', 'left')
                    ->join('users', 'users.id = tickets.served_by', 'left')
                    ->where('tickets.department_id', $departmentId)
                    ->where('tickets.queue_date', date('Y-m-d'))
                    ->where('tickets.status', 'serving')
                    ->first();
    }

    /**
     * Call next patient in queue
     */
    public function callNext($departmentId, $counterId, $staffId)
    {
        // First, complete any currently serving ticket for this counter
        $this->where('counter_id', $counterId)
             ->where('queue_date', date('Y-m-d'))
             ->where('status', 'serving')
             ->set(['status' => 'served', 'completed_at' => date('Y-m-d H:i:s')])
             ->update();

        // Get next waiting ticket (priority first)
        $nextTicket = $this->where('department_id', $departmentId)
                          ->where('queue_date', date('Y-m-d'))
                          ->where('status', 'waiting')
                          ->orderBy("FIELD(priority, 'priority', 'normal')", '', false)
                          ->orderBy('issued_at', 'ASC')
                          ->first();

        if ($nextTicket) {
            $this->update($nextTicket['id'], [
                'status'     => 'serving',
                'counter_id' => $counterId,
                'served_by'  => $staffId,
                'called_at'  => date('Y-m-d H:i:s'),
            ]);
            return $this->find($nextTicket['id']);
        }

        return null;
    }

    /**
     * Skip current patient
     */
    public function skipTicket($ticketId)
    {
        return $this->update($ticketId, [
            'status'       => 'skipped',
            'completed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Complete serving current patient
     */
    public function completeTicket($ticketId)
    {
        return $this->update($ticketId, [
            'status'       => 'served',
            'completed_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * Recall a skipped ticket back to waiting
     */
    public function recallTicket($ticketId)
    {
        return $this->update($ticketId, [
            'status'       => 'waiting',
            'counter_id'   => null,
            'served_by'    => null,
            'called_at'    => null,
            'completed_at' => null,
        ]);
    }

    /**
     * Get today's stats for dashboard
     */
    public function getTodayStats($departmentId = null)
    {
        $builder = $this->where('queue_date', date('Y-m-d'));
        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }
        $total = $builder->countAllResults(false);

        $builder = $this->where('queue_date', date('Y-m-d'))->where('status', 'waiting');
        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }
        $waiting = $builder->countAllResults(false);

        $builder = $this->where('queue_date', date('Y-m-d'))->where('status', 'serving');
        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }
        $serving = $builder->countAllResults(false);

        $builder = $this->where('queue_date', date('Y-m-d'))->where('status', 'served');
        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }
        $served = $builder->countAllResults(false);

        $builder = $this->where('queue_date', date('Y-m-d'))->where('status', 'skipped');
        if ($departmentId) {
            $builder->where('department_id', $departmentId);
        }
        $skipped = $builder->countAllResults(false);

        // Average wait time (called_at - issued_at) for served tickets
        $avgQuery = $this->select('AVG(TIMESTAMPDIFF(MINUTE, issued_at, called_at)) as avg_wait')
                         ->where('queue_date', date('Y-m-d'))
                         ->whereIn('status', ['serving', 'served'])
                         ->where('called_at IS NOT NULL');
        if ($departmentId) {
            $avgQuery->where('department_id', $departmentId);
        }
        $avgResult = $avgQuery->first();
        $avgWait = $avgResult ? round($avgResult['avg_wait'] ?? 0) : 0;

        return [
            'total'    => $total,
            'waiting'  => $waiting,
            'serving'  => $serving,
            'served'   => $served,
            'skipped'  => $skipped,
            'avg_wait' => $avgWait,
        ];
    }

    /**
     * Get department-wise stats for today
     */
    public function getDepartmentStats()
    {
        return $this->select('departments.name as department_name, departments.code, departments.color,
                             COUNT(tickets.id) as total,
                             SUM(CASE WHEN tickets.status = "waiting" THEN 1 ELSE 0 END) as waiting,
                             SUM(CASE WHEN tickets.status = "serving" THEN 1 ELSE 0 END) as serving,
                             SUM(CASE WHEN tickets.status = "served" THEN 1 ELSE 0 END) as served')
                    ->join('departments', 'departments.id = tickets.department_id')
                    ->where('tickets.queue_date', date('Y-m-d'))
                    ->groupBy('tickets.department_id')
                    ->findAll();
    }

    /**
     * Get all currently serving tickets (for display board)
     */
    public function getAllCurrentlyServing()
    {
        return $this->select('tickets.*, departments.name as department_name, departments.code as department_code, departments.color,
                             counters.name as counter_name, services.name as service_name')
                    ->join('departments', 'departments.id = tickets.department_id')
                    ->join('counters', 'counters.id = tickets.counter_id', 'left')
                    ->join('services', 'services.id = tickets.service_id', 'left')
                    ->where('tickets.queue_date', date('Y-m-d'))
                    ->where('tickets.status', 'serving')
                    ->orderBy('departments.name', 'ASC')
                    ->findAll();
    }

    /**
     * Get tickets for reporting
     */
    public function getReport($dateFrom, $dateTo, $departmentId = null)
    {
        $builder = $this->select('tickets.*, departments.name as department_name, departments.code as department_code,
                                  services.name as service_name, users.full_name as staff_name, counters.name as counter_name')
                        ->join('departments', 'departments.id = tickets.department_id')
                        ->join('services', 'services.id = tickets.service_id', 'left')
                        ->join('users', 'users.id = tickets.served_by', 'left')
                        ->join('counters', 'counters.id = tickets.counter_id', 'left')
                        ->where('tickets.queue_date >=', $dateFrom)
                        ->where('tickets.queue_date <=', $dateTo);

        if ($departmentId) {
            $builder->where('tickets.department_id', $departmentId);
        }

        return $builder->orderBy('tickets.queue_date', 'DESC')
                       ->orderBy('tickets.issued_at', 'DESC')
                       ->findAll();
    }

    /**
     * Get recent served tickets (last N)
     */
    public function getRecentServed($departmentId, $limit = 5)
    {
        return $this->select('tickets.*, counters.name as counter_name')
                    ->join('counters', 'counters.id = tickets.counter_id', 'left')
                    ->where('tickets.department_id', $departmentId)
                    ->where('tickets.queue_date', date('Y-m-d'))
                    ->whereIn('tickets.status', ['served', 'skipped'])
                    ->orderBy('tickets.completed_at', 'DESC')
                    ->limit($limit)
                    ->findAll();
    }
}
