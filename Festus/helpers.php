<?php
if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

function redirect(string $path): void
{
    header('Location: ' . $path);
    exit;
}

function flash(string $message, string $type = 'success'): void
{
    $_SESSION['flash'] = ['message' => $message, 'type' => $type];
}

function get_flash(): ?array
{
    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

function sanitize(string $value): string
{
    return htmlspecialchars(trim($value), ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function generate_token(int $length = 32): string
{
    return bin2hex(random_bytes($length));
}

function create_upload_dir(string $path): void
{
    if (!is_dir($path)) {
        mkdir($path, 0755, true);
    }
}
