<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Course
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): mysqli_result
    {
        return $this->db->query('SELECT * FROM courses ORDER BY id DESC');
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM courses WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM courses WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result !== null;
    }

    public function isCodeUnique(string $code, ?int $exceptId = null): bool
    {
        if ($exceptId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM courses WHERE code = ? AND id != ? LIMIT 1');
            $stmt->bind_param('si', $code, $exceptId);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM courses WHERE code = ? LIMIT 1');
            $stmt->bind_param('s', $code);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result === null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO courses (code, name, credits) VALUES (?, ?, ?)');
        $stmt->bind_param('ssi', $data['code'], $data['name'], $data['credits']);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE courses SET code = ?, name = ?, credits = ? WHERE id = ?');
        $stmt->bind_param('ssii', $data['code'], $data['name'], $data['credits'], $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM courses WHERE id = ?');
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
