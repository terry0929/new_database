<?php
include '../common/db.php';

$stmt = $conn->prepare("UPDATE course
    SET name=?, location=?, time=?, semester=?, credits=?, syllabus=?, teacher_name=?, classroom=?
    WHERE course_id=?");
$stmt->bind_param("ssssissss",
    $_POST['name'],
    $_POST['location'],
    $_POST['time'],
    $_POST['semester'],
    $_POST['credits'],
    $_POST['syllabus'],
    $_POST['teacher_name'],
    $_POST['classroom'],
    $_POST['course_id']
);

$stmt->execute();
header("Location: ../courses/list.php");
exit;
