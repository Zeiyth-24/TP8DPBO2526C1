<?php

declare(strict_types=1);

require_once __DIR__ . '/../config/Database.php';

class TeachingAssignment
{
    private mysqli $db;

    public function __construct()
    {
        $this->db = Database::connection();
    }

    public function all(): mysqli_result
    {
        $query = 'SELECT a.*, l.name AS lecturer_name, l.nidn AS lecturer_nidn,
                         c.code AS course_code, c.name AS course_name, c.credits AS course_credits
                  FROM teaching_assignments a
                  JOIN lecturers l ON a.lecturer_id = l.id
                  JOIN courses c ON a.course_id = c.id
                  ORDER BY a.id DESC';

        return $this->db->query($query);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare('SELECT * FROM teaching_assignments WHERE id = ?');
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result ?: null;
    }

    public function isDuplicate(int $lecturerId, int $courseId, string $semester, string $academicYear, ?int $exceptId = null): bool
    {
        if ($exceptId !== null) {
            $stmt = $this->db->prepare(
                'SELECT id FROM teaching_assignments 
                 WHERE lecturer_id = ? AND course_id = ? AND semester = ? AND academic_year = ? AND id != ? 
                 LIMIT 1'
            );
            $stmt->bind_param('iissi', $lecturerId, $courseId, $semester, $academicYear, $exceptId);
        } else {
            $stmt = $this->db->prepare(
                'SELECT id FROM teaching_assignments 
                 WHERE lecturer_id = ? AND course_id = ? AND semester = ? AND academic_year = ? 
                 LIMIT 1'
            );
            $stmt->bind_param('iiss', $lecturerId, $courseId, $semester, $academicYear);
        }

        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        $stmt->close();

        return $result !== null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare('INSERT INTO teaching_assignments (lecturer_id, course_id, semester, academic_year) VALUES (?, ?, ?, ?)');
        $stmt->bind_param('iiss', $data['lecturer_id'], $data['course_id'], $data['semester'], $data['academic_year']);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare('UPDATE teaching_assignments SET lecturer_id = ?, course_id = ?, semester = ?, academic_year = ? WHERE id = ?');
        $stmt->bind_param('iissi', $data['lecturer_id'], $data['course_id'], $data['semester'], $data['academic_year'], $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare('DELETE FROM teaching_assignments WHERE id = ?');
        $stmt->bind_param('i', $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }
}
