<!-- Sidebar -->
<ul class="navbar-nav bg-gradient-primary sidebar sidebar-dark accordion" id="accordionSidebar">
  <!-- Sidebar - Brand -->
  <a class="sidebar-brand d-flex align-items-center justify-content-center" href="#">
    <div class="sidebar-brand-text mx-3"> Penggajian </div>
  </a>
  <hr class="sidebar-divider my-0">
  <li class="nav-item">
    <a class="nav-link" href="<?php echo site_url('dashboard/hrd_dashboard'); ?>">
      <i class="fas fa-fw fa-tachometer-alt"></i>
      <span>Dashboard HRD</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo site_url('dashboard/hrd_report'); ?>">
      <i class="fas fa-fw fa-file-alt"></i>
      <span>Laporan Upah</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo site_url('ganti_password'); ?>">
      <i class="fas fa-fw fa-lock"></i>
      <span>Ubah Password</span></a>
  </li>
  <li class="nav-item">
    <a class="nav-link" href="<?php echo base_url('login/logout'); ?>">
      <i class="fas fa-fw fa-sign-out-alt"></i>
      <span>Logout</span></a>
  </li>
  <div class="text-center d-none d-md-inline">
    <button class="rounded-circle border-0" id="sidebarToggle"></button>
  </div>
</ul>
<!-- End of Sidebar -->