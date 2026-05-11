<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentModel;

class Reports extends BaseController
{
    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Reports',
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('reports/index', $data);
    }

    public function generate()
    {
        $ticketModel = new TicketModel();

        $dateFrom     = $this->request->getPost('date_from') ?: date('Y-m-d');
        $dateTo       = $this->request->getPost('date_to') ?: date('Y-m-d');
        $departmentId = $this->request->getPost('department_id') ?: null;

        $tickets = $ticketModel->getReport($dateFrom, $dateTo, $departmentId);

        // Summary stats
        $total   = count($tickets);
        $served  = count(array_filter($tickets, fn($t) => $t['status'] === 'served'));
        $skipped = count(array_filter($tickets, fn($t) => $t['status'] === 'skipped'));

        // Average wait time
        $waitTimes = [];
        foreach ($tickets as $t) {
            if ($t['called_at'] && $t['issued_at']) {
                $waitTimes[] = (strtotime($t['called_at']) - strtotime($t['issued_at'])) / 60;
            }
        }
        $avgWait = count($waitTimes) > 0 ? round(array_sum($waitTimes) / count($waitTimes)) : 0;

        return $this->response->setJSON([
            'success' => true,
            'tickets' => $tickets,
            'summary' => [
                'total'    => $total,
                'served'   => $served,
                'skipped'  => $skipped,
                'avg_wait' => $avgWait,
            ],
        ]);
    }
}
