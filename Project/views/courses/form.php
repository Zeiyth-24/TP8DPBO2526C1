<?php
declare(strict_types=1);
$editing = !empty($course['id']);
?>
<div class="content-panel form-panel mx-auto">
  <h1><?= htmlspecialchars($title) ?></h1>
  <p><?= $editing ? 'Sesuaikan informasi mata kuliah yang sudah terdaftar.' : 'Tambahkan mata kuliah baru agar dapat diatur dosen pengampunya.' ?></p>

  <?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($errors['general']) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="index.php?route=courses&action=<?= $editing ? 'update' : 'store' ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?= htmlspecialchars((string)$course['id']) ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label" for="field-code">Kode Mata Kuliah <span class="text-danger">*</span></label>
      <input id="field-code" class="form-control<?= isset($errors['code']) ? ' is-invalid' : '' ?>" name="code" maxlength="20" value="<?= htmlspecialchars((string)($course['code'] ?? '')) ?>" placeholder="Contoh: IK100" required>
      <?php if (isset($errors['code'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['code']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-name">Nama Mata Kuliah <span class="text-danger">*</span></label>
      <input id="field-name" class="form-control<?= isset($errors['name']) ? ' is-invalid' : '' ?>" name="name" maxlength="100" value="<?= htmlspecialchars((string)($course['name'] ?? '')) ?>" placeholder="Contoh: Pemrograman Berorientasi Objek" required>
      <?php if (isset($errors['name'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['name']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-credits">SKS (Bobot Kredit) <span class="text-danger">*</span></label>
      <input id="field-credits" type="number" min="1" max="6" class="form-control<?= isset($errors['credits']) ? ' is-invalid' : '' ?>" name="credits" value="<?= htmlspecialchars((string)($course['credits'] ?? '')) ?>" placeholder="Rentang 1 - 6" required>
      <?php if (isset($errors['credits'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['credits']) ?></div>
      <?php endif; ?>
    </div>

    <div class="pt-2">
      <button type="submit" class="btn btn-primary">Simpan Data</button>
      <a class="btn btn-outline-secondary ms-1" href="index.php?route=courses">Batal</a>
    </div>
  </form>
</div>
