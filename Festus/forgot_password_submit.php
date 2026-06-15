<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    redirect('forgot-password.html');
}

$email = filter_var($_POST['email'] ?? '', FILTER_VALIDATE_EMAIL);
if ($email === false) {
    flash('A valid email address is required.', 'danger');
    redirect('forgot-password.html');
}

$db = get_db();
$stmt = $db->prepare('SELECT id FROM users WHERE email = ?');
$stmt->execute([$email]);
$user = $stmt->fetch();

if (!$user) {
    flash('If that address exists in our system, a password reset link has been generated.', 'success');
    redirect('login.html');
}

$token = generate_token(16);
$expires = date('Y-m-d H:i:s', time() + 3600);
$stmt = $db->prepare('INSERT INTO password_resets (user_id, reset_token, expires_at, used, created_at) VALUES (?, ?, ?, 0, NOW())');
$stmt->execute([$user['id'], $token, $expires]);

flash('Password reset link generated. Use the URL: reset_password.php?token=' . $token, 'success');
redirect('login.html');
