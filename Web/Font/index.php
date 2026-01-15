<?php include 'Log/config.php'; ?>



<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Trang chủ</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <link rel="stylesheet" href="index.css">
  <!-- Swiper CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css"/>

</head>
<style>
  .carousel-item img {
    height: 550px;
    width: 100%;
    object-fit: cover;
  }
  /* Chỉnh kích thước modal dialog */
  .custom-modal-width {
    max-width: 1000px;
    width: 100%;
  }

  /* Chiều cao và style modal-content */
  .custom-modal-content {
    height: 600px;
    overflow: hidden;
  }

  /* Layout 2 cột liền mạch */
  .modal-body {
    height: 100%;
    display: flex;
    padding: 0 !important;
  }

  /* Cột ảnh cố định 45% chiều rộng, full chiều cao */
  .modal-image {
    width: 45%;
    background-color: #fff;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  /* Ảnh auto vừa chiều cao, không vỡ tỉ lệ */
  .modal-image img {
    max-height: 90%;
    object-fit: contain;
  }

  /* Cột nội dung 55% còn lại, full chiều cao */
  .modal-info {
    width: 55%;
    background-color: #fff;
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    padding: 1.5rem;
  }
</style>

<body>

<?php include 'header.php'; ?>

<!-- Banner -->
<section class="container my-4">
  <div id="bannerCarousel" class="carousel slide rounded-3 overflow-hidden" data-bs-ride="carousel" data-bs-interval="4500">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="banner.jpg" class="d-block w-100" alt="Banner 1">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h2>Chào mừng bạn đến với cửa hàng của chúng tôi!</h2>
          <p>Chúng tôi chuyên cung cấp các sản phẩm chất lượng cao với dịch vụ tận tâm.</p>
          <a href="introduction.php" class="btn btn-warning">Xem thêm về chúng tôi →</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="banner2.jpg" class="d-block w-100" alt="Banner 2">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h2>Ưu đãi hấp dẫn mỗi ngày!</h2>
          <p>Khám phá các sản phẩm giảm giá đặc biệt cho bạn.</p>
          <a href="product.php" class="btn btn-warning">Xem ngay →</a>
        </div>
      </div>
      <div class="carousel-item">
        <img src="banner3.jpg" class="d-block w-100" alt="Banner 3">
        <div class="carousel-caption d-none d-md-block bg-dark bg-opacity-50 rounded p-3">
          <h2>Sản phẩm mới cập nhật</h2>
          <p>Luôn cập nhật những xu hướng mới nhất.</p>
          <a href="product.php" class="btn btn-warning">Xem thêm →</a>
        </div>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#bannerCarousel" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#bannerCarousel" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
</section>


<?php include 'categories.php'; ?>

<!-- SẢN PHẨM NỔI BẬT -->
<section class="container my-5">
  <h2 class="mb-3">Sản phẩm nổi bật</h2>
  <div class="swiper productSwiper">
    <div class="swiper-wrapper">
      <?php
        include 'Log/config.php';

        // Lấy sản phẩm nổi bật từ database
        $featuredProductsQuery = "SELECT p.ProductID, p.ProductName, p.Price, p.Quantity, p.Image FROM products p  WHERE p.is_featured = 1 ORDER BY p.ProductID DESC ";
        $featuredResult = $conn->query($featuredProductsQuery);

        $products = [];
        while ($row = $featuredResult->fetch_assoc()) {
          $products[] = $row;
        }

        // Chia sản phẩm thành các slide 
        $productSlides = array_chunk($products, 6);

        foreach ($productSlides as $slide):
      ?>
        <div class="swiper-slide">
          <div class="row g-3">
            <?php foreach ($slide as $p): 
              $img_path = !empty($p['Image']) ? 'img/' . htmlspecialchars($p['Image']) : 'img/default-product.jpg';
            ?>
              <div class="col-12 col-md-6 col-lg-4 col-xl-2">
                <a href="items_detail.php?id=<?= urlencode($p['ProductID']) ?>" class="text-decoration-none text-dark">
                  <div class="card h-100 product-card"
                      data-name="<?= htmlspecialchars($p['ProductName']) ?>"
                      data-img="<?= htmlspecialchars($img_path) ?>"
                      data-price="<?= number_format($p['Price'], 0, ',', '.') ?>₫"
                      data-quantity="<?= isset($p['Quantity']) ? htmlspecialchars($p['Quantity']) : '' ?>"
                      style="cursor:pointer;">
                    <img src="<?= $img_path ?>" class="card-img-top" alt="<?= htmlspecialchars($p['ProductName']) ?>" style="height: 150px; object-fit: cover;">
                    <div class="card-body">
                      <h5 class="card-title"><?= htmlspecialchars($p['ProductName']) ?></h5>
                      <p class="card-text text-danger fw-bold"><?= number_format($p['Price'], 0, ',', '.') ?>₫</p>
                      <?php if (isset($p['Quantity'])): ?>
                        <p class="card-text text-primary mb-1">
                          Còn lại: <span class="product-quantity"><?= htmlspecialchars($p['Quantity']) ?></span>
                        </p>
                      <?php endif; ?>
                      <span class="btn btn-sm btn-primary mt-auto w-100"><i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng</span>
                    </div>
                  </div>
                </a>
              </div>
            <?php endforeach; ?>
          </div>
        </div>
      <?php endforeach; ?>
    </div>
    <div class="swiper-button-prev"></div>
    <div class="swiper-button-next"></div>
  </div>
  <div class="text-end mt-3">
    <a href="show_product.php" class="text-decoration-none fw-semibold">Xem thêm tại đây →</a>
  </div>
</section>




<!-- PHẦN SẢN PHẨM GIẢM GIÁ -->
<section class="container my-5">
  <h2 class="mb-3">Sản phẩm giảm giá</h2>
  <div class="d-flex overflow-auto gap-3 pb-2" style="scroll-snap-type: x mandatory;">
    <?php
      $sql = "SELECT * FROM products WHERE is_sale = 1 AND discount_percent > 0";
      $result = mysqli_query($conn, $sql);

      if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
          $original_price = $row['Price'];
          $discount_percent = $row['discount_percent'];
          $discounted_price = $original_price * (1 - $discount_percent / 100);

          $formatted_original = number_format($original_price, 0, ',', '.') . '₫';
          $formatted_discounted = number_format($discounted_price, 0, ',', '.') . '₫';

          $img_path = !empty($row['Image']) ? 'img/' . $row['Image'] : 'img/default-product.jpg';
    ?>
      <a href="items_detail.php?id=<?= urlencode($row['ProductID']) ?>" class="text-decoration-none text-dark" style="display: block;">
        <div class="card h-100 d-flex flex-column border-danger sale-product-card"
          style="width: 200px; min-width: 200px; max-width: 200px; min-height: 340px; scroll-snap-align: start; cursor:pointer;">
          <div class="position-relative">
            <img src="<?= htmlspecialchars($img_path) ?>" class="card-img-top" alt="<?= htmlspecialchars($row['ProductName']) ?>" style="height: 150px; object-fit: cover;">
            <span class="position-absolute top-0 end-0 bg-danger text-white p-1 rounded-start fw-bold" style="font-size: 0.8rem;">
              -<?= $discount_percent ?>%
            </span>
          </div>
          <div class="card-body d-flex flex-column flex-grow-1">
            <h5 class="card-title"><?= htmlspecialchars($row['ProductName']) ?></h5>
            <p class="card-text text-danger fw-bold mb-1">
              <?= $formatted_discounted ?>
              <del class="ms-2" style="font-size: 0.75rem; color: #999;"><?= $formatted_original ?></del>
            </p>
            <p class="card-text text-primary mb-1">
              Còn lại: <span class="product-quantity"><?= htmlspecialchars($row['Quantity']) ?></span>
            </p>
            <span class="btn btn-sm btn-danger mt-auto add-to-cart-btn" style="pointer-events: none;">Thêm vào giỏ hàng</span>
          </div>
        </div>
      </a>
    <?php
        }
      } else {
        echo "<p>Không có sản phẩm giảm giá.</p>";
      }
      mysqli_close($conn);
    ?>
  </div>

  <div class="text-end mt-3">
    <a href="show_product.php?filter=sale" class="text-decoration-none fw-semibold text-danger">Xem thêm tại đây →</a>
  </div>
