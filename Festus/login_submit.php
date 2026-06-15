<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('login.html');
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
$password = $_POST['password'] ?? '';

if ($email === false || $password === '') {
    flash('Email and password are required.', 'danger');
    redirect('login.html');
}

$db = get_db();
$stmt = $db->prepare('SELECT id, full_name, email, password_hash, role, is_verified FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user || !password_verify($password, $user['password_hash'])) {
    flash('Invalid email or password.', 'danger');
    redirect('login.html');
}


















$_SESSION['user'] = [
    'id' => $user['id'],
    'full_name' => $user['full_name'],
    'email' => $user['email'],
    'role' => $user['role'],
];

switch ($user['role']) {
    case 'publisher':
        redirect('publisher-dashboard.html');
    case 'admin':
        redirect('admin-dashboard.html');
    default:
        redirect('citizen-dashboard.html');
}
