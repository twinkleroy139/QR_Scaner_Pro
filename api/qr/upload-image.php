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
$filename = 'qr_img_' . time() . '_' . rand(1000, 9999) . '.' . $ext;
$targetPath = '../../uploads/' . $filename;

if (move_uploaded_file($file['tmp_name'], $targetPath)) {
    // Protocol detection for domain URL construction
    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';
    $host = $_SERVER['HTTP_HOST'];
    
    // Construct public link pointing directly to the saved image
    $publicUrl = $protocol . $host . '/Scaner/uploads/' . $filename;

    echo json_encode([
        'success' => true,
        'image_url' => $publicUrl,
        'message' => 'Image uploaded successfully!'
    ]);
} else {
    echo json_encode(['success' => false, 'message' => 'Failed to save uploaded image.']);
}