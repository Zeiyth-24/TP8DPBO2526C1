<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class Lecturer
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): mysqli_result
    {
        return $this->db->query('SELECT * FROM lecturers ORDER BY id DESC');
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM lecturers WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result ?: null;
    }

    public function exists(int $id): bool
    {
        $stmt = $this->db->prepare('SELECT id FROM lecturers WHERE id = ? LIMIT 1');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result !== null;
    }

    public function isNidnUnique(string $nidn, ?int $exceptId = null): bool
    {
        if ($exceptId !== null) {
            $stmt = $this->db->prepare('SELECT id FROM lecturers WHERE nidn = ? AND id != ? LIMIT 1');
            $stmt->bind_param('si', $nidn, $exceptId);
        } else {
            $stmt = $this->db->prepare('SELECT id FROM lecturers WHERE nidn = ? LIMIT 1');
            $stmt->bind_param('s', $nidn);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result === null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO lecturers (name, nidn, phone, join_date) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('ssss', $data['name'], $data['nidn'], $data['phone'], $data['join_date']);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE lecturers SET name = ?, nidn = ?, phone = ?, join_date = ? WHERE id = ?');
        $stmt->bind_param('ssssi', $data['name'], $data['nidn'], $data['phone'], $data['join_date'], $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM lecturers WHERE id = ?');
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
