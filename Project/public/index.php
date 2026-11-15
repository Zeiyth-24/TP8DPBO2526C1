<?php

declare(strict_types=1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../controllers/LecturerController.php';
require_once __DIR__ . '/../controllers/CourseController.php';
require_once __DIR__ . '/../controllers/AssignmentController.php';

$route = (string)($_GET['route'] ?? 'lecturers');
$action = (string)($_GET['action'] ?? 'index');

$controllers = [
    'lecturers' => LecturerController::class,
    'courses' => CourseController::class,
    'assignments' => AssignmentController::class,
];

if (!isset($controllers[$route])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan: Rute tidak dikenal.');
}

$actionMethods = [
    'index' => 'GET',
    'create' => 'GET',
    'store' => 'POST',
    'edit' => 'GET',
    'update' => 'POST',
    'delete' => 'POST',
];

if (!isset($actionMethods[$action])) {
    http_response_code(404);
    exit('Halaman tidak ditemukan: Aksi tidak dikenal.');
}

$requestMethod = $_SERVER['REQUEST_METHOD'] ?? 'GET';
$expectedMethod = $actionMethods[$action];

if ($requestMethod !== $expectedMethod) {
    // Jika GET ke action yang butuh POST (seperti delete atau store), jangan jalankan aksi.
    if ($expectedMethod === 'POST' && $requestMethod === 'GET') {
        $_SESSION['flash_error'] = 'Aksi penghapusan atau penyimpanan harus menggunakan metode POST yang aman.';
        header('Location: index.php?route=' . urlencode($route));
        exit;
    }

    http_response_code(405);
    exit('Metode HTTP ' . htmlspecialchars($requestMethod) . ' tidak diizinkan untuk aksi ini.');
}

try {
    $controllerClass = $controllers[$route];
    $controller = new $controllerClass();
    $controller->$action();
} catch (mysqli_sql_exception $error) {
    http_response_code(500);
    $errorCode = $error->getCode();
    $message = in_array($errorCode, [1062, 1452], true)
        ? 'Data duplikat atau data relasi yang dipilih tidak tersedia. Silakan periksa kembali isian Anda.'
        : 'Terjadi kendala pada koneksi basis data. Pastikan layanan MySQL aktif dan konfigurasi database sudah benar.';
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Terjadi Kendala</title><link href="assets/vendor/bootstrap.min.css" rel="stylesheet"><link href="assets/app.css" rel="stylesheet"></head><body class="p-4"><div class="container"><div class="alert alert-danger" role="alert"><h4 class="alert-heading">Kendala Sistem</h4><p>' . htmlspecialchars($message) . '</p><hr><a href="index.php?route=' . htmlspecialchars($route) . '" class="btn btn-primary">Kembali</a></div></div></body></html>';
} catch (Throwable $error) {
    http_response_code(500);
    echo '<!doctype html><html lang="id"><head><meta charset="utf-8"><title>Kesalahan Server</title><link href="assets/vendor/bootstrap.min.css" rel="stylesheet"><link href="assets/app.css" rel="stylesheet"></head><body class="p-4"><div class="container"><div class="alert alert-danger" role="alert"><h4 class="alert-heading">Kesalahan Server</h4><p>Terjadi kesalahan pada server saat memproses permintaan Anda.</p><hr><a href="index.php?route=' . htmlspecialchars($route) . '" class="btn btn-primary">Kembali</a></div></div></body></html>';
}
