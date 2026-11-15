<?php

declare(strict_types=1);

abstract class Controller
{
    protected function view(string $name, array $data = []): void
    {
        $flash_success = $_SESSION['flash_success'] ?? null;
        $flash_error = $_SESSION['flash_error'] ?? null;
        unset($_SESSION['flash_success'], $_SESSION['flash_error']);

        $csrf_token = $this->csrfToken();

        extract($data);
        require __DIR__ . '/../views/layout/header.php';
        require __DIR__ . '/../views/' . $name . '.php';
        require __DIR__ . '/../views/layout/footer.php';
    }

    protected function redirect(string $route, array $params = []): void
    {
        $query = array_merge(['route' => $route], $params);
        header('Location: index.php?' . http_build_query($query));
        exit;
    }

    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash_' . $type] = $message;
    }

    protected function csrfToken(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        return $_SESSION['csrf_token'];
    }

    protected function validateCsrf(): bool
    {
        $token = (string)($_POST['csrf_token'] ?? '');
        $sessionToken = (string)($_SESSION['csrf_token'] ?? '');

        return $token !== '' && $sessionToken !== '' && hash_equals($sessionToken, $token);
    }

    protected function input(string $key): string
    {
        return trim((string)($_POST[$key] ?? ''));
    }

    protected function ensurePost(): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            http_response_code(405);
            exit('Metode HTTP tidak diizinkan. Gunakan POST.');
        }

        if (!$this->validateCsrf()) {
            http_response_code(403);
            exit('Permintaan ditolak: Token CSRF tidak valid atau sesi telah kedaluwarsa.');
        }
    }
}
