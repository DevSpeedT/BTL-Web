<?php
session_start();
include 'Log/config.php';

// Kiểm tra đăng nhập khi thêm vào giỏ hàng
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id'])) {
    if (!isset($_SESSION['ID'])) { // Sử dụng đúng biến session bạn lưu khi đăng nhập
        echo "<script>alert('Vui lòng đăng nhập để thêm vào giỏ hàng!'); window.location='Log/login.php';</script>";
        exit();
    }
}

// Lấy thông tin user
$user_id = $_SESSION['ID'] ?? null;

if (!$user_id) {
    $customer_id = null;
    $cart_id = null;
} else {
    // Lấy CustomerID từ bảng customers
    $stmt = $conn->prepare("SELECT CustomerID FROM customers WHERE UserID = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $stmt->bind_result($customer_id);
    $stmt->fetch();
    $stmt->close();

    if (!$customer_id) {
        echo "Không tìm thấy thông tin khách hàng!";
        exit();
    }

    // Lấy Cart_ID của user 
    $stmt = $conn->prepare("SELECT Cart_ID FROM cart WHERE CustomerID = ?");
    $stmt->bind_param("s", $customer_id);
    $stmt->execute();
    $stmt->bind_result($cart_id);
    $stmt->fetch();
    $stmt->close();

    if (!$cart_id) {
        // Nếu chưa có cart, tạo mới
        $cart_id = 'CART' . str_pad(rand(1, 999), 3, '0', STR_PAD_LEFT);
        $stmt = $conn->prepare("INSERT INTO cart (Cart_ID, CustomerID) VALUES (?, ?)");
        $stmt->bind_param("ss", $cart_id, $customer_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Xử lý thêm sản phẩm vào cart_items
if (isset($_GET['action']) && $_GET['action'] == 'add' && isset($_GET['id']) && $cart_id) {
    $product_id = $_GET['id'];

    // Kiểm tra sản phẩm đã có trong cart_items chưa
    $stmt = $conn->prepare("SELECT ID, Quantity FROM cart_items WHERE Cart_ID = ? AND ProductID = ?");
    $stmt->bind_param("ss", $cart_id, $product_id);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows > 0) {
        // Đã có, tăng số lượng
        $stmt->bind_result($item_id, $quantity);
        $stmt->fetch();
        $stmt->close();

        $new_quantity = $quantity + 1;
        $stmt = $conn->prepare("UPDATE cart_items SET Quantity = ? WHERE ID = ?");
        $stmt->bind_param("ii", $new_quantity, $item_id);
        $stmt->execute();
        $stmt->close();
    } else {
        // Chưa có, thêm mới
        $stmt->close();
        $stmt = $conn->prepare("INSERT INTO cart_items (Cart_ID, ProductID, Quantity) VALUES (?, ?, 1)");
        $stmt->bind_param("ss", $cart_id, $product_id);
        $stmt->execute();
        $stmt->close();
    }

    // Chuyển về trang giỏ hàng
    header("Location: cart.php");
    exit();
}

// Xử lý cập nhật số lượng và xóa sản phẩm
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $cart_id) {
    if (isset($_POST['action']) && $_POST['action'] === 'update') {
        $item_id = intval($_POST['item_id']);
        $quantity = intval($_POST['quantity']);
        if ($quantity > 0) {
            $stmt = $conn->prepare("UPDATE cart_items SET Quantity = ? WHERE ID = ? AND Cart_ID = ?");
            $stmt->bind_param("iis", $quantity, $item_id, $cart_id);
            $stmt->execute();
            $stmt->close();
        }
        header("Location: cart.php");
        exit();
    }

    if (isset($_POST['action']) && $_POST['action'] === 'delete') {
        $item_id = intval($_POST['item_id']);
        $stmt = $conn->prepare("DELETE FROM cart_items WHERE ID = ? AND Cart_ID = ?");
        $stmt->bind_param("is", $item_id, $cart_id);
        $stmt->execute();
        $stmt->close();
        header("Location: cart.php");
        exit();
    }
}

// Lấy danh sách sản phẩm trong giỏ hàng
if ($cart_id) {
    $sql = "SELECT ci.ID, ci.ProductID, ci.Quantity, p.ProductName, p.Price, p.Image
            FROM cart_items ci
            JOIN products p ON ci.ProductID = p.ProductID
            WHERE ci.Cart_ID = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $cart_id);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = false;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Giỏ hàng của bạn</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">


    <style>
    table td,
    table th {
        vertical-align: middle !important;
        text-align: center;
    }

    table tr {
        height: 80px;
    }

    .btn {
        min-width: 80px;
    }
    </style>

</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container my-5">
        <h2>Giỏ hàng của bạn</h2>
        <?php if (!isset($_SESSION['ID'])): ?>
            <div class="alert alert-warning">Bạn cần đăng nhập để dùng được giỏ hàng.</div>
        <?php else: ?>
        <table class="table">
            <thead class="table-light align-middle text-center">
                <tr>
                    <th>Ảnh</th>
                    <th>Tên sản phẩm</th>
                    <th>Giá</th>
                    <th>Số lượng</th>
                    <th>Thành tiền</th>
                    <th>Hành động</th>
                </tr>
            </thead>
            <tbody>
            <?php $total = 0; if ($result): while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><img src="img/<?= htmlspecialchars($row['Image']) ?>" width="60"></td>
                <td><?= htmlspecialchars($row['ProductName']) ?></td>
                <td><?= number_format($row['Price']) ?> đ</td>
                <td>
                    <form id="update-form-<?= $row['ID'] ?>" method="post" action="cart.php">
                        <input type="hidden" name="action" value="update">
                        <input type="hidden" name="item_id" value="<?= $row['ID'] ?>">
                        <input type="number" name="quantity" value="<?= $row['Quantity'] ?>" min="1" class="form-control form-control-sm" style="width:70px;">
                    </form>
                </td>
                <td><?= number_format($row['Price'] * $row['Quantity']) ?> đ</td>
                <td class="align-middle text-center">
                    <div class="d-inline-flex gap-2 align-items-center">
                        <button type="submit" form="update-form-<?= $row['ID'] ?>" class="btn btn-sm btn-success">Cập nhật</button>
                        <form method="post" action="cart.php" onsubmit="return confirm('Bạn chắc chắn muốn xóa sản phẩm này khỏi giỏ hàng?');">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="item_id" value="<?= $row['ID'] ?>">
                            <button type="submit" class="btn btn-sm btn-danger">Xóa</button>
                        </form>
                    </div>
                </td>
            </tr>
            <?php $total += $row['Price'] * $row['Quantity']; endwhile; endif; ?>

                <tr>
                    <td colspan="4" class="text-end fw-bold">Tổng cộng</td>
                    <td class="fw-bold text-danger"><?= number_format($total) ?> đ</td>
                    <td></td>
                </tr>
            </tbody>
        </table>
        <button type="submit" class="btn btn-sm btn-success">Mua hàng</button>
        <?php endif; ?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>



