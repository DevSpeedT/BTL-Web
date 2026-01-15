<?php
session_start();
include 'config.php'; // Kết nối DB

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['username']) && isset($_POST['password'])) {
    function validate($data){
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $username = validate($_POST['username']);
    $pass = validate($_POST['password']);

    if(empty($username)){
        $error = 'Username is required';
    } else if(empty($pass)){
        $error = 'Password is required';
    } else {
        $sql = "SELECT * FROM users WHERE Username = '$username' AND Password = '$pass'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) === 1) {
            $user = mysqli_fetch_assoc($result);
            $userID = $user['ID'];


            // Lấy thông tin khách hàng
            $customerQuery = "SELECT * FROM customers WHERE UserID = '$userID'";
            $customerResult = mysqli_query($conn, $customerQuery);
            $customer = mysqli_fetch_assoc($customerResult);

            // Tạo session
            $_SESSION['Username'] = $user['Username'];
            $_SESSION['ID'] = $userID;
            $_SESSION['Email'] = $user['Email'];
            $_SESSION['CustomerID'] = $customer['CustomerID'] ?? null;
            $_SESSION['CustomerName'] = $customer['CustomerName'] ?? null;
            $_SESSION['role'] = $user['role'];

            header("Location: ../index.php"); // Chuyển về trang chủ
            exit();
        } else {
            $error = 'Incorrect username or password';
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
    <form action="login.php" method="post">
        <h1>Login</h1>

        <?php if ($error): ?>
            <p style="color: red;"><?= htmlspecialchars($error) ?></p>
        <?php endif; ?>

        <label for="username">Username:</label><br>
        <input type="text" id="username" name="username" placeholder="Enter username"><br><br>

        <label for="password">Password:</label><br>
        <input type="password" id="password" name="password" placeholder="Enter password"><br><br>

        <button type="submit">Login</button>

        <p><span>Don't have an account?</span> <a href="Register.php">Register</a></p>
        <p><a href="../index.php">Back</a></p>
    </form>
</body>
</html>
