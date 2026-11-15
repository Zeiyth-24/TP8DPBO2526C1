<?php declare(strict_types=1); ?>
<div class="page-heading d-flex justify-content-between align-items-end">
  <div>
    <h1>Pengampu Mata Kuliah</h1>
    <p>Atur penugasan dosen untuk setiap mata kuliah dan periode akademik.</p>
  </div>
  <a class="btn btn-primary" href="index.php?route=assignments&action=create">Tambah Pengampu</a>
</div>

<div class="content-panel">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Dosen</th>
          <th>Mata Kuliah</th>
          <th>Semester</th>
          <th>Tahun Akademik</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $hasRows = false; while ($row = $assignments->fetch_assoc()): $hasRows = true; ?>
          <tr>
            <td class="record-id">#<?= htmlspecialchars((string)$row['id']) ?></td>
            <td>
              <strong><?= htmlspecialchars($row['lecturer_name']) ?></strong>
              <div class="subtle">NIDN: <?= htmlspecialchars($row['lecturer_nidn']) ?></div>
            </td>
            <td>
              <strong><?= htmlspecialchars($row['course_code']) ?></strong> — <?= htmlspecialchars($row['course_name']) ?>
              <div class="subtle"><?= htmlspecialchars((string)$row['course_credits']) ?> SKS</div>
            </td>
            <td>
              <span class="badge <?= $row['semester'] === 'Ganjil' ? 'bg-primary' : 'bg-success' ?>">
                <?= htmlspecialchars($row['semester']) ?>
              </span>
            </td>
            <td><?= htmlspecialchars($row['academic_year']) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-edit" href="index.php?route=assignments&action=edit&id=<?= htmlspecialchars((string)$row['id']) ?>">Ubah</a>
              <form method="post" action="index.php?route=assignments&action=delete" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus penugasan pengampu dosen \'<?= addslashes(htmlspecialchars($row['lecturer_name'])) ?>\' untuk mata kuliah \'<?= addslashes(htmlspecialchars($row['course_name'])) ?>\'?');">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string)$row['id']) ?>">
                <button type="submit" class="btn btn-sm btn-remove">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if (!$hasRows): ?>
          <tr>
            <td colspan="6" class="text-center py-4 empty-state">Belum ada data pengampu. Klik tombol <strong>Tambah Pengampu</strong> untuk menambahkan penugasan baru.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
