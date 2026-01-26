<?php
session_start();
require_once "../config/db.php";

// Load PHPMailer
require_once "PHPMailer/PHPMailer.php";
require_once "PHPMailer/SMTP.php";
require_once "PHPMailer/Exception.php";

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

header("Content-Type: application/json");

// =======================================
// Step 0: Load environment variables (.env)
// =======================================
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false) {
            putenv($line);
        }
    }
}

// 1. Security Check
if (!isset($_SESSION['employee_number'])) {
    echo json_encode(["success" => false, "message" => "Unauthorized: Please log in."]);
    exit;
}

// 2. Input Handling
$subject = $_POST['subject'] ?? 'No Subject';
$header = $_POST['header'] ?? '';
$body = $_POST['content'] ?? ''; 
$closing = $_POST['closing'] ?? '';
$channels = $_POST['channels'] ?? '{}';
$target = $_POST['target_type'] ?? 'All';

$department_ids = isset($_POST['departments']) ? $_POST['departments'] : [];
$selected_emps = isset($_POST['selected_employees']) ? $_POST['selected_employees'] : [];

$created_by = $_SESSION['employee_number'];

// 3. Database Insert
$stmt = $conn->prepare("
    INSERT INTO announcements (subject, header, body, closing, created_by, delivery_channels, target_type)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssssss", $subject, $header, $body, $closing, $created_by, $channels, $target);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

$announcement_id = $stmt->insert_id;

// 4. Determine Recipients
$recipients = [];

if ($target === "All") {
    $q = $conn->query("SELECT employee_number, email FROM employees WHERE status = 'Active'");
    while ($r = $q->fetch_assoc()) $recipients[] = $r;

} elseif ($target === "Department") {
    foreach ($department_ids as $d) {
        $d = $conn->real_escape_string($d);
        $q = $conn->query("
            SELECT employee_number, email 
            FROM employees 
            WHERE department_id = '$d' AND status = 'Active'
        ");
        while ($r = $q->fetch_assoc()) $recipients[] = $r;

        $conn->query("
            INSERT INTO announcement_recipients (announcement_id, department_id)
            VALUES ($announcement_id, '$d')
        ");
    }

} elseif ($target === "Individual") {
    foreach ($selected_emps as $emp) {
        $emp = $conn->real_escape_string($emp);
        $q = $conn->query("
            SELECT employee_number, email 
            FROM employees 
            WHERE employee_number = '$emp'
        ");
        $r = $q->fetch_assoc();
        if ($r) $recipients[] = $r;
    }
}

// 5. Log Individual Recipients
foreach ($recipients as $rec) {
    $emp = $rec['employee_number'];
    $conn->query("
        INSERT IGNORE INTO announcement_recipients (announcement_id, employee_number)
        VALUES ($announcement_id, '$emp')
    ");
}

// 6. Send Email (if Email Channel is ON)
$channelObj = json_decode($channels);

if (isset($channelObj->email) && $channelObj->email == true) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = "tls";
        $mail->Port = getenv('SMTP_PORT');

        $mail->setFrom(getenv('SMTP_USER'), "HR Department");
        $mail->isHTML(true);
        $mail->Subject = $subject;

        foreach ($recipients as $rec) {
            if (!empty($rec['email'])) {
                $mail->addBCC($rec['email']);
            }
        }

        $mail->Body = "
            <div style='font-family: Arial, sans-serif; color: #333;'>
                <h3>{$header}</h3>
                <p>" . nl2br($body) . "</p>
                <br>
                <p>{$closing}</p>
            </div>
        ";

        if (count($recipients) > 0) {
            $mail->send();
        }

    } catch (Exception $e) {
        // Optional: log $mail->ErrorInfo
    }
}

echo json_encode(["success" => true]);
?>
