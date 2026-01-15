<?php
include 'Log/config.php'; // Đảm bảo đúng đường dẫn

// Lấy id là chuỗi
$id = isset($_GET['id']) ? $_GET['id'] : '';
$product = null;

if ($id !== '') {
    $stmt = $conn->prepare("SELECT p.*, c.CateName FROM products p LEFT JOIN categories c ON p.CateID = c.CateID WHERE p.ProductID = ?");
    $stmt->bind_param("s", $id); // bind kiểu string
    $stmt->execute();
    $result = $stmt->get_result();
    $product = $result->fetch_assoc();
    $stmt->close();
}

// Đánh giá mẫu (nếu muốn)
$reviews_by_product = [
  'BEP019' => [
    ["name" => "Nguyễn Văn A", "rating" => 5, "comment" => "Sản phẩm tuyệt vời!", "date" => "2025-06-01"],
    ["name" => "Trần Thị B", "rating" => 4, "comment" => "Chất lượng tốt, giao hàng nhanh.", "date" => "2025-05-29"],
  ],
  'CLA008' => [
    ["name" => "Lê Văn C", "rating" => 3, "comment" => "Tạm ổn với mức giá này.", "date" => "2025-05-27"],
  ],
  // ... Thêm các ProductID khác nếu muốn
];
$reviews = $reviews_by_product[$id] ?? [];
?>
<!DOCTYPE html>
<html lang="vi">
<head>
  <meta charset="UTF-8">
  <title>Chi tiết sản phẩm</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container my-5">
  <?php if ($product): ?>
    <div class="card shadow p-4">
      <div class="row g-4">
        <div class="col-md-5">
          <img src="img/<?= htmlspecialchars($product['Image']) ?>" class="img-fluid rounded border" alt="<?= htmlspecialchars($product['ProductName']) ?>">
        </div>
        <div class="col-md-7">
          <h2><?= htmlspecialchars($product['ProductName']) ?></h2>
          <p class="text-danger fs-3 fw-bold"><?= number_format($product['Price']) ?> đ</p>
          <p>Còn lại: 
            <span class="badge bg-secondary"><?= htmlspecialchars($product['Quantity']) ?></span> 
          </p>
          <p><strong>Mô tả ngắn:</strong> <?= htmlspecialchars($product['Description']) ?></p>
          <a href="cart.php?action=add&id=<?= urlencode($product['ProductID']) ?>" class="btn btn-primary">Thêm vào giỏ hàng</a>
          <a href="index.php" class="btn btn-secondary ms-2">Quay lại</a>

        </div>
      </div>
    </div>

    <!-- Mô tả chi tiết sản phẩm -->
    <div class="card shadow mt-5">
      <div class="card-header bg-light">
        <h4 class="mb-0">📄 Mô tả sản phẩm</h4>
      </div>
      <div class="card-body">
        <p><?= nl2br(htmlspecialchars($product['Description'])) ?></p>
      </div>
    </div>

    <!-- Đánh giá sản phẩm -->
    <div class="card shadow mt-4">
      <div class="card-header bg-light">
        <h4 class="mb-0">⭐ Đánh giá</h4>
      </div>
      <div class="card-body">
        <?php if (!empty($reviews)): ?>
          <?php foreach ($reviews as $review): ?>
            <div class="mb-4 border-bottom pb-3">
              <div class="d-flex justify-content-between">
                <h6 class="mb-1"><?= htmlspecialchars($review['name']) ?></h6>
                <small class="text-muted"><?= htmlspecialchars($review['date']) ?></small>
              </div>
              <div class="text-warning mb-2">
                <?= str_repeat('<i class="bi bi-star-fill"></i>', $review['rating']) ?>
                <?= str_repeat('<i class="bi bi-star"></i>', 5 - $review['rating']) ?>
              </div>
              <p><?= htmlspecialchars($review['comment']) ?></p>
            </div>
          <?php endforeach; ?>
        <?php else: ?>
          <p>Chưa có đánh giá nào cho sản phẩm này.</p>
        <?php endif; ?>

        <!-- Form viết bình luận -->
        <hr>
        <h5 class="mt-4">📝 Viết bình luận của bạn</h5>
        <form method="post" class="mt-3">
          <div class="mb-3">
            <label for="comment" class="form-label">Bình luận</label>
            <textarea class="form-control" id="comment" name="comment" rows="4" required></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Gửi đánh giá</button>
        </form>
      </div>
    </div>
  <?php else: ?>
    <div class="alert alert-danger">Không tìm thấy sản phẩm!</div>
  <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?php include 'footer.php'; ?>
</body>
</html>
