<?php
include '../common/db.php';

if (!isset($_GET['course_id'])) {
    exit('❌ 未指定課程 ID');
}

$course_id = $_GET['course_id'];

$stmt = $conn->prepare("DELETE FROM course WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();

header("Location: my_courses.php");
exit;
?>
