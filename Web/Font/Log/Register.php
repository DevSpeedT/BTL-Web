<?php
session_start();
include 'config.php'; // Kết nối DB

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    // Lấy dữ liệu từ form
    $username = validate($_POST['username']);
    $password = validate($_POST['password']);
    $email = validate($_POST['email']);
    $customer_name = validate($_POST['customerName']);
    $mobile = validate($_POST['phone']);
    $address = validate($_POST['address']);
    $gender = validate($_POST['gender']);
    $date = validate($_POST['date']);


    // Kiểm tra dữ liệu
    if (empty($username) || empty($password) || empty($email) || empty($mobile) || empty($address) || empty($customer_name) || empty($gender) || empty($date)) {
        header("Location: Register.php?error=Vui lòng nhập đầy đủ thông tin");
        exit();
    } else {
        // Kiểm tra trùng username hoặc email
        $sql = "SELECT * FROM users WHERE Username = '$username' OR Email = '$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: Register.php?error=Username hoặc Email đã tồn tại");
            exit();
        } else {
            // Thêm user vào bảng users
            $sql = "INSERT INTO users (Username, Password, Email) VALUES ('$username', '$password', '$email')";
            if (mysqli_query($conn, $sql)) {
                // Lấy ID vừa tạo
                $user_id = mysqli_insert_id($conn);

                // Sinh CustomerID mới 
                $result = mysqli_query($conn, "SELECT CustomerID FROM customers ORDER BY CustomerID DESC LIMIT 1"); // Lấy CustomerID cuối cùng
                $lastID = mysqli_fetch_assoc($result)['CustomerID'] ?? 'KH000'; // Mặc định nếu không có khách hàng nào
                $num = intval(substr($lastID, 2)) + 1; // Lấy số sau 'KH' và tăng lên 1
                $newCustomerID = 'KH' . str_pad($num, 3, '0', STR_PAD_LEFT); // Tạo CustomerID mới
                
                // Thêm vào bảng customers
                $sql2 = "INSERT INTO customers (CustomerID, CustomerName, Phone, Email, Address, Gender, UserID, date) 
                        VALUES ('$newCustomerID', '$customer_name', '$mobile', '$email', '$address', '$gender', '$user_id', '$date')";
                if (mysqli_query($conn, $sql2)) {

                    //  Sinh mã Cart_ID mới
                    $result_cart = mysqli_query($conn, "SELECT Cart_ID FROM cart ORDER BY Cart_ID DESC LIMIT 1"); // Lấy Cart_ID cuối cùng
                    $lastCartID = mysqli_fetch_assoc($result_cart)['Cart_ID'] ?? 'CART000';
                    $cart_num = intval(substr($lastCartID, 4)) + 1;
                    $newCartID = 'CART' . str_pad($cart_num, 3, '0', STR_PAD_LEFT);

                    // Thêm giỏ hàng cho khách hàng
                    $sql3 = "INSERT INTO cart (Cart_ID, CustomerID) VALUES ('$newCartID', '$newCustomerID')";
                    if (mysqli_query($conn, $sql3)) {
                        header("Location: Register.php?success=1");
                        exit();
                    } else {
                        header("Location: Register.php?error=Không thể tạo giỏ hàng.");
                        exit();
                    }

                } else {
                    header("Location: Register.php?error=Đăng ký thất bại khi thêm khách hàng.");
                    exit();
                }
            } else {
                header("Location: Register.php?error=Đăng ký thất bại khi thêm tài khoản.");
                exit();
            }
        }
    }
}
mysqli_close($conn);
?>


<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Đăng Ký</title>
    <link rel="stylesheet" href="regis.css">
</head>
<body>
    <div class="container">
        <h2 class="form-title">Form Đăng Ký</h2>
        <?php if (isset($_GET['success'])): ?>
            <script>
                alert('🎉 Đăng ký thành công! Bạn có thể đăng nhập tại đây.');
            </script>
        <?php endif; ?>
        <?php if (isset($_GET['error'])): ?>
            <script>
                alert('⚠️ <?= htmlspecialchars($_GET['error']) ?>');
            </script>
        <?php endif; ?>
        <form action="Register.php" method="POST">
            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" id="username" name="username" required>
            </div>
            <div class="form-group">
                <label for="password">Mật khẩu</label>
                <input type="password" id="password" name="password" required>
            </div>
            <div class="form-group">
                <label for="customerName">Họ và Tên</label>
                <input type="text" id="customerName" name="customerName" required>
            </div>
            <div class="form-group">
                <label for="phone">Số điện thoại</label>
                <input type="tel" id="phone" name="phone" maxlength="10" required>
            </div>
            <div class="form-group">
                <label for="address">Địa chỉ</label>
                <input type="text" id="address" name="address" required>
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group">
                <label for="gender">Giới tính</label>
                <select id="gender" name="gender" required>
                    <option value="">Chọn giới tính</option>
                    <option value="Male">Nam</option>
                    <option value="Female">Nữ</option>
                    <option value="Other">Khác</option>
                </select>
            </div>
            <div class="form-group">
                <label for="date">Ngày sinh</label>
                <input type="date" id="date" name="date" required>
            </div>
            <br>
            <button type="submit" class="submit-button">Đăng Ký</button>
        </form>
        <p><span>Đã có tài khoản?</span> <a href="login.php">Đăng nhập</a></p>
    </div>
</body>
</html>
