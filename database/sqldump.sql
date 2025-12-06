-- HR MANAGEMENT SYSTEM - FINAL CLEAN & PROFESSIONAL SCHEMA
-- Uses employee_number as the ONLY employee identifier across ALL tables
-- Fresh system: only departments, employees, leave balances, users have data

DROP DATABASE IF EXISTS hr_management_system;
CREATE DATABASE hr_management_system CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE hr_management_system;

-- ============================================================
-- 1. DEPARTMENTS
-- ============================================================
CREATE TABLE departments (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL UNIQUE,
    description TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 2. EMPLOYEES - employee_number is now the PRIMARY KEY
-- ============================================================
CREATE TABLE employees (
    employee_number VARCHAR(20) PRIMARY KEY,
    first_name VARCHAR(50) NOT NULL,
    last_name VARCHAR(50) NOT NULL,
    full_name VARCHAR(100) GENERATED ALWAYS AS (CONCAT(first_name, ' ', last_name)) STORED,
    email VARCHAR(100) UNIQUE NOT NULL,
    phone VARCHAR(20),
    emergency_contact VARCHAR(255),
    address TEXT,
    department_id INT,
    position VARCHAR(100) NOT NULL,
    manager_number VARCHAR(20) NULL,
    employment_type ENUM('Full-Time', 'Part-Time', 'Contract', 'Intern') DEFAULT 'Full-Time',
    location ENUM('Headquarters', 'Remote', 'Remote', 'Branch Office') DEFAULT 'Headquarters',
    status ENUM('Active', 'On Leave', 'Terminated', 'Suspended') DEFAULT 'Active',
    date_hired DATE NOT NULL,
    date_terminated DATE NULL,
    age INT,
    base_salary DECIMAL(10,2),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE SET NULL,
    FOREIGN KEY (manager_number) REFERENCES employees(employee_number) ON DELETE SET NULL,
    INDEX idx_email (email),
    INDEX idx_status (status),
    INDEX idx_department (department_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 3. ATTENDANCE RECORDS (empty)
-- ============================================================
CREATE TABLE attendance_records (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_number VARCHAR(20) NOT NULL,
    date DATE NOT NULL,
    status ENUM('P', 'A', 'L', 'VL', 'SL', '-') NOT NULL,
    time_in TIME NULL,
    time_out TIME NULL,
    undertime_hours DECIMAL(4,2) DEFAULT 0,
    overtime_hours DECIMAL(4,2) DEFAULT 0,
    notes TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE,
    UNIQUE KEY unique_emp_date (employee_number, date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 4. LEAVE BALANCES - FULL FRESH CREDITS
-- ============================================================
CREATE TABLE leave_balances (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_number VARCHAR(20) NOT NULL UNIQUE,
    vacation_leave INT DEFAULT 15,
    sick_leave INT DEFAULT 10,
    emergency_leave INT DEFAULT 5,
    maternity_leave INT DEFAULT 105,
    paternity_leave INT DEFAULT 15,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 5. LEAVE REQUESTS (empty)
-- ============================================================
CREATE TABLE leave_requests (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_number VARCHAR(20) NOT NULL,
    leave_type ENUM('VL', 'SL', 'Emergency', 'Maternity', 'Paternity') NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    days_requested INT NOT NULL,
    reason TEXT NOT NULL,
    attachment_path VARCHAR(255) NULL,
    status ENUM('Pending', 'Approved', 'Rejected') DEFAULT 'Pending',
    requested_at DATETIME DEFAULT CURRENT_TIMESTAMP,
    approved_by VARCHAR(20) NULL,
    approval_date DATETIME NULL,
    rejection_reason TEXT NULL,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE,
    FOREIGN KEY (approved_by) REFERENCES employees(employee_number) ON DELETE SET NULL,
    CHECK (end_date >= start_date)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 6. ANNOUNCEMENTS TABLES (empty)
-- ============================================================
CREATE TABLE announcements (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subject VARCHAR(255) NOT NULL,
    header TEXT,
    body TEXT NOT NULL,
    closing TEXT,
    issued_by VARCHAR(100) DEFAULT 'HR Department',
    created_by VARCHAR(20) NOT NULL,
    delivery_channels JSON,
    target_type ENUM('All', 'Department', 'Individual') NOT NULL,
    status ENUM('Draft', 'Queued', 'Sent', 'Failed') DEFAULT 'Queued',
    sent_at DATETIME NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (created_by) REFERENCES employees(employee_number) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE announcement_recipients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    announcement_id INT NOT NULL,
    employee_number VARCHAR(20) NULL,
    department_id INT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    read_at DATETIME NULL,
    FOREIGN KEY (announcement_id) REFERENCES announcements(id) ON DELETE CASCADE,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE,
    FOREIGN KEY (department_id) REFERENCES departments(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- ============================================================
-- 7. AUTH TABLES
-- ============================================================
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    employee_number VARCHAR(20) UNIQUE NOT NULL,
    username VARCHAR(50) UNIQUE NOT NULL,
    password_hash VARCHAR(255) NOT NULL,
    role ENUM('Admin', 'Manager', 'HR', 'Employee') DEFAULT 'Employee',
    last_login DATETIME NULL,
    is_active BOOLEAN DEFAULT TRUE,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (employee_number) REFERENCES employees(employee_number) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE user_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT UNIQUE NOT NULL,
    email_notifications BOOLEAN DEFAULT TRUE,
    new_products_notifications BOOLEAN DEFAULT TRUE,
    marketing_notifications BOOLEAN DEFAULT FALSE,
    profile_image VARCHAR(255),
    social_links JSON,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================================
-- INSERT ONLY REQUIRED DATA (as instructed)
-- ============================================================

-- Departments
INSERT INTO departments (name, description) VALUES
('IT', 'Information Technology Department'),
('Human Resources', 'Human Resources Department'),
('Finance', 'Finance and Accounting Department'),
('Sales', 'Sales Department'),
('Marketing', 'Marketing Department'),
('Engineering', 'Engineering Department'),
('Operations', 'Operations Department'),
('Customer Support', 'Customer Support Department');

-- Employees
INSERT INTO employees (
    employee_number, first_name, last_name, email, phone, emergency_contact, address,
    department_id, position, manager_number, employment_type, location, status, date_hired, age, base_salary
) VALUES
('EMP1001', 'Jane',    'Smith',    'jane.smith@company.com',    '+63 912 345 6789', 'John Smith - 0917xxx', 'Manila', 2, 'HR Manager',        NULL,     'Full-Time', 'Headquarters', 'Active', '2022-05-20', 35, 75000.00),
('EMP1002', 'John',    'Doe',      'john.doe@company.com',      '+63 917 123 4567', 'Mary Doe',           'Quezon City', 6, 'Software Engineer', 'EMP1001', 'Full-Time', 'Headquarters', 'Active', '2023-01-15', 29, 85000.00),
('EMP1003', 'Maria',   'Santos',   'maria.santos@company.com',  '+63 918 234 5678', 'Pedro Santos',       'Makati', 2, 'HR Specialist',     'EMP1001', 'Full-Time', 'Headquarters', 'Active', '2023-03-10', 27, 48000.00),
('EMP1004', 'Pedro',   'Reyes',    'pedro.reyes@company.com',   '+63 919 345 6789', 'Ana Reyes',          'Remote', 3, 'Accountant',        'EMP1001', 'Full-Time', 'Remote',       'Active', '2022-11-01', 32, 62000.00),
('EMP1005', 'Sarah',   'Connor',   'sarah.connor@company.com',  '+63 927 456 7890', 'Kyle Reese',         'Cebu',   4, 'Sales Executive',   'EMP1001', 'Full-Time', 'Branch Office','Active', '2024-02-01', 31, 65000.00),
('EMP1006', 'Mark',    'Lee',      'mark.lee@company.com',      '+63 928 567 8901', 'Alice Lee',          'Pasig',  1, 'Web Developer',     'EMP1002', 'Full-Time', 'Headquarters', 'Active', '2023-08-15', 26, 58000.00);

-- Full Fresh Leave Credits for ALL employees
INSERT INTO leave_balances (employee_number, vacation_leave, sick_leave, emergency_leave, maternity_leave, paternity_leave)
SELECT employee_number, 15, 10, 5, 105, 15 FROM employees;

-- Users (HR Admin + 1 regular user)
INSERT INTO users (employee_number, username, password_hash, role) VALUES
('EMP1001', 'janesmith', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'HR'),      -- password: password
('EMP1002', 'johndoe',   '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Employee'); -- password: password

-- ============================================================
-- DONE! Clean, complete, consistent, and ready for biometric page
-- ============================================================