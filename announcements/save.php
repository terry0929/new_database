<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
session_start();

// 1. 從登入者抓出 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$teacher_id = $result->fetch_assoc()['teacher_id'] ?? '';

if (!$teacher_id) {
    exit("❌ 找不到對應的 teacher_id，請重新登入");
}

// 2. 處理 post_date
$post_date_raw = $_POST['post_date'];
$post_date = str_replace('/', '-', $post_date_raw);

// 3. 插入公告
$stmt = $conn->prepare("INSERT INTO announcement 
    (title, content, category, post_date, poster_name, teacher_id) 
    VALUES (?, ?, ?, ?, ?, ?)");

$stmt->bind_param("ssssss", // ← 最後也用 "s"
    $_POST['title'],
    $_POST['content'],
    $_POST['category'],
    $post_date,
    $_POST['poster_name'],
    $teacher_id
);

if ($stmt->execute()) {
    header("Location: /~D1285210/announcements/manage.php");
    exit;
} else {
    echo "❌ 儲存失敗：" . $stmt->error;
}
?>
