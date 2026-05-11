<?php

namespace App\Models;

use CodeIgniter\Model;

class ServiceModel extends Model
{
    protected $table            = 'services';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $allowedFields    = ['department_id', 'name', 'code', 'description', 'is_active'];
    protected $useTimestamps    = true;
    protected $createdField     = 'created_at';
    protected $updatedField     = 'updated_at';

    protected $validationRules = [
        'department_id' => 'required|integer',
        'name'          => 'required|min_length[2]|max_length[100]',
        'code'          => 'required|min_length[1]|max_length[10]',
    ];

    public function getServicesByDepartment($departmentId)
    {
        return $this->where('department_id', $departmentId)
                    ->where('is_active', 1)
                    ->orderBy('name', 'ASC')
                    ->findAll();
    }

    public function getServicesWithDepartment()
    {
        return $this->select('services.*, departments.name as department_name, departments.code as department_code')
                    ->join('departments', 'departments.id = services.department_id')
                    ->orderBy('departments.name', 'ASC')
                    ->orderBy('services.name', 'ASC')
                    ->findAll();
    }
}
