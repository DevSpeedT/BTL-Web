<?php
require_once 'Log/config.php';

// Lấy danh sách danh mục
$categories = [];
$result = $conn->query("SELECT CateID, CateName FROM categories");
while ($row = $result->fetch_assoc()) {
    $categories[] = $row;
}

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $productID = trim($_POST['ProductID']);
    $productName = trim($_POST['ProductName']);
    $price = trim($_POST['Price']);
    $quantity = trim($_POST['Quantity']);
    $desc = trim($_POST['Description']);
    $cateID = trim($_POST['CateID']);

    // Trạng thái sản phẩm
    $is_featured = isset($_POST['is_featured']) ? 1 : 0;
    $is_bestseller = isset($_POST['is_bestseller']) ? 1 : 0;
    $is_sale = isset($_POST['is_sale']) ? 1 : 0;
    $discount_percent = isset($_POST['discount_percent']) ? intval($_POST['discount_percent']) : 0;

    // Validate dữ liệu
    if (empty($productID) || empty($productName) || empty($price) || empty($quantity) || empty($cateID)) {
        $error = "Vui lòng nhập đầy đủ thông tin bắt buộc!";
    } elseif (!is_numeric($price) || $price <= 0) {
        $error = "Giá phải là số dương!";
    } elseif (!is_numeric($quantity) || $quantity < 0) {
        $error = "Số lượng phải là số không âm!";
    } elseif ($is_sale && $discount_percent <= 0) {
        $error = "Vui lòng nhập phần trăm giảm giá lớn hơn 0!";
    } else {
        // Kiểm tra xem ProductID đã tồn tại chưa
        $checkStmt = $conn->prepare("SELECT ProductID FROM products WHERE ProductID = ?");
        $checkStmt->bind_param("s", $productID);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();
        
        if ($checkResult->num_rows > 0) {
            $error = "Mã sản phẩm đã tồn tại, vui lòng chọn mã khác!";
        } else {
            // Xử lý upload ảnh
            $imageName = '';
            if (!empty($_FILES['Image']['name'])) {
                $target_dir = "img/";
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $imageName = basename($_FILES["Image"]["name"]);
                $target_file = $target_dir . $imageName;
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $allowTypes = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
                if (in_array($imageFileType, $allowTypes)) {
                    if (move_uploaded_file($_FILES["Image"]["tmp_name"], $target_file)) {
                        // OK
                    } else {
                        $error = "Lỗi upload ảnh!";
                    }
                } else {
                    $error = "Chỉ cho phép file ảnh JPG, JPEG, PNG, GIF, WEBP!";
                }
            }

            // Nếu không có lỗi upload ảnh thì thêm vào DB
            if (empty($error)) {
                $stmt = $conn->prepare("INSERT INTO products 
                    (ProductID, ProductName, Price, Quantity, Description, Image, CateID, is_featured, is_bestseller, is_sale, discount_percent) 
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
                $stmt->bind_param(
                    "ssdisssiiii",
                    $productID,
                    $productName,
                    $price,
                    $quantity,
                    $desc,
                    $imageName,
                    $cateID,
                    $is_featured,
                    $is_bestseller,
                    $is_sale,
                    $discount_percent
                );

                if ($stmt->execute()) {
                    header("Location: manage_product.php?success=1");
                    exit();
                } else {
                    $error = "Thêm sản phẩm thất bại!";
                }
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thêm sản phẩm</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php include 'header.php'; ?>
<div class="container my-5">
    <h2>Thêm sản phẩm mới</h2>
    <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
    <?php endif; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Mã sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="ProductID" id="ProductID" class="form-control" required>
            <small class="form-text text-muted">Nhập mã sản phẩm hoặc chọn danh mục để gợi ý mã.</small>
        </div>
        <div class="mb-3">
            <label class="form-label">Danh mục <span class="text-danger">*</span></label>
            <select name="CateID" id="CateID" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['CateID']) ?>" 
                            data-prefix="<?= htmlspecialchars(substr($cat['CateID'], 0, 2)) ?>">
                        <?= htmlspecialchars($cat['CateName']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Tên sản phẩm <span class="text-danger">*</span></label>
            <input type="text" name="ProductName" class="form-control" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Giá <span class="text-danger">*</span></label>
            <input type="number" name="Price" class="form-control" min="0" step="0.01" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Số lượng <span class="text-danger">*</span></label>
            <input type="number" name="Quantity" class="form-control" min="0" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Mô tả</label>
            <textarea name="Description" class="form-control"></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Ảnh sản phẩm</label>
            <input type="file" name="Image" class="form-control" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Trạng thái sản phẩm</label>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="is_featured" id="is_featured"
                       value="1">
                <label class="form-check-label" for="is_featured">Nổi bật</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="is_bestseller" id="is_bestseller"
                       value="1">
                <label class="form-check-label" for="is_bestseller">Bán chạy</label>
            </div>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" name="is_sale" id="is_sale"
                       value="1">
                <label class="form-check-label" for="is_sale">Giảm giá</label>
            </div>
        </div>
        <div class="mb-3" id="discount_percent_group" style="display: none;">
            <label class="form-label">Phần trăm giảm giá (%)</label>
            <input type="number" name="discount_percent" id="discount_percent" class="form-control"
                   min="1" max="100">
        </div>
        <button type="submit" class="btn btn-primary">Thêm mới</button>
        <a href="manage_product.php" class="btn btn-primary">Quay lại</a>
    </form>
</div>

<?php include 'footer.php'; ?>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Khi chọn danh mục, cập nhật trường ProductID với 2 ký tự đầu
    const cateSelect = document.getElementById('CateID');
    const productIDInput = document.getElementById('ProductID');
    cateSelect.addEventListener('change', function() {
        const selectedOption = this.options[this.selectedIndex];
        if (selectedOption.value) {
            const prefix = selectedOption.getAttribute('data-prefix');
            productIDInput.value = prefix;
            productIDInput.focus();
        }
    });

    // Ẩn/hiện phần trăm giảm giá khi tick Giảm giá
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
    toggleDiscountInput();
});
</script>
</body>
</html>
