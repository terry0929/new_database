
<?php
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

$stmt = $conn->prepare("UPDATE announcement SET title=?, post_date=?, content=?, category=?, teacher_id=? WHERE announcement_id=?");
$stmt->bind_param("sssssi", $title, $post_date, $content, $category, $teacher_id, $id);
$stmt->execute();

header("Location: /~D1285210/announcements/manage.php");
exit;
?>
