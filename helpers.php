<?php
if (!function_exists('ensure_session_started')) {
    function ensure_session_started(): void {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }
}

if (!function_exists('current_user')) {
    function current_user(): ?array {
        ensure_session_started();
        return $_SESSION['usuario'] ?? null;
    }
}

if (!function_exists('is_admin_user')) {
    function is_admin_user(): bool {
        $user = current_user();
        return isset($user['rol']) && $user['rol'] === 'admin';
    }
}

if (!function_exists('redirect')) {
    function redirect(string $path): void {
        header('Location: ' . $path);
        exit;
    }
}

if (!function_exists('set_flash_message')) {
    function set_flash_message(string $type, string $message): void {
        ensure_session_started();
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }
}

if (!function_exists('get_flash_message')) {
    function get_flash_message(): ?array {
        ensure_session_started();
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }
}

if (!function_exists('require_login')) {
    function require_login(): void {
        if (!current_user()) {
            set_flash_message('warning', 'Debes iniciar sesión para acceder.');
            redirect('index.php');
        }
    }
}

if (!function_exists('require_admin')) {
    function require_admin(): void {
        require_login();
        if (!is_admin_user()) {
            set_flash_message('danger', 'No tienes permisos para acceder a esa sección.');
            redirect('pantalla principal.php');
        }
    }
}

if (!function_exists('generate_csrf_token')) {
    function generate_csrf_token(): string {
        ensure_session_started();
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }
}

if (!function_exists('csrf_input')) {
    function csrf_input(): string {
        $token = htmlspecialchars(generate_csrf_token(), ENT_QUOTES, 'UTF-8');
        return '<input type="hidden" name="csrf_token" value="' . $token . '">';
    }
}

if (!function_exists('verify_csrf_token')) {
    function verify_csrf_token(?string $token): bool {
        ensure_session_started();
        if (empty($_SESSION['csrf_token']) || empty($token)) {
            return false;
        }

        return hash_equals($_SESSION['csrf_token'], $token);
    }
}

if (!function_exists('sanitize_string')) {
    function sanitize_string(?string $value): string {
        return trim((string) $value);
    }
}
