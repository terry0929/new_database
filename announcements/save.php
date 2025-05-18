<?php
include '../common/db.php';

$stmt = $conn->prepare("INSERT INTO announcement (title, content, category, view_count, post_date, poster_name, poster_unit, teacher_id)
                        VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssissss",
    $_POST['title'],
    $_POST['content'],
    $_POST['category'],
    $_POST['view_count'],
    $_POST['post_date'],
    $_POST['poster_name'],
    $_POST['poster_unit'],
    $_POST['teacher_id']
);

$stmt->execute();
header("Location: ../announcements/list.php");
exit;
