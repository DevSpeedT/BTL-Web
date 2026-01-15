<?php
// Kết nối database
require_once 'Log/config.php';

// Xử lý xóa sản phẩm
if (isset($_GET['delete'])) {
    $productID = $_GET['delete'];
    $conn->query("DELETE FROM products WHERE ProductID = '$productID'");
    header("Location: manage_product.php");
    exit();
}

// Lấy danh sách sản phẩm
$result = $conn->query("SELECT * FROM products");
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Quản lý sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
     
</head>
<body>

<?php include 'header.php'; ?>
<div class="container my-5">
    <h1>Quản lý sản phẩm</h1>
    <a href="add_product.php" class="btn btn-success mb-3">+ Thêm sản phẩm mới</a>
    <table class="table table-bordered align-middle">
        <thead>
            <tr>
                <th>Mã SP</th>
                <th>Tên sản phẩm</th>
                <th>Giá</th>
                <th>Số lượng</th>
                <th>Mô tả</th>
                <th>Danh mục</th>
                <th>Ảnh</th>
                <th>Trạng thái</th>
                <th>Hành động</th>
            </tr>
        </thead>
        <tbody>
        <?php while($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['ProductID']) ?></td>
                <td><?= htmlspecialchars($row['ProductName']) ?></td>
                <td><?= number_format($row['Price']) ?>₫</td>
                <td><?= $row['Quantity'] ?></td>
                <td><?= htmlspecialchars($row['Description']) ?></td>
                <td><?= htmlspecialchars($row['CateID']) ?></td>
                <td>
                    <?php if (!empty($row['Image'])): ?>
                        <img src="img/<?= htmlspecialchars($row['Image']) ?>" alt="Ảnh sản phẩm" style="width:60px; height:auto;">
                    <?php else: ?>
                        <span>Không có ảnh</span>
                    <?php endif; ?>
                </td>
                <td>
                    <?php
                        // Hiển thị trạng thái đặc biệt
                        $status = [];
                        if (!empty($row['is_bestseller']) && $row['is_bestseller']) {
                            $status[] = '<span class="badge bg-warning text-dark">Bán chạy</span>';
                        }
                        if (!empty($row['is_featured']) && $row['is_featured']) {
                            $status[] = '<span class="badge bg-primary">Nổi bật</span>';
                        }
                        if (!empty($row['is_sale']) && $row['is_sale']) {
                            $discount = !empty($row['discount_percent']) ? intval($row['discount_percent']) : 0;
                            $status[] = '<span class="badge bg-danger">Giảm giá'.($discount ? ' -'.$discount.'%' : '').'</span>';
                        }
                        echo implode(' ', $status) ?: '<span class="text-muted">Bình thường</span>';
                    ?>
                </td>
                <td>
                    <a href="edit_product.php?id=<?= urlencode($row['ProductID']) ?>" class="btn btn-warning btn-sm">Sửa</a>
                    <a href="manage_product.php?delete=<?= urlencode($row['ProductID']) ?>" class="btn btn-danger btn-sm" onclick="return confirm('Bạn có chắc chắn muốn xóa?')">Xóa</a>
                </td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>
</div>



    <script src="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
