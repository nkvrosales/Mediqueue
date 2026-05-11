<?php

namespace App\Controllers;

use App\Models\CounterModel;
use App\Models\DepartmentModel;

class Counters extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new CounterModel();
    }

    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Counters',
            'counters'    => $this->model->getCountersWithDepartment(),
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('counters/index', $data);
    }

    public function store()
    {
        $data = [
            'department_id' => $this->request->getPost('department_id'),
            'name'          => $this->request->getPost('name'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/counters')->with('success', 'Counter created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function edit($id)
    {
        $counter = $this->model->find($id);
        if (!$counter) {
            return $this->response->setJSON(['error' => 'Counter not found.']);
        }
        return $this->response->setJSON($counter);
    }

    public function update($id)
    {
        $data = [
            'department_id' => $this->request->getPost('department_id'),
            'name'          => $this->request->getPost('name'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/counters')->with('success', 'Counter updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/counters')->with('success', 'Counter deleted successfully.');
        }

        return redirect()->to('/counters')->with('error', 'Failed to delete counter.');
    }
}
