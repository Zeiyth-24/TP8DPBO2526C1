// Associate existing form labels with their controls.
document.querySelectorAll('.form-label').forEach((label, index) => {
  const field = label.parentElement.querySelector('input, select, textarea');
  if (!field) return;
  field.id ||= `field-${index}`;
  label.htmlFor = field.id;
});

document.querySelectorAll('.table').forEach(table => {
  table.querySelectorAll('thead th').forEach(th => th.scope = 'col');
  const body = table.tBodies[0];
  if (body && body.rows.length === 0) {
    const cell = body.insertRow().insertCell();
    cell.colSpan = table.tHead.rows[0].cells.length;
    cell.className = 'empty-state';
    cell.textContent = 'Belum ada data. Gunakan tombol tambah untuk membuat data pertama.';
  }
  const wrapper = table.closest('.table-responsive');
  if (wrapper) {
    wrapper.tabIndex = 0;
    wrapper.setAttribute('role', 'region');
    wrapper.setAttribute('aria-label', 'Daftar data; geser untuk melihat seluruh kolom');
  }
});
