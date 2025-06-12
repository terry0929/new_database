
<?php
include '../common/db.php';

$title = $_POST['title'];
$teacher_id = $_POST['teacher_id'];
$post_date = $_POST['post_date'];
$content = $_POST['content'];
$category = '';

if (!empty($_POST['category']) && is_array($_POST['category'])) {
    $category = implode(',', array_map('trim', $_POST['category']));
}

$stmt = $conn->prepare("INSERT INTO announcement (title, teacher_id, post_date, content, category) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("sssss", $title, $teacher_id, $post_date, $content, $category);
$stmt->execute();

header("Location: /~D1285210/announcements/manage.php");
exit;
?>
