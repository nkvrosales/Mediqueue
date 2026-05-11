<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use App\Models\ServiceModel;
use App\Models\TicketModel;

class Kiosk extends BaseController
{
    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Patient Kiosk',
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('kiosk/index', $data);
    }

    public function getServices($departmentId)
    {
        $serviceModel = new ServiceModel();
        $services = $serviceModel->getServicesByDepartment($departmentId);
        return $this->response->setJSON(['services' => $services]);
    }

    public function generate()
    {
        $ticketModel = new TicketModel();

        $departmentId = $this->request->getPost('department_id');
        $serviceId    = $this->request->getPost('service_id') ?: null;
        $patientName  = $this->request->getPost('patient_name') ?: null;
        $priority     = $this->request->getPost('priority') ?: 'normal';

        $ticket = $ticketModel->createTicket($departmentId, $serviceId, $patientName, $priority);

        if ($ticket) {
            // Get department info
            $deptModel = new DepartmentModel();
            $dept = $deptModel->find($departmentId);

            // Count waiting before this ticket
            $waitingCount = $ticketModel->where('department_id', $departmentId)
                                        ->where('queue_date', date('Y-m-d'))
                                        ->where('status', 'waiting')
                                        ->where('id <', $ticket['id'])
                                        ->countAllResults();

            return $this->response->setJSON([
                'success'        => true,
                'ticket'         => $ticket,
                'department'     => $dept,
                'waiting_before' => $waitingCount,
            ]);
        }

        return $this->response->setJSON([
            'success' => false,
            'message' => 'Failed to generate ticket.',
        ]);
    }
}
