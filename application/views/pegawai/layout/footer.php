</main>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
  // Logic untuk menutup sidebar otomatis di mobile setelah klik link
  document.querySelectorAll('#sidebarMenu .nav-link').forEach(link => {
    link.addEventListener('click', () => {
      const sidebar = document.getElementById('sidebarMenu');
      const bsCollapse = new bootstrap.Collapse(sidebar, {
        toggle: false
      });
      if (window.innerWidth < 768) {
        bsCollapse.hide();
      }
    });
  });
</script>
</body>

</html>