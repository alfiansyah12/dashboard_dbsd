<footer class="app-footer py-3">
  <div class="container-fluid px-3 small d-flex justify-content-between flex-wrap gap-2">
    <div>© <?= date('Y') ?> BTN Dashboard — Admin System</div>
    <div class="text-muted">Session: <span class="badge text-bg-success">Active</span></div>
  </div>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
  document.addEventListener('click', function (e) {
    const link = e.target.closest('.app-sidebar-offcanvas .nav-link');
    if (!link) return;

    const ocEl = document.getElementById('sidebarOffcanvas');
    const oc = bootstrap.Offcanvas.getInstance(ocEl);
    if (oc) oc.hide();
  });
</script>

</body>
</html>