</section>




<!-- GỢI Ý CHO BẠN -->
<?php
include 'Log/config.php'; 

$sql = "SELECT * FROM products ORDER BY ProductID DESC";
$result = $conn->query($sql);
?>

<section class="container my-5">
  <h2 class="mb-3">Gợi ý cho bạn</h2>
  <div class="row g-3">
    <?php while ($p = $result->fetch_assoc()): ?>
      <div class="col-12 col-sm-6 col-md-4 col-lg-3 col-xl-2">
        <a href="items_detail.php?id=<?= urlencode($p['ProductID']) ?>" class="text-decoration-none text-dark">
          <div class="card h-100">
            <img src="img/<?= htmlspecialchars($p['Image']) ?>" class="card-img-top" alt="<?= htmlspecialchars($p['ProductName']) ?>" style="height: 150px; object-fit: cover;">
            <div class="card-body">
              <h5 class="card-title"><?= htmlspecialchars($p['ProductName']) ?></h5>
              <p class="card-text text-danger fw-bold"><?= number_format($p['Price']) ?> đ</p>
              <span class="btn btn-sm btn-primary">Thêm vào giỏ hàng</span>
            </div>
          </div>
        </a>
      </div>
    <?php endwhile; ?>
  </div>
</section>





<!-- REVIEW -->
<section class="bg-dark text-white py-5">
  <div class="container position-relative">
    <h2 class="text-center mb-4">Khách hàng nói gì về chúng tôi</h2>
    <div id="reviewCarousel" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner text-center">
        <div class="carousel-item active">
          <p class="lead">"Sản phẩm rất chất lượng, giao hàng nhanh và đóng gói cẩn thận."</p>
          <h5 class="mt-3">Nguyễn Văn A</h5><small>TP. Hồ Chí Minh</small>
        </div>
        <div class="carousel-item">
          <p class="lead">"Tôi rất hài lòng về dịch vụ chăm sóc khách hàng của cửa hàng."</p>
          <h5 class="mt-3">Trần Thị B</h5><small>Hà Nội</small>
        </div>
        <div class="carousel-item">
          <p class="lead">"Giá cả hợp lý, sản phẩm đúng mô tả. Sẽ ủng hộ tiếp."</p>
          <h5 class="mt-3">Lê Văn C</h5><small>Đà Nẵng</small>
        </div>
      </div>

      <button class="carousel-control-prev" type="button" data-bs-target="#reviewCarousel" data-bs-slide="prev">
        <span class="carousel-control-prev-icon bg-secondary rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Trước</span>
      </button>

      <button class="carousel-control-next" type="button" data-bs-target="#reviewCarousel" data-bs-slide="next">
        <span class="carousel-control-next-icon bg-secondary rounded-circle p-3" aria-hidden="true"></span>
        <span class="visually-hidden">Tiếp</span>
      </button>
    </div>
  </div>

