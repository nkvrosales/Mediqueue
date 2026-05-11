<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table            = 'departments';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['name', 'code', 'description', 'color', 'is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]',
        'code' => 'required|min_length[2]|max_length[10]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Department name is required.',
        ],
        'code' => [
            'required' => 'Department code is required.',
        ],
    ];

    public function getActiveDepartments()
    {
        return $this->where('is_active', 1)->orderBy('name', 'ASC')->findAll();
    }

    public function getDepartmentWithStats($id)
    {
        $dept = $this->find($id);
        if ($dept) {
            $ticketModel = new TicketModel();
            $dept['today_total'] = $ticketModel->where('department_id', $id)
                                                ->where('queue_date', date('Y-m-d'))
                                                ->countAllResults();
            $dept['today_waiting'] = $ticketModel->where('department_id', $id)
                                                  ->where('queue_date', date('Y-m-d'))
                                                  ->where('status', 'waiting')
                                                  ->countAllResults();
            $dept['today_served'] = $ticketModel->where('department_id', $id)
                                                 ->where('queue_date', date('Y-m-d'))
                                                 ->where('status', 'served')
                                                 ->countAllResults();
        }
        return $dept;
    }
}
