<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

$token = $_GET['token'] ?? '';
if (!$token) {
    redirect('login.html');
}

$db = get_db();
$stmt = $db->prepare('UPDATE users SET is_verified = 1, verification_token = NULL, updated_at = NOW() WHERE verification_token = ? AND is_verified = 0');
$stmt->execute([$token]);

if ($stmt->rowCount() === 0) {
    flash('Verification failed or token has expired.', 'danger');
} else {
    flash('Your email has been verified. You can now sign in.', 'success');
}

redirect('login.html');
