<?php declare(strict_types=1); ?>
<div class="page-heading d-flex justify-content-between align-items-end">
  <div>
    <h1>Data Dosen</h1>
    <p>Daftar dosen yang tercatat pada sistem akademik.</p>
  </div>
  <a class="btn btn-primary" href="index.php?route=lecturers&action=create">Tambah Dosen</a>
</div>

<div class="content-panel">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Nama</th>
          <th>NIDN</th>
          <th>No. Telepon</th>
          <th>Tanggal Bergabung</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $hasRows = false; while ($row = $lecturers->fetch_assoc()): $hasRows = true; ?>
          <tr>
            <td class="record-id">#<?= htmlspecialchars((string)$row['id']) ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['nidn']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars(date('d M Y', strtotime($row['join_date']))) ?></td>
            <td class="text-end">
              <a class="btn btn-sm btn-edit" href="index.php?route=lecturers&action=edit&id=<?= htmlspecialchars((string)$row['id']) ?>">Ubah</a>
              <form method="post" action="index.php?route=lecturers&action=delete" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus dosen \'<?= addslashes(htmlspecialchars($row['name'])) ?>\'?\n\nPERINGATAN: Menghapus dosen ini juga akan menghapus seluruh data penugasan pengampu terkait secara otomatis (CASCADE). Tindakan ini tidak dapat dibatalkan.');">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string)$row['id']) ?>">
                <button type="submit" class="btn btn-sm btn-remove">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if (!$hasRows): ?>
          <tr>
            <td colspan="6" class="text-center py-4 empty-state">Belum ada data dosen. Klik tombol <strong>Tambah Dosen</strong> untuk menambahkan data baru.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
