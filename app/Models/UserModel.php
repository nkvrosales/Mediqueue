<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table            = 'users';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['username', 'email', 'password', 'full_name', 'role', 'department_id', 'is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'username'  => 'required|min_length[3]|max_length[50]',
        'email'     => 'required|valid_email|max_length[100]',
        'full_name' => 'required|min_length[2]|max_length[100]',
        'role'      => 'required|in_list[admin,staff]',
    ];

    public function authenticate($username, $password)
    {
        $user = $this->where('username', $username)
                     ->where('is_active', 1)
                     ->first();

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }

        return false;
    }

    public function getUsersWithDepartment()
    {
        return $this->select('users.*, departments.name as department_name')
                    ->join('departments', 'departments.id = users.department_id', 'left')
                    ->orderBy('users.full_name', 'ASC')
                    ->findAll();
    }
}
