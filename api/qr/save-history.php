<?php
// api/qr/save-history.php
session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Guest user: history not saved.']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Invalid request method.']);
    exit;
}

$userId      = $_SESSION['user_id'];
$qrData      = trim($_POST['qr_data'] ?? '');
$contentType = trim($_POST['content_type'] ?? 'text');
$scanType    = trim($_POST['scan_type'] ?? 'scan');

if (empty($qrData)) {
    echo json_encode(['success' => false, 'message' => 'QR data is empty.']);
    exit;
}

try {
    $stmt = $pdo->prepare("INSERT INTO scan_history (user_id, scan_type, content_type, qr_data) VALUES (?, ?, ?, ?)");
    $stmt->execute([$userId, $scanType, $contentType, $qrData]);

    echo json_encode(['success' => true, 'message' => 'Scan saved to your history!']);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}