<?php

declare(strict_types=1);

require_once __DIR__ . '/Controller.php';
require_once __DIR__ . '/../models/Lecturer.php';

class LecturerController extends Controller
{
    private Lecturer $lecturer;

    public function __construct()
    {
        $this->lecturer = new Lecturer();
    }

    public function index(): void
    {
        $this->view('lecturers/index', [
            'lecturers' => $this->lecturer->all(),
        ]);
    }

    public function create(): void
    {
        $this->view('lecturers/form', [
            'lecturer' => null,
            'title' => 'Tambah Dosen',
            'errors' => [],
        ]);
    }

    public function store(): void
    {
        $this->ensurePost();
        $payload = $this->payload();
        $errors = $this->validate($payload);

        if (!empty($errors)) {
            $this->view('lecturers/form', [
                'lecturer' => $payload,
                'title' => 'Tambah Dosen',
                'errors' => $errors,
            ]);
            return;
        }

        $this->lecturer->create($payload);
        $this->setFlash('success', 'Data dosen berhasil ditambahkan.');
        $this->redirect('lecturers');
    }

    public function edit(): void
    {
        $id = filter_var($_GET['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID dosen tidak valid.');
            $this->redirect('lecturers');
        }

        $lecturer = $this->lecturer->find($id);
        if (!$lecturer) {
            $this->setFlash('error', 'Data dosen tidak ditemukan.');
            $this->redirect('lecturers');
        }

        $this->view('lecturers/form', [
            'lecturer' => $lecturer,
            'title' => 'Ubah Dosen',
            'errors' => [],
        ]);
    }

    public function update(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0 || !$this->lecturer->exists($id)) {
            $this->setFlash('error', 'Data dosen tidak valid atau tidak ditemukan.');
            $this->redirect('lecturers');
        }

        $payload = $this->payload();
        $errors = $this->validate($payload, $id);

        if (!empty($errors)) {
            $payload['id'] = $id;
            $this->view('lecturers/form', [
                'lecturer' => $payload,
                'title' => 'Ubah Dosen',
                'errors' => $errors,
            ]);
            return;
        }

        $this->lecturer->update($id, $payload);
        $this->setFlash('success', 'Data dosen berhasil diperbarui.');
        $this->redirect('lecturers');
    }

    public function delete(): void
    {
        $this->ensurePost();
        $id = filter_var($_POST['id'] ?? null, FILTER_VALIDATE_INT);
        if ($id === false || $id === null || $id <= 0) {
            $this->setFlash('error', 'ID dosen tidak valid.');
            $this->redirect('lecturers');
        }

        if (!$this->lecturer->exists($id)) {
            $this->setFlash('error', 'Data dosen tidak ditemukan.');
            $this->redirect('lecturers');
        }

        $this->lecturer->delete($id);
        $this->setFlash('success', 'Data dosen berhasil dihapus.');
        $this->redirect('lecturers');
    }

    private function payload(): array
    {
        return [
            'name' => $this->input('name'),
            'nidn' => $this->input('nidn'),
            'phone' => $this->input('phone'),
            'join_date' => $this->input('join_date'),
        ];
    }

    private function validate(array $data, ?int $id = null): array
    {
        $errors = [];

        // Nama
        if ($data['name'] === '') {
            $errors['name'] = 'Nama dosen wajib diisi.';
        } elseif (mb_strlen($data['name']) > 100) {
            $errors['name'] = 'Nama dosen maksimal 100 karakter.';
        }

        // NIDN (tetap string agar nol di awal tidak hilang)
        if ($data['nidn'] === '') {
            $errors['nidn'] = 'NIDN wajib diisi.';
        } elseif (mb_strlen($data['nidn']) > 30) {
            $errors['nidn'] = 'NIDN maksimal 30 karakter.';
        } elseif (!preg_match('/^[A-Za-z0-9]+$/', $data['nidn'])) {
            $errors['nidn'] = 'NIDN hanya boleh berisi huruf dan angka.';
        } elseif (!$this->lecturer->isNidnUnique($data['nidn'], $id)) {
            $errors['nidn'] = 'NIDN sudah terdaftar pada dosen lain.';
        }

        // Telepon (tetap string)
        if ($data['phone'] === '') {
            $errors['phone'] = 'Nomor telepon wajib diisi.';
        } elseif (mb_strlen($data['phone']) > 20) {
            $errors['phone'] = 'Nomor telepon maksimal 20 karakter.';
        } elseif (!preg_match('/^[0-9+\-\s]+$/', $data['phone'])) {
            $errors['phone'] = 'Nomor telepon hanya boleh berisi angka dan tanda (+, -).';
        }

        // Tanggal Bergabung
        if ($data['join_date'] === '') {
            $errors['join_date'] = 'Tanggal bergabung wajib diisi.';
        } elseif (!preg_match('/^(\d{4})-(\d{2})-(\d{2})$/', $data['join_date'], $matches)) {
            $errors['join_date'] = 'Format tanggal harus YYYY-MM-DD.';
        } else {
            $year = (int)$matches[1];
            $month = (int)$matches[2];
            $day = (int)$matches[3];
            if (!checkdate($month, $day, $year)) {
                $errors['join_date'] = 'Tanggal bergabung tidak valid dalam kalender.';
            }
        }

        return $errors;
    }
}
