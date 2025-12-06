<?php
// backend/save_employee.php
header('Content-Type: application/json');
require_once '../config/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
    exit;
}

// Collect and sanitize input
$emp_number     = $_POST['emp_number'] ?? '';        // for edit
$first_name     = trim($_POST['first_name'] ?? '');
$last_name      = trim($_POST['last_name'] ?? '');
$email          = trim($_POST['email'] ?? '');
$phone          = $_POST['phone'] ?? '';
$department     = $_POST['department'] ?? '';
$position       = trim($_POST['position'] ?? '');
$manager_number = $_POST['manager_id'] ?: null;
$employment_type= $_POST['employment_type'] ?? 'Full-Time';
$location       = $_POST['location'] ?? 'Headquarters';
$status         = $_POST['status'] ?? 'Active';
$emergency      = $_POST['emergency_contact'] ?? '';
$address        = $_POST['address'] ?? '';
$date_hired     = $_POST['date_hired'] ?? date('Y-m-d');

// The '?: null' ensures they are correctly handled as NULL if the form field is empty.
$age            = $_POST['age'] ?: null;
$base_salary    = $_POST['base_salary'] ?: null;
$date_terminated= $_POST['date_terminated'] ?: null;

// Basic validation
if (empty($first_name) || empty($last_name) || empty($email) || empty($department) || empty($position)) {
    echo json_encode(['success' => false, 'message' => 'Required fields missing']);
    exit;
}

// Get department_id from name
$stmt = $conn->prepare("SELECT id FROM departments WHERE name = ?");
$stmt->bind_param("s", $department);
$stmt->execute();
$result = $stmt->get_result();
$dept_row = $result->fetch_assoc();

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
        // Bind type string is correct: 17 characters for 17 variables
        $stmt->bind_param("ssssissssssssssss", 
            $first_name, $last_name, $email, $phone,
            $dept_id, $position, $manager_number,
            $employment_type, $location, $status,
            $emergency, $address, $date_hired,
            $age, $base_salary, $date_terminated, // New fields
            $emp_number // WHERE condition
        );

        if ($stmt->execute()) {
            echo json_encode(['success' => true, 'message' => 'Employee updated successfully']);
        } else {
            echo json_encode(['success' => false, 'message' => 'Update failed: ' . $stmt->error]);
        }

    } else {
        // =======================
        // ADD NEW EMPLOYEE
        // =======================
        // Generate next EMP number
        $result = $conn->query("SELECT MAX(CAST(SUBSTRING(employee_number, 4) AS UNSIGNED)) AS max_num FROM employees");
        $row = $result->fetch_assoc();
        $next = ($row['max_num'] ?? 1000) + 1;
        $new_emp_number = 'EMP' . str_pad($next, 4, '0', STR_PAD_LEFT);

        $sql = "INSERT INTO employees (
            employee_number, first_name, last_name, email, phone,
            department_id, position, manager_number, employment_type,
            location, status, emergency_contact, address, date_hired,
            age, base_salary, date_terminated
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

        $stmt = $conn->prepare($sql);
        // <<< FIX HERE: Added the missing 's'. Now 17 characters for 17 variables.
        $stmt->bind_param("sssssisssssssssss", 
            $new_emp_number, $first_name, $last_name, $email, $phone,
            $dept_id, $position, $manager_number,
            $employment_type, $location, $status,
            $emergency, $address, $date_hired,
            $age, $base_salary, $date_terminated // New fields
        );

        if ($stmt->execute()) {
            // Create leave balance record
            $conn->query("INSERT INTO leave_balances (employee_number) VALUES ('$new_emp_number')");

            echo json_encode([
                'success' => true,
                'message' => 'Employee added successfully',
                'employee_number' => $new_emp_number
            ]);
        } else {
            echo json_encode(['success' => false, 'message' => 'Insert failed: ' . $stmt->error]);
        }
    }

} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}
?>