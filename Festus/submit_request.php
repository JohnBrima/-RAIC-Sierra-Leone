<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('submit-request.html');
}

$title = sanitize($_POST['title'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$ministry = sanitize($_POST['ministry'] ?? '');
$category = sanitize($_POST['category'] ?? '');

$errors = [];
if ($title === '') {
    $errors[] = 'Request title is required.';
}
if ($description === '') {
    $errors[] = 'Request description is required.';
}
if ($ministry === '') {
    $errors[] = 'A ministry must be selected.';
}
if ($category === '') {
    $errors[] = 'A request category must be selected.';
}

$attachmentPath = null;
if (!empty($_FILES['attachment']['name'])) {
    if ($_FILES['attachment']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'There was a problem uploading the attachment.';
    } else {
        $uploadDir = __DIR__ . '/uploads/requests';
        create_upload_dir($uploadDir);
        $extension = pathinfo($_FILES['attachment']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('request_', true) . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($_FILES['attachment']['tmp_name'], $destination)) {
            $errors[] = 'Unable to save the uploaded attachment.';
        } else {
            $attachmentPath = 'uploads/requests/' . $filename;
        }
    }
}

if ($errors) {
    flash(implode(' ', $errors), 'danger');
    redirect('submit-request.html');
}

$trackingNumber = sprintf('RAIC-%s-%04d', date('Y'), random_int(1000, 9999));
$db = get_db();
$stmt = $db->prepare('INSERT INTO information_requests (tracking_number, title, description, ministry, category, attachment_path, status, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
$stmt->execute([$trackingNumber, $title, $description, $ministry, $category, $attachmentPath, 'Pending']);

flash('Your request has been submitted. Use tracking number ' . $trackingNumber . ' to follow the request.', 'success');
redirect('track-request.php?tracking=' . urlencode($trackingNumber));
