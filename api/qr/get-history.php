<?php
// api/qr/get-history.php
session_start();
header('Content-Type: application/json');
require_once '../../config/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access. Please log in.']);
    exit;
}

$userId = $_SESSION['user_id'];

try {
    $stmt = $pdo->prepare("SELECT id, scan_type, content_type, qr_data, created_at FROM scan_history WHERE user_id = ? ORDER BY created_at DESC");
    $stmt->execute([$userId]);
    $history = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $history]);
} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Database error: ' . $e->getMessage()]);
}