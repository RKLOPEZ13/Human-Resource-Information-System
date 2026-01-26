<?php
header('Content-Type: application/json');
require_once '../config/db.php';

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
        e.date_terminated,
        e.age,
        e.base_salary,
        e.department_id,
        d.name AS department,
        e.manager_number,
        COALESCE(CONCAT(m.first_name,' ',m.last_name), '—') AS manager_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    LEFT JOIN employees m ON e.manager_number = m.employee_number
    WHERE e.status != 'Terminated'
    ORDER BY e.employee_number ASC
";

$result = $conn->query($sql);

if ($result) {
    $data = [];
    while ($row = $result->fetch_assoc()) {
        // Handle empty or invalid dates
        if (!$row['date_hired'] || $row['date_hired'] == '0000-00-00') {
            $row['date_hired'] = '';
        }
        if (!$row['date_terminated'] || $row['date_terminated'] == '0000-00-00') {
            $row['date_terminated'] = '';
        }
        $data[] = $row;
    }
    
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