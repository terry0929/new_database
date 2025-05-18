<?php
include '../common/db.php';

$course_id = $_POST['course_id'];
$teacher_id = $_POST['teacher_id'];

$stmt = $conn->prepare("INSERT IGNORE INTO course_teacher (course_id, teacher_id) VALUES (?, ?)");
$stmt->bind_param("ss", $course_id, $teacher_id);
$stmt->execute();

header("Location: teacher_link.php?course_id=$course_id");
exit;
