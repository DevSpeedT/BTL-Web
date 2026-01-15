<?php include 'Log/config.php'; ?>

<?php
// Lấy danh sách sản phẩm
$sql = "SELECT p.*, c.CateName FROM products p
        LEFT JOIN categories c ON p.CateID = c.CateID
        ORDER BY p.ProductID DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Danh sách sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="index.css">
    <style>
        .product-card {
            height: 100%;
            transition: transform 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 10px 20px rgba(0,0,0,0.1);
        }
        .card-img-container {
            height: 200px; /* Tăng chiều cao container ảnh */
            overflow: hidden;
            position: relative;
        }
        .card-img-top {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .product-title {
            height: 48px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }
        .product-description {
            height: 62px;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
        }
        .card-body {
            display: flex;
            flex-direction: column;
        }
        .mt-auto {
            margin-top: auto;
        }
        .quantity-text {
            color: #6c757d;
            font-size: 0.9rem;
            margin-top: 2px;
        }
        .low-stock {
            color: #dc3545;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <?php include 'header.php'; ?>
    
    <div class="container my-5">
        <h2 class="mb-4">Danh sách sản phẩm</h2>
        <div class="row">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="col-6 col-md-2 mb-4">
                    <div class="card product-card">
                        <a href="items_detail.php?id=<?php echo urlencode($row['ProductID']); ?>" class="text-decoration-none text-dark">
                            <div class="card-img-container position-relative">
                                <?php if ($row['Image']): ?>
                                    <img src="img/<?php echo htmlspecialchars($row['Image']); ?>" class="card-img-top" alt="<?php echo htmlspecialchars($row['ProductName']); ?>">
                                <?php else: ?>
                                    <img src="default-product.png" class="card-img-top" alt="No image">
                                <?php endif; ?>

                                <!-- Nếu là SALE thì hiện badge góc -->
                                <?php if (!empty($row['is_sale']) && $row['is_sale'] && !empty($row['discount_percent'])): ?>
                                    <span class="position-absolute top-0 start-0 m-2 badge bg-danger fs-6 px-3 py-2" style="z-index:2; box-shadow:0 2px 8px rgba(0,0,0,0.15);">
                                        Giảm <?php echo intval($row['discount_percent']); ?>%
                                    </span>
                                <?php endif; ?>
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title product-title"><?php echo htmlspecialchars($row['ProductName']); ?></h5>
                                
                                <!-- Giá sản phẩm: Nếu là sale thì hiện giá cũ gạch ngang và giá mới -->
                                <?php if (!empty($row['is_sale']) && $row['is_sale']): ?>
                                    <?php
                                        $discount = !empty($row['discount_percent']) ? intval($row['discount_percent']) : 0;
                                        $old_price = $row['Price'];
                                        $new_price = $discount ? round($old_price * (100 - $discount) / 100) : $old_price;
                                    ?>
                                    <div>
                                        <span class="text-muted text-decoration-line-through me-2"><?php echo number_format($old_price); ?> đ</span>
                                        <span class="text-danger fw-bold fs-5"><?php echo number_format($new_price); ?> đ</span>
                                        <!-- Không cần badge -30% ở đây nữa -->
                                    </div>
                                <?php else: ?>
                                    <p class="card-text text-danger fw-bold mb-0"><?php echo number_format($row['Price']); ?> đ</p>
                                <?php endif; ?>
                                <p class="quantity-text <?php echo ($row['Quantity'] < 5) ? 'low-stock' : ''; ?>">
                                    <i class="bi bi-box-seam"></i> Còn lại: <?php echo htmlspecialchars($row['Quantity']); ?> sản phẩm
                                </p>
                                <?php if ($row['Description']): ?>
                                    <p class="card-text product-description"><?php echo htmlspecialchars($row['Description']); ?></p>
                                <?php endif; ?>

                                <!-- Các trạng thái khác ở dưới mô tả -->
                                <div class="mb-2">
                                    <?php
                                        $status = [];
                                        if (!empty($row['is_bestseller']) && $row['is_bestseller']) {
                                            $status[] = '<span class="badge bg-warning text-dark me-1">Bán chạy</span>';
                                        }
                                        if (!empty($row['is_featured']) && $row['is_featured']) {
                                            $status[] = '<span class="badge bg-primary me-1">Nổi bật</span>';
                                        }
                                        // Không hiển thị "Giảm giá" ở đây nữa vì đã trang trí ở trên
                                        echo implode(' ', $status);
                                    ?>
                                </div>

                                <div class="mt-auto">
                                    <a href="cart.php?action=add&id=<?php echo urlencode($row['ProductID']); ?>" class="btn btn-primary btn-sm w-100">
                                        <i class="bi bi-cart-plus"></i> Thêm vào giỏ hàng
                                    </a>
                                </div>
                            </div>
                        </a>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
