<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\DepartmentModel;

class Users extends BaseController
{
    protected $model;

    public function __construct()
    {
        $this->model = new UserModel();
    }

    public function index()
    {
        $deptModel = new DepartmentModel();
        $data = [
            'title'       => 'Users',
            'users'       => $this->model->getUsersWithDepartment(),
            'departments' => $deptModel->getActiveDepartments(),
        ];
        return view('users/index', $data);
    }

    public function store()
    {
        $data = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'password'      => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'full_name'     => $this->request->getPost('full_name'),
            'role'          => $this->request->getPost('role'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        if ($this->model->insert($data)) {
            return redirect()->to('/users')->with('success', 'User created successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function edit($id)
    {
        $user = $this->model->find($id);
        if (!$user) {
            return $this->response->setJSON(['error' => 'User not found.']);
        }
        unset($user['password']);
        return $this->response->setJSON($user);
    }

    public function update($id)
    {
        $data = [
            'username'      => $this->request->getPost('username'),
            'email'         => $this->request->getPost('email'),
            'full_name'     => $this->request->getPost('full_name'),
            'role'          => $this->request->getPost('role'),
            'department_id' => $this->request->getPost('department_id') ?: null,
            'is_active'     => $this->request->getPost('is_active') ? 1 : 0,
        ];

        // Only update password if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_DEFAULT);
        }

        if ($this->model->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'User updated successfully.');
        }

        return redirect()->back()->withInput()->with('errors', $this->model->errors());
    }

    public function delete($id)
    {
        // Prevent self-deletion
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account.');
        }

        if ($this->model->delete($id)) {
            return redirect()->to('/users')->with('success', 'User deleted successfully.');
        }

        return redirect()->to('/users')->with('error', 'Failed to delete user.');
    }
}
