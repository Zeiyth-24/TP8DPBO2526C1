<?php

declare(strict_types=1);

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Course.php';

class CourseController extends Controller
{
    private Course $course;

    public function __construct()
    {
        $this->course = new Course();
    }

    public function index(): void
    {
        $this->view('courses/index', [
            'courses' => $this->course->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('courses/form', [
            'course' => null,
            'title' => 'Tambah Mata Kuliah',
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $this->ensurePost();
        $payload = $this->payload();
        $errors = $this->validate($payload);

        if (!empty($errors)) {
            $this->view('courses/form', [
                'course' => $payload,
                'title' => 'Tambah Mata Kuliah',
                'errors' => $errors,
            ]);
            return;
        }

        $this->course->create([
            'code' => $payload['code'],
            'name' => $payload['name'],
            'credits' => (int)$payload['credits'],
        ]);
        $this->setFlash('success', 'Data mata kuliah berhasil ditambahkan.');
        $this->redirect('courses');
    }

    public function edit(): void
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID mata kuliah tidak valid.');
            $this->redirect('courses');
        }

        $course = $this->course->find($id);
        if (!$course) {
            $this->setFlash('error', 'Data mata kuliah tidak ditemukan.');
            $this->redirect('courses');
        }

        $this->view('courses/form', [
            'course' => $course,
            'title' => 'Ubah Mata Kuliah',
            'errors' => [],
        ]);
    }

    public function update(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0 || !$this->course->exists($id)) {
            $this->setFlash('error', 'Data mata kuliah tidak valid atau tidak ditemukan.');
            $this->redirect('courses');
        }

        $payload = $this->payload();
        $errors = $this->validate($payload, $id);

        if (!empty($errors)) {
            $payload['id'] = $id;
            $this->view('courses/form', [
                'course' => $payload,
                'title' => 'Ubah Mata Kuliah',
                'errors' => $errors,
            ]);
            return;
        }

        $this->course->update($id, [
            'code' => $payload['code'],
            'name' => $payload['name'],
            'credits' => (int)$payload['credits'],
        ]);
        $this->setFlash('success', 'Data mata kuliah berhasil diperbarui.');
        $this->redirect('courses');
    }

    public function delete(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID mata kuliah tidak valid.');
            $this->redirect('courses');
        }

        if (!$this->course->exists($id)) {
            $this->setFlash('error', 'Data mata kuliah tidak ditemukan.');
            $this->redirect('courses');
        }

        $this->course->delete($id);
        $this->setFlash('success', 'Data mata kuliah berhasil dihapus.');
        $this->redirect('courses');
    }

    private function payload(): array
    {
        return [
            'code' => $this->input('code'),
            'name' => $this->input('name'),
            'credits' => $this->input('credits'),
        ];
    }

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];

        // Kode Mata Kuliah
        if ($data['code'] === '') {
            $errors['code'] = 'Kode mata kuliah wajib diisi.';
        } elseif (mb_strlen($data['code']) > 20) {
            $errors['code'] = 'Kode mata kuliah maksimal 20 karakter.';
        } elseif (!preg_match('/^[A-Za-z0-9_\-]+$/', $data['code'])) {
            $errors['code'] = 'Kode mata kuliah hanya boleh berisi huruf, angka, strip (-), dan garis bawah (_).';
        } elseif (!$this->course->isCodeUnique($data['code'], $id)) {
            $errors['code'] = 'Kode mata kuliah sudah digunakan oleh mata kuliah lain.';
        }

        // Nama Mata Kuliah
        if ($data['name'] === '') {
            $errors['name'] = 'Nama mata kuliah wajib diisi.';
        } elseif (mb_strlen($data['name']) > 100) {
            $errors['name'] = 'Nama mata kuliah maksimal 100 karakter.';
        }

        // SKS (1 sampai 6)
        $credits = filter_var($data['credits'], FILTER_VALIDATE_INT, [
            'options' => ['min_range' => 1, 'max_range' => 6]
        ]);
        if ($data['credits'] === '') {
            $errors['credits'] = 'SKS wajib diisi.';
        } elseif ($credits === false) {
            $errors['credits'] = 'SKS harus berupa bilangan bulat antara 1 sampai 6.';
        }

        return $errors;
    }
}
