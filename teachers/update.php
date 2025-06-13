<?php
include '../common/db.php';

$teacher_id = $_POST['teacher_id'] ?? '';
$name = $_POST['name'] ?? '';
$email = $_POST['email'] ?? '';
$phone = $_POST['phone'] ?? '';
$title = $_POST['title'] ?? '';
$education = $_POST['education'] ?? '';
$research_field = $_POST['research_field'] ?? '';
$category = isset($_POST['category']) && is_array($_POST['category'])
    ? implode(',', array_map('trim', $_POST['category']))
    : ($_POST['category'] ?? '');

// 檢查是否有此教師
$stmt = $conn->prepare("SELECT photo FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

if (!$row) {
    echo "❌ 找不到教師資料"; exit;
}

$old_photo = $row['photo'] ?? null;

// 處理圖片上傳（如果有）
$photo_name = $old_photo;
if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "../uploads/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }
    $ext = pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION);
    $photo_name = uniqid('photo_') . '.' . $ext;
    $tmp_name = $_FILES['photo']['tmp_name'];
    move_uploaded_file($tmp_name, $upload_dir . $photo_name);
}

// 更新資料庫
$stmt = $conn->prepare("UPDATE teacher
    SET name = ?, email = ?, phone = ?, title = ?, education = ?, research_field = ?, photo = ?, category = ?
    WHERE teacher_id = ?");
$stmt->bind_param("sssssssss", $name, $email, $phone, $title, $education, $research_field, $photo_name, $category, $teacher_id);

if ($stmt->execute()) {
    echo "✅ 資料已更新，圖片檔名是：" . $photo_name;
    echo "<script>window.location.href='list.php';</script>";
} else {
    echo "❌ 錯誤：" . $stmt->error;
}
?>
