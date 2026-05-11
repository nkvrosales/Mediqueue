<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InitialSeeder extends Seeder
{
    public function run()
    {
        // Departments
        $departments = [
            [
                'name'        => 'Out-Patient Department',
                'code'        => 'OPD',
                'description' => 'General outpatient consultation and check-ups',
                'color'       => '#6366f1',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Emergency Room',
                'code'        => 'ER',
                'description' => 'Emergency and urgent care services',
                'color'       => '#ef4444',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Laboratory',
                'code'        => 'LAB',
                'description' => 'Blood tests, urinalysis, and other lab services',
                'color'       => '#10b981',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Radiology',
                'code'        => 'RAD',
                'description' => 'X-ray, CT scan, MRI, and ultrasound services',
                'color'       => '#f59e0b',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Pharmacy',
                'code'        => 'PH',
                'description' => 'Prescription dispensing and medication counseling',
                'color'       => '#8b5cf6',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
            [
                'name'        => 'Cashier',
                'code'        => 'CSH',
                'description' => 'Billing, payments, and financial transactions',
                'color'       => '#06b6d4',
                'is_active'   => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('departments')->insertBatch($departments);

        // Services
        $services = [
            ['department_id' => 1, 'name' => 'General Consultation', 'code' => 'GC', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 1, 'name' => 'Follow-up Check-up', 'code' => 'FC', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 1, 'name' => 'Medical Certificate', 'code' => 'MC', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 2, 'name' => 'Emergency Triage', 'code' => 'ET', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 3, 'name' => 'Blood Test', 'code' => 'BT', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 3, 'name' => 'Urinalysis', 'code' => 'UA', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 4, 'name' => 'X-Ray', 'code' => 'XR', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 4, 'name' => 'Ultrasound', 'code' => 'US', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 5, 'name' => 'Prescription Pickup', 'code' => 'PP', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 6, 'name' => 'Bill Payment', 'code' => 'BP', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('services')->insertBatch($services);

        // Counters
        $counters = [
            ['department_id' => 1, 'name' => 'Window 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 1, 'name' => 'Window 2', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 2, 'name' => 'Window 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 3, 'name' => 'Window 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 3, 'name' => 'Window 2', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 4, 'name' => 'Window 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 5, 'name' => 'Window 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 5, 'name' => 'Window 2', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 6, 'name' => 'Cashier 1', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
            ['department_id' => 6, 'name' => 'Cashier 2', 'is_active' => 1, 'created_at' => date('Y-m-d H:i:s'), 'updated_at' => date('Y-m-d H:i:s')],
        ];

        $this->db->table('counters')->insertBatch($counters);

        // Admin User (password: admin123)
        $users = [
            [
                'username'      => 'admin',
                'email'         => 'admin@hospital.com',
                'password'      => password_hash('admin123', PASSWORD_DEFAULT),
                'full_name'     => 'System Administrator',
                'role'          => 'admin',
                'department_id' => null,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'staff_opd',
                'email'         => 'opd@hospital.com',
                'password'      => password_hash('staff123', PASSWORD_DEFAULT),
                'full_name'     => 'OPD Staff',
                'role'          => 'staff',
                'department_id' => 1,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
            [
                'username'      => 'staff_lab',
                'email'         => 'lab@hospital.com',
                'password'      => password_hash('staff123', PASSWORD_DEFAULT),
                'full_name'     => 'Laboratory Staff',
                'role'          => 'staff',
                'department_id' => 3,
                'is_active'     => 1,
                'created_at'    => date('Y-m-d H:i:s'),
                'updated_at'    => date('Y-m-d H:i:s'),
            ],
        ];

        $this->db->table('users')->insertBatch($users);
    }
}
