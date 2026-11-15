<?php
declare(strict_types=1);
$editing = !empty($assignment['id']);
?>
<div class="content-panel form-panel mx-auto">
  <h1><?= htmlspecialchars($title) ?></h1>
  <p><?= $editing ? 'Perbarui penugasan dosen pengampu, semester, atau tahun akademik.' : 'Tentukan dosen, mata kuliah, dan periode penugasan pengampu.' ?></p>

  <?php if (!empty($errors['general'])): ?>
    <div class="alert alert-danger" role="alert">
      <?= htmlspecialchars($errors['general']) ?>
    </div>
  <?php endif; ?>

  <form method="post" action="index.php?route=assignments&action=<?= $editing ? 'update' : 'store' ?>" novalidate>
    <input type="hidden" name="csrf_token" value="<?= htmlspecialchars($csrf_token) ?>">
    <?php if ($editing): ?>
      <input type="hidden" name="id" value="<?= htmlspecialchars((string)$assignment['id']) ?>">
    <?php endif; ?>

    <div class="mb-3">
      <label class="form-label" for="field-lecturer">Dosen Pengampu <span class="text-danger">*</span></label>
      <select id="field-lecturer" class="form-select<?= isset($errors['lecturer_id']) ? ' is-invalid' : '' ?>" name="lecturer_id" required>
        <option value="">-- Pilih Dosen --</option>
        <?php while ($l = $lecturers->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars((string)$l['id']) ?>" <?= ((string)($assignment['lecturer_id'] ?? '') === (string)$l['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($l['name'] . ' (NIDN: ' . $l['nidn'] . ')') ?>
          </option>
        <?php endwhile; ?>
      </select>
      <?php if (isset($errors['lecturer_id'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['lecturer_id']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-course">Mata Kuliah <span class="text-danger">*</span></label>
      <select id="field-course" class="form-select<?= isset($errors['course_id']) ? ' is-invalid' : '' ?>" name="course_id" required>
        <option value="">-- Pilih Mata Kuliah --</option>
        <?php while ($c = $courses->fetch_assoc()): ?>
          <option value="<?= htmlspecialchars((string)$c['id']) ?>" <?= ((string)($assignment['course_id'] ?? '') === (string)$c['id']) ? 'selected' : '' ?>>
            <?= htmlspecialchars($c['code'] . ' — ' . $c['name'] . ' (' . $c['credits'] . ' SKS)') ?>
          </option>
        <?php endwhile; ?>
      </select>
      <?php if (isset($errors['course_id'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['course_id']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-semester">Semester <span class="text-danger">*</span></label>
      <select id="field-semester" class="form-select<?= isset($errors['semester']) ? ' is-invalid' : '' ?>" name="semester" required>
        <option value="Ganjil" <?= (($assignment['semester'] ?? 'Ganjil') === 'Ganjil') ? 'selected' : '' ?>>Ganjil</option>
        <option value="Genap" <?= (($assignment['semester'] ?? '') === 'Genap') ? 'selected' : '' ?>>Genap</option>
      </select>
      <?php if (isset($errors['semester'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['semester']) ?></div>
      <?php endif; ?>
    </div>

    <div class="mb-3">
      <label class="form-label" for="field-academic-year">Tahun Akademik <span class="text-danger">*</span></label>
      <input id="field-academic-year" class="form-control<?= isset($errors['academic_year']) ? ' is-invalid' : '' ?>" name="academic_year" placeholder="Contoh: 2026/2027" pattern="[0-9]{4}/[0-9]{4}" maxlength="9" value="<?= htmlspecialchars((string)($assignment['academic_year'] ?? '')) ?>" required>
      <div class="form-text">Gunakan format 4 digit tahun / 4 digit tahun berikutnya (misal: 2026/2027).</div>
      <?php if (isset($errors['academic_year'])): ?>
        <div class="invalid-feedback"><?= htmlspecialchars($errors['academic_year']) ?></div>
      <?php endif; ?>
    </div>

    <div class="pt-2">
      <button type="submit" class="btn btn-primary">Simpan Data</button>
      <a class="btn btn-outline-secondary ms-1" href="index.php?route=assignments">Batal</a>
    </div>
  </form>
</div>
