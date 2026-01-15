<?php
session_start();
require_once 'config.php';

if (empty($_SESSION['ID']) || empty($_SESSION['Username'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['ID'];
$username = $_SESSION['Username'];
$email = $_SESSION['Email'] ?? 'Chưa có email';

$stmt = $conn->prepare("SELECT * FROM customers WHERE UserID = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$customer = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Thông tin tài khoản</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
 
</head>
<body>
<?php 
// Định nghĩa biến để header biết đang ở thư mục con
$isSubDirectory = true;
include '../header.php'; 
?>

<main class="container my-5">
    <div class="card shadow mx-auto" style="max-width: 600px;">
        <div class="card-body">
            <h3 class="card-title mb-4 text-center"><i class="bi bi-person-circle"></i> Thông tin tài khoản</h3>
            <ul class="list-group list-group-flush mb-4">
                <li class="list-group-item"><strong>Tên đăng nhập:</strong> <?= htmlspecialchars($username) ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($email) ?></li>

                <?php if ($customer): ?>
                    <li class="list-group-item"><strong>Họ tên:</strong> <?= htmlspecialchars($customer['CustomerName']) ?></li>
                    <li class="list-group-item"><strong>Số điện thoại:</strong> <?= htmlspecialchars($customer['Phone']) ?></li>
                    <li class="list-group-item"><strong>Địa chỉ:</strong> <?= htmlspecialchars($customer['Address']) ?></li>
                    <li class="list-group-item"><strong>Giới tính:</strong> <?= htmlspecialchars($customer['Gender']) ?></li>
                    <li class="list-group-item"><strong>Ngày sinh:</strong> <?= htmlspecialchars($customer['date']) ?></li>

                <?php else: ?>
                    <li class="list-group-item text-danger">⚠ Không tìm thấy thông tin khách hàng tương ứng với tài khoản!</li>
                <?php endif; ?>
            </ul>

            <div class="d-flex justify-content-between">
                <a href="../index.php" class="btn btn-secondary"><i class="bi bi-house"></i> Trang chủ</a>
                <div>
                    <a href="edit_profile.php" class="btn btn-warning me-2"><i class="bi bi-pencil-square"></i> Chỉnh sửa</a>
                    <a href="logout.php" class="btn btn-danger"><i class="bi bi-box-arrow-right"></i> Đăng xuất</a>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include '../footer.php'; ?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
