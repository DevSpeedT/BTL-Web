<?php
require_once 'Log/config.php';

if (!isset($_GET['id'])) {
    die('Thiếu ID sản phẩm');
}

$id = $_GET['id'];

// Lấy thông tin sản phẩm
$stmt = $conn->prepare("SELECT * FROM products WHERE ProductID = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$product = $stmt->get_result()->fetch_assoc();
$stmt->close();

if (!$product) {
    die('Không tìm thấy sản phẩm');
}

if (isset($_POST['update'])) {
    $ProductName = $_POST['ProductName'];
    $Price = $_POST['Price'];
    $Quantity = $_POST['Quantity'];
    $Description = $_POST['Description'];
    $Image = $product['Image'];

    // Trạng thái
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_sale = isset($_POST['is_sale']) ? 1 : 0;
    $discount_percent = isset($_POST['discount_percent']) ? intval($_POST['discount_percent']) : 0;

    // Xử lý upload ảnh mới nếu có
    if ($is_sale && $discount_percent <= 0) {
        $error = "Vui lòng nhập phần trăm giảm giá lớn hơn 0!";
    } else if ($is_sale && $discount_percent > 100) {
        $error = "Phần trăm giảm giá không được lớn hơn 100!";
    } else {
        $error = '';
    }
    if (!empty($_FILES['Image']['name'])) {
        $target_dir = "img/";
        if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
        $target_file = $target_dir . basename($_FILES["Image"]["name"]);
        if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
            $Image = $_FILES["Image"]["name"];
        }
    }

    $stmt = $conn->prepare("UPDATE products SET ProductName=?, Price=?, Quantity=?, Description=?, Image=?, is_featured=?, is_bestseller=?, is_sale=?, discount_percent=? WHERE ProductID=?");
    $stmt->bind_param(
        "sdissiiiis",
        $ProductName,
        $Price,
        $Quantity,
        $Description,
        $Image,
        $is_featured,
        $is_bestseller,
        $is_sale,
        $discount_percent,
        $id
    );
    if ($stmt->execute()) {
        $success = "Cập nhật thành công!";
        // header("Location: manage_product.php"); // Bỏ comment nếu muốn chuyển trang sau khi cập nhật
    } else {
        $error = "Cập nhật thất bại!";
    }
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Sửa sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-7">
                <div class="card shadow">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Sửa sản phẩm</h4>
                    </div>
                    <div class="card-body">
                        <?php if (!empty($success)): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                        <?php elseif (!empty($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>
                        <form method="post" enctype="multipart/form-data">
                            <div class="mb-3">
                                <label class="form-label">Tên sản phẩm</label>
                                <input type="text" name="ProductName" class="form-control" value="<?= htmlspecialchars($product['ProductName']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Giá</label>
                                <input type="number" name="Price" class="form-control" value="<?= $product['Price'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số lượng</label>
                                <input type="number" name="Quantity" class="form-control" value="<?= $product['Quantity'] ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Mô tả</label>
                                <textarea name="Description" class="form-control" rows="3"><?= htmlspecialchars($product['Description']) ?></textarea>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Ảnh hiện tại</label><br>
                                <?php if (!empty($product['Image'])): ?>
                                    <img src="img/<?= htmlspecialchars($product['Image']) ?>" style="width:120px; border-radius:8px; border:1px solid #ddd;">
                                <?php else: ?>
                                    <span class="text-muted">Chưa có ảnh</span>
                                <?php endif; ?>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Chọn ảnh mới (nếu muốn thay)</label>
                                <input type="file" name="Image" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Trạng thái sản phẩm</label>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                                        value="1" <?= !empty($product['is_featured']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_featured">Nổi bật</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_bestseller" id="is_bestseller"
                                        value="1" <?= !empty($product['is_bestseller']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_bestseller">Bán chạy</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="checkbox" name="is_sale" id="is_sale"
                                        value="1" <?= !empty($product['is_sale']) ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="is_sale">Giảm giá</label>
                                </div>
                            </div>
                            <div class="mb-3" id="discount_percent_group" style="display: <?= !empty($product['is_sale']) ? 'block' : 'none' ?>;">
                                <label class="form-label">Phần trăm giảm giá (%)</label>
                                <input type="number" name="discount_percent" id="discount_percent" class="form-control"
                                    min="1" max="100"
                                    value="<?= isset($product['discount_percent']) ? (int)$product['discount_percent'] : '' ?>">
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="manage_product.php" class="btn btn-secondary">Quay lại</a>
                                <button type="submit" name="update" class="btn btn-success">Cập nhật</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const isSaleCheckbox = document.getElementById('is_sale');
            const discountGroup = document.getElementById('discount_percent_group');
            const discountInput = document.getElementById('discount_percent');

            function toggleDiscountInput() {
                if (isSaleCheckbox.checked) {
                    discountGroup.style.display = 'block';
                    discountInput.required = true;
                } else {
                    discountGroup.style.display = 'none';
                    discountInput.required = false;
                    discountInput.value = '';
                }
            }

            isSaleCheckbox.addEventListener('change', toggleDiscountInput);
            toggleDiscountInput(); // Gọi lần đầu khi load trang
        });
    </script>

</body>
</html>
