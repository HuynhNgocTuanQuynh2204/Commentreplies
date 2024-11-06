<?php
include("dbcon.php");
session_start();

if (isset($_POST['login_btn'])) {
    $email = $_POST['email'];
    $password = $_POST['password'];

    // Escape các giá trị để tránh tấn công SQL Injection
    $email = mysqli_real_escape_string($conn, $email);
    $password = mysqli_real_escape_string($conn, $password);

    $login = "SELECT * FROM users WHERE email='$email' AND password='$password' LIMIT 1";
    $query = mysqli_query($conn, $login);

    if ($query) {
        if (mysqli_num_rows($query) > 0) {
            $userdata = mysqli_fetch_array($query);
            $user_id = $userdata["id"];
            $user_name = $userdata["hovaten"];
            $_SESSION['auth_user_id'] = $user_id;
            $_SESSION['authuser_name'] = $user_name;

            $_SESSION['status'] = "Login Successfully";
            header("location:index.php");
            exit();
        } else {
            $_SESSION['status'] = "Invalid Email Or Password";
            header("location:index.php");
            exit();
        }
    } else {
        die("Query failed: " . mysqli_error($conn));
    }
}
?>
