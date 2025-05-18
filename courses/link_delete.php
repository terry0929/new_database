<?php
include '../common/db.php';

$course_id = $_GET['course_id'];
$teacher_id = $_GET['teacher_id'];

$stmt = $conn->prepare("DELETE FROM course_teacher WHERE course_id = ? AND teacher_id = ?");
$stmt->bind_param("ss", $course_id, $teacher_id);
$stmt->execute();

header("Location: teacher_link.php?course_id=$course_id");
exit;
