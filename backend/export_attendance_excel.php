<?php
ini_set('display_errors', 0);
error_reporting(0);

session_start();
require '../config/db.php';

require_once __DIR__ . '/../vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;

/* ================= INPUT ================= */
$monthStr   = $_GET['month'] ?? date('Y-m');
$deptFilter = $_GET['dept'] ?? '';
$search     = $_GET['search'] ?? '';

$startDate   = $monthStr . '-01';
$endDate     = date('Y-m-t', strtotime($startDate));
$daysInMonth = (int) date('t', strtotime($startDate));
$year        = (int) date('Y', strtotime($startDate));
$month       = (int) date('m', strtotime($startDate));
$monthName   = date('F Y', strtotime($startDate));

header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename="Attendance_' . $monthStr . '.xlsx"');
header('Cache-Control: max-age=0');

/* ================= EMPLOYEES ================= */
$sql = "
    SELECT e.employee_number, e.first_name, e.last_name, e.position, d.name AS dept_name
    FROM employees e
    LEFT JOIN departments d ON e.department_id = d.id
    WHERE 1=1
";

$types  = '';
$params = [];

if ($deptFilter !== '') {
    $sql .= " AND d.name = ?";
    $types .= 's';
    $params[] = $deptFilter;
}

if ($search !== '') {
    $sql .= " AND (e.first_name LIKE ? OR e.last_name LIKE ?)";
    $types .= 'ss';
    $params[] = '%' . $search . '%';
    $params[] = '%' . $search . '%';
}

$sql .= " ORDER BY e.last_name ASC";

$stmt = $conn->prepare($sql);
if ($types !== '') {
    $stmt->bind_param($types, ...$params);
}
$stmt->execute();
$employees = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
$stmt->close();

if (!$employees) {
    exit;
}

/* ================= ATTENDANCE ================= */
$empNums = array_column($employees, 'employee_number');
$placeholders = implode(',', array_fill(0, count($empNums), '?'));

$attSql = "
    SELECT employee_number, DAY(date) AS day, status
    FROM attendance_records
    WHERE date BETWEEN ? AND ?
      AND employee_number IN ($placeholders)
";

$attTypes  = str_repeat('s', count($empNums) + 2);
$attParams = array_merge([$startDate, $endDate], $empNums);

$attStmt = $conn->prepare($attSql);
$attStmt->bind_param($attTypes, ...$attParams);
$attStmt->execute();
$rawLogs = $attStmt->get_result()->fetch_all(MYSQLI_ASSOC);
$attStmt->close();

$logs = [];
foreach ($rawLogs as $log) {
    $logs[$log['employee_number']][$log['day']] = $log;
}

/* ================= SPREADSHEET ================= */
$spreadsheet = new Spreadsheet();
$sheet = $spreadsheet->getActiveSheet();

$lastCol = Coordinate::stringFromColumnIndex($daysInMonth + 3);

/* Title */
$sheet->setCellValue('A1', "Attendance Report - $monthName");
$sheet->mergeCells("A1:$lastCol" . "1");
$sheet->getStyle('A1')->getFont()->setBold(true)->setSize(16);
$sheet->getStyle('A1')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);

/* Header */
$sheet->setCellValue('A3', 'Employee');
$sheet->setCellValue('B3', 'Position');
$sheet->setCellValue('C3', 'Department');

$colIndex = 4;
for ($d = 1; $d <= $daysInMonth; $d++) {
    $col = Coordinate::stringFromColumnIndex($colIndex++);
    $sheet->getCell($col . '3')->setValue($d);
}

$headerRange = "A3:$lastCol" . "3";
$sheet->getStyle($headerRange)->getFont()->setBold(true);
$sheet->getStyle($headerRange)->getFill()
    ->setFillType(Fill::FILL_SOLID)
    ->getStartColor()->setARGB('FFE2E2E2');
$sheet->getStyle($headerRange)->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);
$sheet->getStyle($headerRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

/* Data */
$row = 4;
foreach ($employees as $emp) {
    $sheet->setCellValue("A$row", $emp['first_name'] . ' ' . $emp['last_name']);
    $sheet->setCellValue("B$row", $emp['position']);
    $sheet->setCellValue("C$row", $emp['dept_name'] ?? '-');

    $colIndex = 4;
    for ($day = 1; $day <= $daysInMonth; $day++) {
        $dateObj = new DateTime(sprintf('%04d-%02d-%02d', $year, $month, $day));
        $dow = $dateObj->format('w');

        $status = '-';
        if (isset($logs[$emp['employee_number']][$day])) {
            $status = $logs[$emp['employee_number']][$day]['status'];
        } elseif ($dow == 0 || $dow == 6) {
            $status = 'W';
        }

        $col = Coordinate::stringFromColumnIndex($colIndex++);
        $sheet->getCell($col . $row)->setValue($status);
    }
    $row++;
}

/* Autosize */
for ($i = 1; $i <= $daysInMonth + 3; $i++) {
    $sheet->getColumnDimension(
        Coordinate::stringFromColumnIndex($i)
    )->setAutoSize(true);
}

/* Borders */
$dataRange = "A3:$lastCol" . ($row - 1);
$sheet->getStyle($dataRange)->getBorders()->getAllBorders()->setBorderStyle(Border::BORDER_THIN);

/* Output */
if (ob_get_length()) {
    ob_end_clean();
}

$writer = new Xlsx($spreadsheet);
$writer->save('php://output');
exit;