<?php

declare(strict_types=1);

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/TeachingAssignment.php';
require_once __DIR__ . '/../models/Lecturer.php';
require_once __DIR__ . '/../models/Course.php';

class AssignmentController extends Controller
{
    private TeachingAssignment $assignment;
    private Lecturer $lecturer;
    private Course $course;

    public function __construct()
    {
        $this->assignment = new TeachingAssignment();
        $this->lecturer = new Lecturer();
        $this->course = new Course();
    }

    public function index(): void
    {
        $this->view('assignments/index', [
            'assignments' => $this->assignment->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('assignments/form', [
            'assignment' => null,
            'title' => 'Tambah Pengampu',
            'lecturers' => $this->lecturer->all(),
            'courses' => $this->course->all(),
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $this->ensurePost();
        $payload = $this->payload();
        $errors = $this->validate($payload);

        if (!empty($errors)) {
            $this->view('assignments/form', [
                'assignment' => $payload,
                'title' => 'Tambah Pengampu',
                'lecturers' => $this->lecturer->all(),
                'courses' => $this->course->all(),
                'errors' => $errors,
            ]);
            return;
        }

        $this->assignment->create([
            'lecturer_id' => (int)$payload['lecturer_id'],
            'course_id' => (int)$payload['course_id'],
            'semester' => $payload['semester'],
            'academic_year' => $payload['academic_year'],
        ]);
        $this->setFlash('success', 'Penugasan pengampu berhasil ditambahkan.');
        $this->redirect('assignments');
    }

    public function edit(): void
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID penugasan tidak valid.');
            $this->redirect('assignments');
        }

        $assignment = $this->assignment->find($id);
        if (!$assignment) {
            $this->setFlash('error', 'Data penugasan tidak ditemukan.');
            $this->redirect('assignments');
        }

        $this->view('assignments/form', [
            'assignment' => $assignment,
            'title' => 'Ubah Pengampu',
            'lecturers' => $this->lecturer->all(),
            'courses' => $this->course->all(),
            'errors' => [],
        ]);
    }

    public function update(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0 || !$this->assignment->find($id)) {
            $this->setFlash('error', 'Data penugasan tidak valid atau tidak ditemukan.');
            $this->redirect('assignments');
        }

        $payload = $this->payload();
        $errors = $this->validate($payload, $id);

        if (!empty($errors)) {
            $payload['id'] = $id;
            $this->view('assignments/form', [
                'assignment' => $payload,
                'title' => 'Ubah Pengampu',
                'lecturers' => $this->lecturer->all(),
                'courses' => $this->course->all(),
                'errors' => $errors,
            ]);
            return;
        }

        $this->assignment->update($id, [
            'lecturer_id' => (int)$payload['lecturer_id'],
            'course_id' => (int)$payload['course_id'],
            'semester' => $payload['semester'],
            'academic_year' => $payload['academic_year'],
        ]);
        $this->setFlash('success', 'Penugasan pengampu berhasil diperbarui.');
        $this->redirect('assignments');
    }

    public function delete(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID penugasan tidak valid.');
            $this->redirect('assignments');
        }

        if (!$this->assignment->find($id)) {
            $this->setFlash('error', 'Data penugasan tidak ditemukan.');
            $this->redirect('assignments');
        }

        $this->assignment->delete($id);
        $this->setFlash('success', 'Penugasan pengampu berhasil dihapus.');
        $this->redirect('assignments');
    }

    private function payload(): array
    {
        return [
            'lecturer_id' => $this->input('lecturer_id'),
            'course_id' => $this->input('course_id'),
            'semester' => $this->input('semester'),
            'academic_year' => $this->input('academic_year'),
        ];
    }

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];

        // Dosen
        $lecturerId = filter_var($data['lecturer_id'], FILTER_VALIDATE_INT);
        if ($data['lecturer_id'] === '' || $lecturerId === false || $lecturerId <= 0) {
            $errors['lecturer_id'] = 'Pilih dosen pengampu.';
        } elseif (!$this->lecturer->exists($lecturerId)) {
            $errors['lecturer_id'] = 'Dosen yang dipilih tidak ditemukan pada sistem.';
        }

        // Mata Kuliah
        $courseId = filter_var($data['course_id'], FILTER_VALIDATE_INT);
        if ($data['course_id'] === '' || $courseId === false || $courseId <= 0) {
            $errors['course_id'] = 'Pilih mata kuliah yang diampu.';
        } elseif (!$this->course->exists($courseId)) {
            $errors['course_id'] = 'Mata kuliah yang dipilih tidak ditemukan pada sistem.';
        }

        // Semester
        if (!in_array($data['semester'], ['Ganjil', 'Genap'], true)) {
            $errors['semester'] = 'Semester harus bernilai Ganjil atau Genap.';
        }

        // Tahun Akademik (YYYY/YYYY dengan year2 = year1 + 1)
        if ($data['academic_year'] === '') {
            $errors['academic_year'] = 'Tahun akademik wajib diisi.';
        } elseif (!preg_match('/^(\d{4})\/(\d{4})$/', $data['academic_year'], $matches)) {
            $errors['academic_year'] = 'Format tahun akademik harus YYYY/YYYY (contoh: 2026/2027).';
        } else {
            $year1 = (int)$matches[1];
            $year2 = (int)$matches[2];
            if ($year2 !== $year1 + 1) {
                $errors['academic_year'] = 'Tahun kedua harus persis satu tahun setelah tahun pertama (contoh: 2026/2027).';
            }
        }

        // Cek kombinasi penugasan unik
        if (empty($errors)) {
            if ($this->assignment->isDuplicate($lecturerId, $courseId, $data['semester'], $data['academic_year'], $id)) {
                $errors['general'] = 'Kombinasi penugasan dosen, mata kuliah, semester, dan tahun akademik ini sudah pernah ditambahkan.';
            }
        }

        return $errors;
    }
}
