<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentModel;

class Display extends BaseController
{
    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Now Serving',
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('display/index', $data);
    }

    public function getData()
    {
        $ticketModel = new TicketModel();
        $deptModel = new DepartmentModel();

        $serving = $ticketModel->getAllCurrentlyServing();
        $departments = $deptModel->getActiveDepartments();

        // Group serving by department
        $servingByDept = [];
        foreach ($serving as $ticket) {
            $deptId = $ticket['department_id'];
            if (!isset($servingByDept[$deptId])) {
                $servingByDept[$deptId] = [];
            }
            $servingByDept[$deptId][] = $ticket;
        }

        // Get waiting counts per department
        $waitingCounts = [];
        foreach ($departments as $dept) {
            $waitingCounts[$dept['id']] = $ticketModel
                ->where('department_id', $dept['id'])
                ->where('queue_date', date('Y-m-d'))
                ->where('status', 'waiting')
                ->countAllResults();
        }

        return $this->response->setJSON([
            'serving'       => $servingByDept,
            'departments'   => $departments,
            'waitingCounts' => $waitingCounts,
            'timestamp'     => date('H:i:s'),
        ]);
    }
}
