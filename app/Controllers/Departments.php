<?php

namespace App\Controllers;

use App\Models\DepartmentModel;

class Departments extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new DepartmentModel();
    }

    public function index()
    {
        $data = [
            'title'       => 'Departments',
            'departments' => $this->model->orderBy('name', 'ASC')->findAll(),
        ];
        return view('departments/index', $data);
    }

    public function store()
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
            'color'       => $this->request->getPost('color') ?: '#0d6efd',
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/departments')->with('success', 'Department created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function edit($id)
    {
        $department = $this->model->find($id);
        if (!$department) {
            return $this->response->setJSON(['error' => 'Department not found.']);
        }
        return $this->response->setJSON($department);
    }

    public function update($id)
    {
        $data = [
            'name'        => $this->request->getPost('name'),
            'code'        => strtoupper($this->request->getPost('code')),
            'description' => $this->request->getPost('description'),
            'color'       => $this->request->getPost('color') ?: '#0d6efd',
            'is_active'   => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/departments')->with('success', 'Department updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/departments')->with('success', 'Department deleted successfully.');
        }

        return redirect()->to('/departments')->with('error', 'Failed to delete department.');
    }
}
