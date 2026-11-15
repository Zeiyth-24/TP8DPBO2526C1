<?php

declare(strict_types=1);

class Database
{
    private static ?mysqli $connection = null;

    public static function connection(): mysqli
    {
        if (self::$connection === null) {
            $configFile = __DIR__ . '/database-config.php';
            if (!is_file($configFile)) {
                throw new RuntimeException('File konfigurasi database tidak ditemukan.');
            }

            $config = require $configFile;
            mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

            self::$connection = new mysqli(
                (string)($config['host'] ?? '127.0.0.1'),
                (string)($config['username'] ?? 'root'),
                (string)($config['password'] ?? ''),
                (string)($config['database'] ?? 'tp_mvc25'),
                (int)($config['port'] ?? 3306)
            );

            self::$connection->set_charset('utf8mb4');
        }

        return self::$connection;
    }
}
