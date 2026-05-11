<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function index()
    {
        if (session()->get('isLoggedIn')) {
            return redirect()->to('/dashboard');
        }
        return view('auth/login');
    }

    public function login()
    {
        $userModel = new UserModel();
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = $userModel->authenticate($username, $password);

        if ($user) {
            $sessionData = [
                'user_id'       => $user['id'],
                'username'      => $user['username'],
                'full_name'     => $user['full_name'],
                'email'         => $user['email'],
                'role'          => $user['role'],
                'department_id' => $user['department_id'],
                'isLoggedIn'    => true,
            ];
            session()->set($sessionData);

            if ($user['role'] === 'admin') {
                return redirect()->to('/dashboard');
            }
            return redirect()->to('/queue');
        }

        return redirect()->back()->with('error', 'Invalid username or password.');
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
