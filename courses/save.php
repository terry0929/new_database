<?php
include '../common/db.php';

$stmt = $conn->prepare("INSERT INTO course (course_id, name, location, time, semester, credits, syllabus, teacher_name, classroom)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssissss",
    $_POST['course_id'],
    $_POST['name'],
    $_POST['location'],
    $_POST['time'],
    $_POST['semester'],
    $_POST['credits'],
    $_POST['syllabus'],
    $_POST['teacher_name'],
    $_POST['classroom']
);

$stmt->execute();
header("Location: ../courses/list.php");
exit;
