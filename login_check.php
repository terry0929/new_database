<?php
session_start();
include("common/db.php");

$username = $_POST['username'];
$password = $_POST['password'];
$role = $_POST['role'];  // 這就是你登入頁送來的身分

if ($role === 'student') {
    // ✅ 學生登入：從 student_account 查
    $stmt = $conn->prepare("SELECT * FROM student_account WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['student_id']; // 用 student_id 當主 key
        $_SESSION['role'] = 'student';             // ✅ 儲存角色方便未來使用
        header("Location: index.php");
        exit;
    }

} elseif ($role === 'teacher') {

    $stmt = $conn->prepare("SELECT * FROM user_account WHERE username = ? AND password = ?");
    $stmt->bind_param("ss", $username, $password);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $_SESSION['user_id'] = $row['user_id']; // 用 user_id 當主 key
        $_SESSION['teacher_id'] = $row['teacher_id']; // ✅ 給功能頁面用
        $_SESSION['role'] = 'teacher';             // ✅ 儲存角色方便未來使用
        header("Location: index.php");
        exit;
    }
}

// ❌ 登入失敗
echo "<script>alert('帳號或密碼錯誤'); location.href='login.php';</script>";
exit;
?>
