<?php
// api/qr/upload-image.php
session_start();
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_FILES['qr_image'])) {
    echo json_encode(['success' => false, 'message' => 'No image file uploaded.']);
    exit;
}

$file = $_FILES['qr_image'];
$allowedTypes = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

if (!in_array($file['type'], $allowedTypes)) {
    echo json_encode(['success' => false, 'message' => 'Invalid file format. Please upload JPG, PNG, GIF, or WEBP.']);
    exit;
}

// Generate unique filename
$ext = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = 'qr_img_' . time() . '_' . rand(1000, 9999) . '.' . strtolower($ext);
$uploadDir = '../../uploads/';

// Ensure directory exists
if (!file_exists($uploadDir)) {
    mkdir($uploadDir, 0777, true);
}

$targetPath = $uploadDir . $filename;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Detect protocol and host dynamically
    $protocol = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https') ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Build root relative path dynamically (works locally and in Docker)
    $scriptDir = dirname($_SERVER['SCRIPT_NAME']); // e.g. /api/qr or /Scaner/api/qr
    $baseDir = preg_replace('#/api/qr$#', '', $scriptDir); // strip /api/qr
    
    $publicUrl = $protocol . $host . $baseDir . '/uploads/' . $filename;

    echo json_encode([
        'success' => true,
        'image_url' => $publicUrl,
        'message' => 'Image uploaded successfully!'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded image. Check folder permissions.']);
}