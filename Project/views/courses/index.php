<?php declare(strict_types=1); ?>
<div class="page-heading d-flex justify-content-between align-items-end">
  <div>
    <h1>Data Mata Kuliah</h1>
    <p>Kelola daftar mata kuliah yang tersedia pada kurikulum pembelajaran.</p>
  </div>
  <a class="btn btn-primary" href="index.php?route=courses&action=create">Tambah Mata Kuliah</a>
</div>

<div class="content-panel">
  <div class="table-responsive">
    <table class="table table-hover">
      <thead>
        <tr>
          <th>ID</th>
          <th>Kode</th>
          <th>Nama Mata Kuliah</th>
          <th>SKS</th>
          <th class="text-end">Aksi</th>
        </tr>
      </thead>
      <tbody>
        <?php $hasRows = false; while ($row = $courses->fetch_assoc()): $hasRows = true; ?>
          <tr>
            <td class="record-id">#<?= htmlspecialchars((string)$row['id']) ?></td>
            <td><strong><?= htmlspecialchars($row['code']) ?></strong></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars((string)$row['credits']) ?> SKS</td>
            <td class="text-end">
              <a class="btn btn-sm btn-edit" href="index.php?route=courses&action=edit&id=<?= htmlspecialchars((string)$row['id']) ?>">Ubah</a>
              <form method="post" action="index.php?route=courses&action=delete" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus mata kuliah \'<?= addslashes(htmlspecialchars($row['name'])) ?>\'?\n\nPERINGATAN: Menghapus mata kuliah ini juga akan menghapus seluruh data penugasan pengampu terkait secara otomatis (CASCADE). Tindakan ini tidak dapat dibatalkan.');">
                <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
                <input type="hidden" name="id" value="<?= htmlspecialchars((string)$row['id']) ?>">
                <button type="submit" class="btn btn-sm btn-remove">Hapus</button>
              </form>
            </td>
          </tr>
        <?php endwhile; ?>
        <?php if (!$hasRows): ?>
          <tr>
            <td colspan="5" class="text-center py-4 empty-state">Belum ada data mata kuliah. Klik tombol <strong>Tambah Mata Kuliah</strong> untuk menambahkan data baru.</td>
          </tr>
        <?php endif; ?>
      </tbody>
    </table>
  </div>
</div>
