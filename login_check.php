<?php
session_start();
include 'common/db.php';

$username = $_POST['username'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM user_account WHERE username = ? AND password = ?");
$stmt->bind_param("ss", $username, $password);
$stmt->execute();
$result = $stmt->get_result();

if ($user = $result->fetch_assoc()) {
    $_SESSION['user_id'] = $user['user_id'];
    echo "<script>alert('登入成功'); location.href='edit_mode.php';</script>";
} else {
    echo "<script>alert('帳號或密碼錯誤'); history.back();</script>";
}
