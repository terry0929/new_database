
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$id = $_POST['announcement_id'];
$title = $_POST['title'];
$post_date = $_POST['post_date'];
$content = $_POST['content'];
$teacher_id = $_POST['teacher_id'];

$category = '';
if (!empty($_POST['category']) && is_array($_POST['category'])) {
    $category = implode(',', array_map('trim', $_POST['category']));
}

// 取得原圖
$stmt = $conn->prepare("SELECT image FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$original = $stmt->get_result()->fetch_assoc();
$img_name = $original['image'] ?? '';

// 若有新圖片
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $upload_dir = "../uploads_ann/";
    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $ext = pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION);
    $img_name = uniqid('img_') . '.' . $ext;
    $tmp_name = $_FILES['image']['tmp_name'];
    move_uploaded_file($tmp_name, $upload_dir . $img_name);
}

// 更新資料
$stmt = $conn->prepare("UPDATE announcement 
    SET title=?, post_date=?, content=?, category=?, teacher_id=?, image=? 
    WHERE announcement_id=?");
$stmt->bind_param("ssssssi", $title, $post_date, $content, $category, $teacher_id, $img_name, $id);
$stmt->execute();

header("Location: /~D1285210/announcements/manage.php");
exit;
?>
