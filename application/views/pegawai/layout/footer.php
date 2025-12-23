    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>

<script>
document.querySelectorAll('#sidebarMenu .nav-link').forEach(link => {
  link.addEventListener('click', () => {
    const sidebar = document.getElementById('sidebarMenu');
    if (window.innerWidth < 768 && sidebar.classList.contains('show')) {
      sidebar.classList.remove('show');
    }
  });
});
</script>
