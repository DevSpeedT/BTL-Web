<?php
if (session_status() === PHP_SESSION_NONE) session_start();

// Kiểm tra xem đang ở thư mục gốc hay thư mục con
$baseUrl = isset($isSubDirectory) ? '../' : '';
?>

<header class="bg-dark py-3 border-bottom">
  <div class="container d-flex justify-content-between align-items-center px-2">
    <div class="d-flex align-items-center gap-2">
      <img src="<?php echo $baseUrl; ?>logo.gif" alt="Logo" style="height: 50px; border-radius: 50%;">
      <span class="h5 mb-0 text-white">Uy tín - Chất lượng - Giá tốt</span>
    </div>
    <nav class="flex-grow-1 mx-4">
      <ul class="nav align-items-center justify-content-center">
        <li class="nav-item me-3">
          <form class="position-relative" role="search" action="<?php echo $baseUrl; ?>search.php" method="get">
            <input class="form-control form-control-sm pe-5 rounded-pill" type="search" name="q" placeholder="Tìm kiếm..." aria-label="Search" style="width: 250px;">
            <button type="submit" class="btn position-absolute top-50 end-0 translate-middle-y me-3 p-0" style="background: transparent; border: none; color: #6c757d; width: 24px; height: 24px;">
              <i class="bi bi-search"></i>
            </button>
          </form>
        </li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>index.php">Trang chủ</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>show_product.php">Sản phẩm</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>introduction.php">Giới thiệu</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>news.php">Tin tức</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>contact.php">Liên hệ</a></li>
        <li class="nav-item"><a class="nav-link text-white" href="<?php echo $baseUrl; ?>cart.php"><i class="bi bi-cart"></i></a></li>
        <!-- Tài khoản động -->
        <li class="nav-item dropdown">
          <a class="nav-link text-white" href="#" id="accountDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="bi bi-person"></i>
          </a>
          <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="accountDropdown">
            <?php if (isset($_SESSION['ID'])): ?>
              <?php if (isset($_SESSION['role']) && $_SESSION['role'] === 'admin'): ?>
                <li class="nav-item">
                  <a class="nav-link" href="manage_product.php">Quản lý danh sách</a>
                </li>
              <?php else: ?>
                <li class="nav-item">
                  <a class="nav-link" href="Log/profile.php">Thông tin tài khoản</a>
                </li>
              <?php endif; ?>
              <li class="nav-item">
                <a class="nav-link" href="Log/logout.php">Đăng xuất</a>
              </li>
            <?php else: ?>
              <li class="nav-item">
                <a class="nav-link" href="Log/login.php">Đăng nhập</a>
              </li>
              <li class="nav-item">
                <a class="nav-link" href="Log/Register.php">Đăng ký</a>
              </li>
            <?php endif; ?>
          </ul>
        </li>
      </ul>
    </nav>
  </div>
</header>