</section>

<!-- TIN TỨC -->
<section class="container my-5">
  <h2 class="mb-4">Tin tức mới</h2>
  <div class="list-group">
    <a href="news.php#Quoc-khanh" class="list-group-item list-group-item-action">
      <h5 class="mb-1">Khuyến mãi lớn dịp lễ 2/9</h5>
      <p class="mb-1">Cửa hàng giảm giá đến 50% tất cả các mặt hàng...</p>
    </a>
    <a href="news.php#SP-moi" class="list-group-item list-group-item-action">
      <h5 class="mb-1">Sản phẩm mới vừa ra mắt</h5>
      <p class="mb-1">Giới thiệu dòng sản phẩm thông minh, tiện ích...</p>
    </a>
  </div>
</section>


<?php include 'footer.php'; ?>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.js"></script>
<script>
  var swiper = new Swiper('.categorySwiper', {
    navigation: {
      nextEl: '.swiper-button-next',
      prevEl: '.swiper-button-prev',
    },
  });
</script>

<script>
  var swiperCategory = new Swiper('.categorySwiper', {
    navigation: {
      nextEl: '.categorySwiper .swiper-button-next',
      prevEl: '.categorySwiper .swiper-button-prev',
    }
  });

  var swiperProduct = new Swiper('.productSwiper', {
    navigation: {
      nextEl: '.productSwiper .swiper-button-next',
      prevEl: '.productSwiper .swiper-button-prev',
    }
  });
</script>


<script>
document.addEventListener('DOMContentLoaded', function () {
  document.querySelectorAll('.product-card, .sale-product-card').forEach(function (card) {
    card.addEventListener('click', function (e) {
      if (e.target.closest('.btn')) return;

      var name = card.getAttribute('data-name');
      var img = card.getAttribute('data-img');
      var price = card.getAttribute('data-price');
      var quantity = card.getAttribute('data-quantity');
      var oldPrice = card.getAttribute('data-old');

      document.getElementById('modalProductImg').src = img;
      document.getElementById('modalProductImg').alt = name;
      document.getElementById('modalProductName').textContent = name;
      document.getElementById('modalProductPrice').textContent = price;
      document.getElementById('modalProductQuantity').textContent = quantity ? 'Còn lại: ' + quantity : '';
      document.getElementById('modalProductOldPrice').textContent = oldPrice ? oldPrice : '';

      var modal = new bootstrap.Modal(document.getElementById('productModal'));
      modal.show();
    });
  });
});
</script>






</body>
</html>
