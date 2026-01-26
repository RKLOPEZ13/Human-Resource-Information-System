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

// Load .env variables
if (file_exists(__DIR__ . '/.env')) {
    foreach (file(__DIR__ . '/.env') as $line) {
        $line = trim($line);
        if ($line && strpos($line, '=') !== false && strpos($line, '#') !== 0) {
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

// 3. Database Insert (Existing 'announcements' table only)
$stmt = $conn->prepare("
    INSERT INTO announcements (subject, header, body, closing, created_by, delivery_channels, target_type)
    VALUES (?, ?, ?, ?, ?, ?, ?)
");
$stmt->bind_param("sssssss", $subject, $header, $body, $closing, $created_by, $channels, $target);

if (!$stmt->execute()) {
    echo json_encode(["success" => false, "message" => "Database error: " . $conn->error]);
    exit;
}

// 4. Fetch Recipient Emails directly from the 'employees' table
$recipients = [];

if ($target === "All") {
    $q = $conn->query("SELECT email FROM employees WHERE status = 'Active'");
    while ($r = $q->fetch_assoc()) if(!empty($r['email'])) $recipients[] = $r['email'];

} elseif ($target === "Department" && !empty($department_ids)) {
    $ids = implode("','", array_map([$conn, 'real_escape_string'], $department_ids));
    $q = $conn->query("SELECT email FROM employees WHERE department_id IN ('$ids') AND status = 'Active'");
    while ($r = $q->fetch_assoc()) if(!empty($r['email'])) $recipients[] = $r['email'];

} elseif ($target === "Individual" && !empty($selected_emps)) {
    $ids = implode("','", array_map([$conn, 'real_escape_string'], $selected_emps));
    $q = $conn->query("SELECT email FROM employees WHERE employee_number IN ('$ids')");
    while ($r = $q->fetch_assoc()) if(!empty($r['email'])) $recipients[] = $r['email'];
}

// 5. Send Email via PHPMailer
$channelObj = json_decode($channels);
if (isset($channelObj->email) && $channelObj->email == true && count($recipients) > 0) {
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = getenv('SMTP_HOST');
        $mail->SMTPAuth = true;
        $mail->Username = getenv('SMTP_USER');
        $mail->Password = getenv('SMTP_PASS');
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = getenv('SMTP_PORT');

        $mail->setFrom(getenv('SMTP_USER'), "HR Department");
        $mail->isHTML(true);
        $mail->Subject = $subject;

        // Use BCC to send to multiple people privately
        foreach ($recipients as $email) {
            $mail->addBCC($email);
        }

        $mail->Body = "
            <div style='font-family: sans-serif; color: #333;'>
                <h3 style='color: #4154f1;'>{$header}</h3>
                <p>" . nl2br(htmlspecialchars($body)) . "</p>
                <br>
                <p>{$closing}</p>
            </div>
        ";

        $mail->send();
    } catch (Exception $e) {
        // We still return true because the announcement was saved to the DB
    }
}

echo json_encode(["success" => true]);
?>