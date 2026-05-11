<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentModel;
use App\Models\CounterModel;

class Queue extends BaseController
{
    public function index()
    {
        $deptModel = new DepartmentModel();
        $counterModel = new CounterModel();
        $ticketModel = new TicketModel();

        $session = session();
        $departmentId = $session->get('department_id');

        // Admin can see all departments
        if ($session->get('role') === 'admin') {
            $departments = $deptModel->getActiveDepartments();
            $departmentId = $departmentId ?: ($departments[0]['id'] ?? null);
        } else {
            $departments = $departmentId ? [$deptModel->find($departmentId)] : [];
        }

        $counters = $departmentId ? $counterModel->getCountersByDepartment($departmentId) : [];
        $currentServing = $departmentId ? $ticketModel->getCurrentServing($departmentId) : null;
        $waitingTickets = $departmentId ? $ticketModel->getWaitingTickets($departmentId) : [];
        $recentServed = $departmentId ? $ticketModel->getRecentServed($departmentId) : [];

        $data = [
            'title'          => 'Queue Management',
            'departments'    => $departments,
            'counters'       => $counters,
            'currentServing' => $currentServing,
            'waitingTickets' => $waitingTickets,
            'recentServed'   => $recentServed,
            'departmentId'   => $departmentId,
        ];

        return view('queue/index', $data);
    }

    public function callNext()
    {
        $ticketModel = new TicketModel();

        $departmentId = $this->request->getPost('department_id');
        $counterId    = $this->request->getPost('counter_id');
        $staffId      = session()->get('user_id');

        $ticket = $ticketModel->callNext($departmentId, $counterId, $staffId);

        if ($ticket) {
            return $this->response->setJSON([
                'success' => true,
                'ticket'  => $ticket,
                'message' => 'Now serving: ' . $ticket['ticket_number'],
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'No more patients in the queue.',
        ]);
    }

    public function complete($id)
    {
        $ticketModel = new TicketModel();
        $ticketModel->completeTicket($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Patient marked as served.',
        ]);
    }

    public function skip($id)
    {
        $ticketModel = new TicketModel();
        $ticketModel->skipTicket($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Patient skipped.',
        ]);
    }

    public function recall($id)
    {
        $ticketModel = new TicketModel();
        $ticketModel->recallTicket($id);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Patient recalled to queue.',
        ]);
    }

    public function getStatus($departmentId = null)
    {
        $ticketModel = new TicketModel();
        $counterModel = new CounterModel();

        if (!$departmentId) {
            $departmentId = $this->request->getGet('department_id') ?: session()->get('department_id');
        }

        $currentServing = $departmentId ? $ticketModel->getCurrentServing($departmentId) : null;
        $waitingTickets = $departmentId ? $ticketModel->getWaitingTickets($departmentId) : [];
        $recentServed   = $departmentId ? $ticketModel->getRecentServed($departmentId) : [];
        $stats          = $departmentId ? $ticketModel->getTodayStats($departmentId) : [];
        $counters       = $departmentId ? $counterModel->getCountersByDepartment($departmentId) : [];

        return $this->response->setJSON([
            'currentServing' => $currentServing,
            'waitingTickets' => $waitingTickets,
            'recentServed'   => $recentServed,
            'stats'          => $stats,
            'counters'       => $counters,
        ]);
    }
}
