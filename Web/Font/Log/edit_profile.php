<?php
session_start();
require_once 'config.php';

if (empty($_SESSION['ID']) || empty($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['ID'];

// Lấy thông tin khách hàng hiện tại
$stmt = $conn->prepare("SELECT * FROM customers WHERE UserID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();

// Xử lý cập nhật thông tin
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['CustomerName']);
    $phone = trim($_POST['Phone']);
    $address = trim($_POST['Address']);
    $gender = trim($_POST['Gender']);
    $date = trim($_POST['date']);


    $stmt = $conn->prepare("UPDATE customers SET CustomerName = ?, Phone = ?, Address = ?, Gender = ?, date = ? WHERE UserID = ?");
    $stmt->bind_param("sssssi", $name, $phone, $address, $gender, $date, $user_id);


    if ($stmt->execute()) {
        $success = "Cập nhật thông tin thành công!";
        // Reload lại thông tin mới
        $customer['CustomerName'] = $name;
        $customer['Phone'] = $phone;
        $customer['Address'] = $address;
        $customer['Gender'] = $gender;
    } else {
        $error = "Có lỗi xảy ra khi cập nhật!";
    }
}
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Chỉnh sửa thông tin cá nhân</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include '../header.php'; ?>

<main class="container my-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center"><i class="bi bi-pencil-square"></i> Chỉnh sửa thông tin cá nhân</h3>
            <?php if (!empty($success)): ?>
                <div class="alert alert-success"><?= htmlspecialchars($success) ?></div>
            <?php elseif (!empty($error)): ?>
                <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
            <?php endif; ?>

            <?php if ($customer): ?>
            <form method="post" id="editProfileForm">
                <div class="mb-3">
                    <label for="CustomerName" class="form-label">Họ tên</label>
                    <input type="text" class="form-control" id="CustomerName" name="CustomerName" value="<?= htmlspecialchars($customer['CustomerName']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Phone" class="form-label">Số điện thoại</label>
                    <input type="text" class="form-control" id="Phone" name="Phone" value="<?= htmlspecialchars($customer['Phone']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Address" class="form-label">Địa chỉ</label>
                    <input type="text" class="form-control" id="Address" name="Address" value="<?= htmlspecialchars($customer['Address']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="Gender" class="form-label">Giới tính</label>
                    <select class="form-select" id="Gender" name="Gender" required>
                        <option value="Nam" <?= $customer['Gender'] == 'Nam' ? 'selected' : '' ?>>Nam</option>
                        <option value="Nữ" <?= $customer['Gender'] == 'Nữ' ? 'selected' : '' ?>>Nữ</option>
                        <option value="Khác" <?= $customer['Gender'] == 'Khác' ? 'selected' : '' ?>>Khác</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="date" class="form-label">Ngày sinh</label>
                    <input type="date" class="form-control" id="date" name="date" value="<?= htmlspecialchars($customer['date']) ?>" required>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="profile.php" class="btn btn-secondary"><i class="bi bi-arrow-left"></i> Quay lại</a>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-save"></i> Lưu thay đổi</button>
                </div>
            </form>
            <?php else: ?>
                <div class="alert alert-danger">Không tìm thấy thông tin khách hàng!</div>
            <?php endif; ?>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>



<script>
document.getElementById('editProfileForm').addEventListener('submit', function(e) {
    if (!confirm('Bạn có chắc chắn muốn thay đổi thông tin không?')) {
        e.preventDefault(); // Ngăn không cho submit nếu bấm Cancel
    }
});
</script>

</body>
</html>
