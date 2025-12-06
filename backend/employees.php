<?php
header('Content-Type: application/json');
require_once '../config/db.php';   // correct path

$sql = "
    SELECT 
        e.employee_number,
        e.first_name,
        e.last_name,
        e.email,
        e.phone,
        e.emergency_contact,
        e.address,
        e.position,
        e.employment_type,
        e.location,
        e.status,
        e.date_hired,
        e.date_terminated,     -- ADDED
        e.age,                 -- ADDED
        e.base_salary,         -- ADDED
        d.name AS department,
        COALESCE(CONCAT(m.first_name,' ',m.last_name), '—') AS manager_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    LEFT JOIN employees m ON e.manager_number = m.employee_number
    WHERE e.status != 'Terminated'
    ORDER BY e.employee_number ASC
";

$result = $conn->query($sql);

if ($result) {
    $data = $result->fetch_all(MYSQLI_ASSOC);
    echo json_encode([
        'success' => true,
        'data'    => $data,
        'count'   => count($data),
        'timestamp' => date('c')
    ]);
} else {
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => $conn->error]);
}
?>