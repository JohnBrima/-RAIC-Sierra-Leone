<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('upload-dataset.html');
}

$title = sanitize($_POST['title'] ?? '');
$category = sanitize($_POST['category'] ?? '');
$ministry = sanitize($_POST['ministry'] ?? '');
$description = sanitize($_POST['description'] ?? '');
$tags = sanitize($_POST['tags'] ?? '');
$author = sanitize($_POST['author'] ?? '');
$year = (int)($_POST['year'] ?? 0);
$status = sanitize($_POST['status'] ?? 'Draft');

$errors = [];
if ($title === '') {
    $errors[] = 'Dataset title is required.';
}
if ($category === '') {
    $errors[] = 'Category is required.';
}
if ($ministry === '') {
    $errors[] = 'Ministry is required.';
}
if ($description === '') {
    $errors[] = 'Description is required.';
}

$filePath = null;
if (!empty($_FILES['datafile']['name'])) {
    if ($_FILES['datafile']['error'] !== UPLOAD_ERR_OK) {
        $errors[] = 'There was a problem uploading the dataset file.';
    } else {
        $uploadDir = __DIR__ . '/uploads/datasets';
        create_upload_dir($uploadDir);
        $extension = pathinfo($_FILES['datafile']['name'], PATHINFO_EXTENSION);
        $filename = uniqid('dataset_', true) . '.' . $extension;
        $destination = $uploadDir . '/' . $filename;
        if (!move_uploaded_file($_FILES['datafile']['tmp_name'], $destination)) {
            $errors[] = 'Unable to save the uploaded file.';
        } else {
            $filePath = 'uploads/datasets/' . $filename;
        }
    }
}

if ($errors) {
    flash(implode(' ', $errors), 'danger');
    redirect('upload-dataset.html');
}

$db = get_db();
$stmt = $db->prepare('INSERT INTO datasets (title, category, ministry, description, tags, author, year, status, file_path, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
$stmt->execute([$title, $category, $ministry, $description, $tags, $author, $year, $status, $filePath]);

flash('Dataset uploaded successfully and is pending review.', 'success');
redirect('upload-dataset.html');
