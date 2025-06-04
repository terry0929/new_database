<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../common/db.php';

$teacher_id = $_POST['teacher_id'];

// 準備圖片上傳處理
$photo = '';
if (!empty($_FILES['photo']['name'])) {
    $photo = basename($_FILES['photo']['name']);
    $upload_dir = __DIR__ . '/../uploads/';
    $upload_path = $upload_dir . $photo;

    // 若 uploads 資料夾不存在，自動建立
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    // 嘗試上傳圖片
    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $upload_path)) {
        die("❌ 圖片上傳失敗，請確認 uploads 資料夾存在且可寫入！");
    }
}

// 動態產生 SQL 與參數
$sql = "UPDATE teacher SET name=?, email=?, phone=?, title=?, education=?, research_field=?";
$params = [
    $_POST['name'],
    $_POST['email'],
    $_POST['phone'],
    $_POST['title'],
    $_POST['education'],
    $_POST['research_field']
];
$types = "ssssss";

if ($photo) {
    $sql .= ", photo=?";
    $params[] = $photo;
    $types .= "s";
}

$sql .= " WHERE teacher_id=?";
$params[] = $teacher_id;
$types .= "s";

// 執行更新
$stmt = $conn->prepare($sql);
if (!$stmt) {
    die("SQL 錯誤：" . $conn->error);
}
$stmt->bind_param($types, ...$params);
$stmt->execute();

// 導向個人頁面
header("Location: ../teachers/manage.php?id=$teacher_id");
exit;
