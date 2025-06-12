<?php
include '../common/db.php';

// 1. 處理 post_date 正確格式
$post_date_raw = $_POST['post_date'];
$post_date = str_replace('/', '-', $post_date_raw);

// 2. 準備更新
$stmt = $conn->prepare("UPDATE announcement SET 
    title = ?, content = ?, category = ?, post_date = ?
    WHERE announcement_id = ?");

$stmt->bind_param("ssssi",
    $_POST['title'],
    $_POST['content'],
    $_POST['category'],
    $post_date,
    $_POST['announcement_id']
);

if ($stmt->execute()) {
    header("Location: /~D1285210/announcements/manage.php");
    exit;
} else {
    echo "❌ 更新失敗：" . $stmt->error;
}
?>
