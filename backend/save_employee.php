<?php
header('Content-Type: application/json');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// 1. Collect and sanitize input
$emp_number      = $_POST['emp_number'] ?? '';
$first_name      = trim($_POST['first_name'] ?? '');
$last_name       = trim($_POST['last_name'] ?? '');
$email           = trim($_POST['email'] ?? '');
$phone           = $_POST['phone'] ?? '';
$department      = $_POST['department'] ?? '';
$position        = trim($_POST['position'] ?? '');
$manager_id_raw  = $_POST['manager_id'] ?? '';
$employment_type = $_POST['employment_type'] ?? 'Full-Time';
$location        = $_POST['location'] ?? 'Headquarters';
$status          = $_POST['status'] ?? 'Active';
$emergency       = $_POST['emergency_contact'] ?? '';
$address         = $_POST['address'] ?? '';
$date_hired      = $_POST['date_hired'] ?? date('Y-m-d');
$age            = !empty($_POST['age']) ? (int)$_POST['age'] : null;
$base_salary    = !empty($_POST['base_salary']) ? (float)$_POST['base_salary'] : null;
$date_terminated = !empty($_POST['date_terminated']) ? $_POST['date_terminated'] : null;

// 2. Handle manager_number - convert empty string to NULL
$manager_number = null;
if (!empty($manager_id_raw) && trim($manager_id_raw) !== '') {
    $manager_number = trim($manager_id_raw);
    
    // Validate that the manager exists
    $check_stmt = $conn->prepare("SELECT employee_number FROM employees WHERE employee_number = ?");
    $check_stmt->bind_param("s", $manager_number);
    $check_stmt->execute();
    $manager_exists = $check_stmt->get_result()->fetch_assoc();
    $check_stmt->close();
    
    if (!$manager_exists) {
        echo json_encode([
            'success' => false, 
            'message' => "Invalid manager selected. Manager ID '$manager_number' does not exist."
        ]);
        exit;
    }
}

// 3. Basic validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($department) || empty($position)) {
    echo json_encode(['success' => false, 'message' => 'Required fields missing']);
    exit;
}

// 4. Resolve Department ID
$stmt = $conn->prepare("SELECT id FROM departments WHERE name = ?");
$stmt->bind_param("s", $department);
$stmt->execute();
$dept_row = $stmt->get_result()->fetch_assoc();

if (!$dept_row) {
    echo json_encode(['success' => false, 'message' => 'Invalid department']);
    exit;
}
$dept_id = $dept_row['id'];

try {
    if (!empty($emp_number)) {
        // =======================
        // UPDATE EXISTING EMPLOYEE
        // =======================
        $sql = "UPDATE employees SET
                first_name = ?, last_name = ?, email = ?, phone = ?,
                department_id = ?, position = ?, manager_number = ?,
                employment_type = ?, location = ?, status = ?,
                emergency_contact = ?, address = ?, date_hired = ?,
                age = ?, base_salary = ?, date_terminated = ? 
                WHERE employee_number = ?";

        $stmt = $conn->prepare($sql);
        $bind_types = "ssssississsssisds"; 
        
        $stmt->bind_param($bind_types, 
            $first_name, $last_name, $email, $phone,
            $dept_id, $position, $manager_number,
            $employment_type, $location, $status,
            $emergency, $address, $date_hired,
            $age, $base_salary, $date_terminated,
            $emp_number
        );

        if (!$stmt->execute()) {
            throw new Exception("Update failed: " . $stmt->error);
        }
        echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);

    } else {
        // =======================
        // ADD NEW EMPLOYEE
        // =======================
        
        // 1. Generate the ID FIRST
        $res = $conn->query("SELECT MAX(CAST(SUBSTRING(employee_number, 4) AS UNSIGNED)) AS max_num FROM employees");
        $row = $res->fetch_assoc();
        $next = ($row['max_num'] ?? 1000) + 1;
        $new_emp_number = 'EMP' . str_pad($next, 4, '0', STR_PAD_LEFT);

        // 2. Prepare the SQL
        $sql = "INSERT INTO employees (
            employee_number, first_name, last_name, email, phone,
            department_id, position, manager_number, employment_type,
            location, status, emergency_contact, address, date_hired,
            age, base_salary, date_terminated
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        $bind_types = "sssssisssssssisds"; 
        
        $stmt->bind_param($bind_types, 
            $new_emp_number,
            $first_name, $last_name, $email, $phone,
            $dept_id, $position, $manager_number,
            $employment_type, $location, $status,
            $emergency, $address, $date_hired,
            $age, $base_salary, $date_terminated
        );

        if ($stmt->execute()) {
            $conn->query("INSERT INTO leave_balances (employee_number) VALUES ('$new_emp_number')");
            echo json_encode(['success' => true, 'message' => 'Employee added successfully']);
        } else {
            throw new Exception("Insert failed: " . $stmt->error);
        }
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}