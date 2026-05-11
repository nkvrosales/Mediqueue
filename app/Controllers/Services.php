<?php

namespace App\Controllers;

use App\Models\ServiceModel;
use App\Models\DepartmentModel;

class Services extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new ServiceModel();
    }

    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Services',
            'services'    => $this->model->getServicesWithDepartment(),
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('services/index', $data);
    }

    public function store()
    {
        $data = [
            'department_id' => $this->request->getPost('department_id'),
            'name'          => $this->request->getPost('name'),
            'code'          => strtoupper($this->request->getPost('code')),
            'description'   => $this->request->getPost('description'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/services')->with('success', 'Service created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function edit($id)
    {
        $service = $this->model->find($id);
        if (!$service) {
            return $this->response->setJSON(['error' => 'Service not found.']);
        }
        return $this->response->setJSON($service);
    }

    public function update($id)
    {
        $data = [
            'department_id' => $this->request->getPost('department_id'),
            'name'          => $this->request->getPost('name'),
            'code'          => strtoupper($this->request->getPost('code')),
            'description'   => $this->request->getPost('description'),
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->update($id, $data)) {
            return redirect()->to('/services')->with('success', 'Service updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id)
    {
        if ($this->model->delete($id)) {
            return redirect()->to('/services')->with('success', 'Service deleted successfully.');
        }

        return redirect()->to('/services')->with('error', 'Failed to delete service.');
    }
}
