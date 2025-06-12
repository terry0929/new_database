
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$title = $_POST['title'];
$teacher_id = $_POST['teacher_id'];
$post_date = $_POST['post_date'];
$content = $_POST['content'];
$category = '';

if (!empty($_POST['category']) && is_array($_POST['category'])) {
    $category = implode(',', array_map('trim', $_POST['category']));
}

// ✅ 圖片上傳處理
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "../uploads_ann/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $img_name = uniqid('img_') . '.' . $ext;
    $tmp_name = $_FILES['image']['tmp_name'];
    $upload_path = $upload_dir . $img_name;

    if (!move_uploaded_file($tmp_name, $upload_path)) {
        die("❌ move_uploaded_file 失敗，請確認 uploads_ann 資料夾存在且可寫入！");
    }
} else {
    $img_name = null;
}

$stmt = $conn->prepare("INSERT INTO announcement (title, content, category, post_date, teacher_id, image)
                        VALUES (?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssss", $title, $content, $category, $post_date, $teacher_id, $img_name);
$stmt->execute();

header("Location: /~D1285210/announcements/manage.php");
exit;
?>
