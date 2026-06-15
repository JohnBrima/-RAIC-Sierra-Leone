<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('register.html');
}

$name = sanitize($_POST['name'] ?? '');
$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$phone = sanitize($_POST['phone'] ?? '');
$institution = sanitize($_POST['institution'] ?? '');
$password = $_POST['password'] ?? '';
$confirmPassword = $_POST['confirm'] ?? '';
$role = in_array($_POST['role'] ?? 'citizen', ['citizen', 'publisher'], true) ? $_POST['role'] : 'citizen';

$errors = [];
if ($name === '') {
    $errors[] = 'Full name is required.';
}
if ($email === false) {
    $errors[] = 'A valid email address is required.';
}
if ($password === '' || strlen($password) < 8) {
    $errors[] = 'Password must be at least 8 characters long.';
}
if ($password !== $confirmPassword) {
    $errors[] = 'Password confirmation does not match.';
}

$db = get_db();
if ($email !== false) {
    $existing = $db->prepare('SELECT id FROM users WHERE email = ?');
    $existing->execute([$email]);
    if ($existing->fetch()) {
        $errors[] = 'An account with that email already exists.';
    }
}

if ($errors) {
    flash(implode(' ', $errors), 'danger');
    redirect('register.html');
}

$passwordHash = password_hash($password, PASSWORD_DEFAULT);


$stmt = $db->prepare('INSERT INTO users (full_name, email, phone, institution, password_hash, role, verification_token, is_verified, created_at, updated_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
// Verification removed: allow immediate login
$stmt->execute([$name, $email, $phone, $institution, $passwordHash, $role, null, 1]);


flash('Registration successful! You can now sign in.', 'success');
redirect('login.html');

