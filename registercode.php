<?php
include("dbcon.php");
session_start();

if (isset($_POST['register_btn'])) {
    $fullname = $_POST['fullname'];
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Escape các giá trị để tránh tấn công SQL Injection
    $fullname = mysqli_real_escape_string($conn, $fullname);
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    // Kiểm tra xem email đã tồn tại chưa
    $check_email_query = "SELECT * FROM users WHERE email='$email' LIMIT 1";
    $check_email_result = mysqli_query($conn, $check_email_query);

    if (mysqli_num_rows($check_email_result) > 0) {
        
        $_SESSION['status'] = "Email tồn tại.Làm ơn đăng ký với email khác.";
        header("location:index.php");
        exit();
    } else {
       
        $register_query = "INSERT INTO users (hovaten, email, password) VALUES ('$fullname', '$email', '$password')";
        $register_result = mysqli_query($conn, $register_query);

        if ($register_result) {
            
            $_SESSION['status'] = "Đăng ký thành công.Bạn có thể đăng nhập.";
            header("location:index.php");
            exit();
        } else {
            die("Query failed: " . mysqli_error($conn));
        }
    }
}
?>
