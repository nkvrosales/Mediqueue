<?php

namespace App\Controllers;

use App\Models\TicketModel;
use App\Models\DepartmentModel;

class Dashboard extends BaseController
{
    public function index()
    {
        $ticketModel = new TicketModel();
        $deptModel = new DepartmentModel();

        $data = [
            'title'       => 'Dashboard',
            'stats'       => $ticketModel->getTodayStats(),
            'departments' => $deptModel->getActiveDepartments(),
            'deptStats'   => $ticketModel->getDepartmentStats(),
        ];

        return view('dashboard/index', $data);
    }

    public function getStats()
    {
        $ticketModel = new TicketModel();
        $departmentId = $this->request->getGet('department_id');

        return $this->response->setJSON([
            'stats' => $ticketModel->getTodayStats($departmentId ?: null),
        ]);
    }

    public function getChartData()
    {
        $ticketModel = new TicketModel();
        $deptStats = $ticketModel->getDepartmentStats();

        $labels = [];
        $totals = [];
        $served = [];
        $waiting = [];
        $colors = [];

        foreach ($deptStats as $stat) {
            $labels[] = $stat['department_name'];
            $totals[] = (int) $stat['total'];
            $served[] = (int) $stat['served'];
            $waiting[] = (int) $stat['waiting'];
            $colors[] = $stat['color'];
        }

        return $this->response->setJSON([
            'labels'  => $labels,
            'totals'  => $totals,
            'served'  => $served,
            'waiting' => $waiting,
            'colors'  => $colors,
        ]);
    }
}
