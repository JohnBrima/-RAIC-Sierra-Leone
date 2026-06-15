<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('contact.html');
}

$name = sanitize($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$subject = sanitize($_POST['subject'] ?? '');
$message = sanitize($_POST['message'] ?? '');

$errors = [];
if ($name === '') {
    $errors[] = 'Your name is required.';
}
if ($email === false) {
    $errors[] = 'A valid email address is required.';
}
if ($subject === '') {
    $errors[] = 'Subject is required.';
}
if ($message === '') {
    $errors[] = 'Message is required.';
}

if ($errors) {
    flash(implode(' ', $errors), 'danger');
    redirect('contact.html');
}

$db = get_db();
$stmt = $db->prepare('INSERT INTO contact_messages (name, email, subject, message, created_at) VALUES (?, ?, ?, ?, NOW())');
$stmt->execute([$name, $email, $subject, $message]);

flash('Your message has been sent. RAIC will follow up soon.', 'success');
redirect('contact.html');
