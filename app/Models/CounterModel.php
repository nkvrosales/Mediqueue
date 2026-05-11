<?php

namespace App\Models;

use CodeIgniter\Model;

class CounterModel extends Model
{
    protected $table            = 'counters';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['department_id', 'name', 'is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'department_id' => 'required|integer',
        'name'          => 'required|min_length[1]|max_length[50]',
    ];

    public function getCountersByDepartment($departmentId)
    {
        return $this->where('department_id', $departmentId)
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getCountersWithDepartment()
    {
        return $this->select('counters.*, departments.name as department_name')
                    ->join('departments', 'departments.id = counters.department_id')
                    ->orderBy('departments.name', 'ASC')
                    ->orderBy('counters.name', 'ASC')
                    ->findAll();
    }
}
