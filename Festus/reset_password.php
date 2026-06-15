<?php
require_once __DIR__ . '/helpers.php';
require_once __DIR__ . '/db.php';

$token = $_GET['token'] ?? '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $token = $_POST['token'] ?? '';
    $password = $_POST['password'] ?? '';
    $confirm = $_POST['confirm'] ?? '';

    if ($token === '' || $password === '' || $confirm === '') {
        flash('All fields are required.', 'danger');
        redirect('reset_password.php?token=' . urlencode($token));
    }

    if ($password !== $confirm) {
        flash('Password confirmation does not match.', 'danger');
        redirect('reset_password.php?token=' . urlencode($token));
    }

    if (strlen($password) < 8) {
        flash('Password must be at least 8 characters long.', 'danger');
        redirect('reset_password.php?token=' . urlencode($token));
    }

    $db = get_db();
    $stmt = $db->prepare('SELECT pr.id, pr.user_id FROM password_resets pr JOIN users u ON pr.user_id = u.id WHERE pr.reset_token = ? AND pr.expires_at >= NOW() AND pr.used = 0');
    $stmt->execute([$token]);
    $reset = $stmt->fetch();

    if (!$reset) {
        flash('The reset token is invalid or has expired.', 'danger');
        redirect('forgot-password.html');
    }

    $passwordHash = password_hash($password, PASSWORD_DEFAULT);
    $db->prepare('UPDATE users SET password_hash = ?, updated_at = NOW() WHERE id = ?')->execute([$passwordHash, $reset['user_id']]);
    $db->prepare('UPDATE password_resets SET used = 1 WHERE id = ?')->execute([$reset['id']]);

    flash('Your password has been reset successfully. Please log in.', 'success');
    redirect('login.html');
}

if ($token === '') {
    redirect('forgot-password.html');
}

$db = get_db();
$stmt = $db->prepare('SELECT id FROM password_resets WHERE reset_token = ? AND expires_at >= NOW() AND used = 0');
$stmt->execute([$token]);
$reset = $stmt->fetch();
if (!$reset) {
    flash('The reset token is invalid or has expired.', 'danger');
    redirect('forgot-password.html');
}
?>
<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Reset Password | RAIC Open Data Platform</title>
    <meta name="description" content="Reset your RAIC portal password.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="assets/ChatGPT%20Image%20Jun%2013%2C%202026%2C%2010_24_34%20PM.png">
    <link rel="stylesheet" href="assets/css/styles.css">
  </head>
  <body class="auth-layout">
    <div class="container">
      <div class="auth-card shadow-sm">
        <div class="d-flex align-items-center justify-content-between mb-4">
          <a href="index.html" class="d-flex align-items-center gap-3 text-decoration-none">
            <div class="brand-mark" aria-hidden="true">RAIC</div>
            <div>
              <strong class="d-block text-dark">RAIC Sierra Leone</strong>
              <small class="text-muted">Open Data Platform</small>
            </div>
          </a>
          <a href="login.html" class="btn btn-link">Back to login</a>
        </div>
        <h1 class="mb-3">Reset password</h1>
        <p class="text-muted mb-4">Choose a new password for your RAIC account.</p>
        <form action="reset_password.php" method="post">
          <input type="hidden" name="token" value="<?php echo htmlspecialchars($token, ENT_QUOTES, 'UTF-8'); ?>">
          <div class="mb-3"><label class="form-label" for="password">New password</label><input id="password" name="password" type="password" class="form-control" placeholder="New password"></div>
          <div class="mb-3"><label class="form-label" for="confirm">Confirm password</label><input id="confirm" name="confirm" type="password" class="form-control" placeholder="Confirm password"></div>
          <button class="btn btn-primary w-100" type="submit">Save password</button>
        </form>
      </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
  </body>
</html>
