<?php
header('Content-Type: application/json');
require_once "../config/db.php";

try {
    $sql = "
        SELECT 
            employee_number,
            full_name,
            birthdate,
            d.name AS department_name
        FROM employees e
        LEFT JOIN departments d ON e.department_id = d.id
        WHERE birthdate IS NOT NULL
          AND status = 'Active'
        ORDER BY MONTH(birthdate), DAY(birthdate)
    ";

    $result = $conn->query($sql);

    $birthdays = [];
    $currentYear = date('Y');
    $nextYear = $currentYear + 1;
    $currentMonthDay = date('m-d');

    while ($emp = $result->fetch_assoc()) {
        $birthMonthDay = date('m-d', strtotime($emp['birthdate']));
        $displayDate = date('F j', strtotime($emp['birthdate']));
        $birthYear = date('Y', strtotime($emp['birthdate']));

        $nextBirthdayYear = ($currentMonthDay > $birthMonthDay) ? $nextYear : $currentYear;
        $age = $nextBirthdayYear - $birthYear;

        $names = explode(' ', $emp['full_name']);
        $initials = substr(implode('', array_map(fn($n) => strtoupper($n[0] ?? ''), $names)), 0, 2);

        $birthdays[] = [
            'initials' => $initials ?: '??',
            'full_name' => $emp['full_name'],
            'department' => $emp['department_name'] ?? 'N/A',
            'birthday_display' => $displayDate,
            'birth_month_day' => $birthMonthDay,
            'age_next' => $age,
            'next_year' => $nextBirthdayYear
        ];
    }

    echo json_encode(['success' => true, 'birthdays' => $birthdays]);

} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
}

$conn->close();
?>