<?php
declare(strict_types=1);
$editing = !empty($lecturer['id']);
?>
<div class="content-panel form-panel mx-auto">
  <h1><?= htmlspecialchars($title) ?></h1>
  <p><?= $editing ? 'Perbarui informasi dosen yang sudah tersimpan.' : 'Isi data pokok dosen sebelum menyimpannya ke sistem.' ?></p>

  <?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($errors['general']) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="index.php?route=lecturers&action=<?= $editing ? 'update' : 'store' ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?= htmlspecialchars((string)$lecturer['id']) ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label" for="field-name">Nama Dosen <span class="text-danger">*</span></label>
      <input id="field-name" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" name="name" maxlength="100" value="<?= htmlspecialchars((string)($lecturer['name'] ?? '')) ?>" required>
      <?php if (isset($errors['name'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-nidn">NIDN <span class="text-danger">*</span></label>
      <input id="field-nidn" class="form-control<?= isset($errors['nidn']) ? ' is-invalid' : '' ?>" name="nidn" maxlength="30" value="<?= htmlspecialchars((string)($lecturer['nidn'] ?? '')) ?>" placeholder="Contoh: 0412345678" required>
      <?php if (isset($errors['nidn'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['nidn']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-phone">No. Telepon <span class="text-danger">*</span></label>
      <input id="field-phone" class="form-control<?= isset($errors['phone']) ? ' is-invalid' : '' ?>" name="phone" maxlength="20" value="<?= htmlspecialchars((string)($lecturer['phone'] ?? '')) ?>" placeholder="Contoh: 081234567890" required>
      <?php if (isset($errors['phone'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['phone']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-join-date">Tanggal Bergabung <span class="text-danger">*</span></label>
      <input id="field-join-date" type="date" class="form-control<?= isset($errors['join_date']) ? ' is-invalid' : '' ?>" name="join_date" value="<?= htmlspecialchars((string)($lecturer['join_date'] ?? '')) ?>" required>
      <?php if (isset($errors['join_date'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['join_date']) ?></div>
      <?php endif; ?>
    </div>

    <div class="pt-2">
      <button type="submit" class="btn btn-primary">Simpan Data</button>
      <a class="btn btn-outline-secondary ms-1" href="index.php?route=lecturers">Batal</a>
    </div>
  </form>
</div>
