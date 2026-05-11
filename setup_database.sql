-- Hospital Ticketing System Database Setup
-- Run this script after starting XAMPP MySQL:
--   sudo /opt/lampp/lampp startmysql
--   /opt/lampp/bin/mysql -u root < setup_database.sql

CREATE DATABASE IF NOT EXISTS hospital_ticketing;
USE hospital_ticketing;

-- Departments
CREATE TABLE IF NOT EXISTS departments (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL,
    description TEXT NULL,
    color VARCHAR(7) DEFAULT '#0d6efd',
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    UNIQUE KEY code (code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Services
CREATE TABLE IF NOT EXISTS services (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(100) NOT NULL,
    code VARCHAR(10) NOT NULL,
    description TEXT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Counters
CREATE TABLE IF NOT EXISTS counters (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    department_id INT(11) UNSIGNED NOT NULL,
    name VARCHAR(50) NOT NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Users
CREATE TABLE IF NOT EXISTS users (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL,
    email VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    full_name VARCHAR(100) NOT NULL,
    role ENUM('admin','staff') DEFAULT 'staff',
    department_id INT(11) UNSIGNED NULL,
    is_active TINYINT(1) DEFAULT 1,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    UNIQUE KEY username (username),
    UNIQUE KEY email (email)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tickets
CREATE TABLE IF NOT EXISTS tickets (
    id INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    ticket_number VARCHAR(20) NOT NULL,
    department_id INT(11) UNSIGNED NOT NULL,
    service_id INT(11) UNSIGNED NULL,
    counter_id INT(11) UNSIGNED NULL,
    served_by INT(11) UNSIGNED NULL,
    patient_name VARCHAR(100) NULL,
    status ENUM('waiting','serving','served','skipped','cancelled') DEFAULT 'waiting',
    priority ENUM('normal','priority') DEFAULT 'normal',
    issued_at DATETIME NULL,
    called_at DATETIME NULL,
    completed_at DATETIME NULL,
    queue_date DATE NOT NULL,
    created_at DATETIME NULL,
    updated_at DATETIME NULL,
    KEY queue_date (queue_date),
    KEY status (status),
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Seed Data
INSERT INTO departments (name, code, description, color, is_active, created_at, updated_at) VALUES
('Out-Patient Department', 'OPD', 'General outpatient consultation and check-ups', '#6366f1', 1, NOW(), NOW()),
('Emergency Room', 'ER', 'Emergency and urgent care services', '#ef4444', 1, NOW(), NOW()),
('Laboratory', 'LAB', 'Blood tests, urinalysis, and other lab services', '#10b981', 1, NOW(), NOW()),
('Radiology', 'RAD', 'X-ray, CT scan, MRI, and ultrasound services', '#f59e0b', 1, NOW(), NOW()),
('Pharmacy', 'PH', 'Prescription dispensing and medication counseling', '#8b5cf6', 1, NOW(), NOW()),
('Cashier', 'CSH', 'Billing, payments, and financial transactions', '#06b6d4', 1, NOW(), NOW());

INSERT INTO services (department_id, name, code, is_active, created_at, updated_at) VALUES
(1, 'General Consultation', 'GC', 1, NOW(), NOW()),
(1, 'Follow-up Check-up', 'FC', 1, NOW(), NOW()),
(1, 'Medical Certificate', 'MC', 1, NOW(), NOW()),
(2, 'Emergency Triage', 'ET', 1, NOW(), NOW()),
(3, 'Blood Test', 'BT', 1, NOW(), NOW()),
(3, 'Urinalysis', 'UA', 1, NOW(), NOW()),
(4, 'X-Ray', 'XR', 1, NOW(), NOW()),
(4, 'Ultrasound', 'US', 1, NOW(), NOW()),
(5, 'Prescription Pickup', 'PP', 1, NOW(), NOW()),
(6, 'Bill Payment', 'BP', 1, NOW(), NOW());

INSERT INTO counters (department_id, name, is_active, created_at, updated_at) VALUES
(1, 'Window 1', 1, NOW(), NOW()),
(1, 'Window 2', 1, NOW(), NOW()),
(2, 'Window 1', 1, NOW(), NOW()),
(3, 'Window 1', 1, NOW(), NOW()),
(3, 'Window 2', 1, NOW(), NOW()),
(4, 'Window 1', 1, NOW(), NOW()),
(5, 'Window 1', 1, NOW(), NOW()),
(5, 'Window 2', 1, NOW(), NOW()),
(6, 'Cashier 1', 1, NOW(), NOW()),
(6, 'Cashier 2', 1, NOW(), NOW());

-- Admin: admin / admin123 | Staff: staff_opd / staff123 | Staff: staff_lab / staff123
INSERT INTO users (username, email, password, full_name, role, department_id, is_active, created_at, updated_at) VALUES
('admin', 'admin@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'System Administrator', 'admin', NULL, 1, NOW(), NOW()),
('staff_opd', 'opd@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'OPD Staff', 'staff', 1, 1, NOW(), NOW()),
('staff_lab', 'lab@hospital.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Laboratory Staff', 'staff', 3, 1, NOW(), NOW());

SELECT 'Database setup complete!' AS status;
