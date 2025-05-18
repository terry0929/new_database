<?php
include '../common/db.php';

$stmt = $conn->prepare("UPDATE announcement
                        SET title=?, content=?, category=?, view_count=?, post_date=?, poster_name=?, poster_unit=?, teacher_id=?
                        WHERE announcement_id=?");
$stmt->bind_param("sssissssi",
    $_POST['title'],
    $_POST['content'],
    $_POST['category'],
    $_POST['view_count'],
    $_POST['post_date'],
    $_POST['poster_name'],
    $_POST['poster_unit'],
    $_POST['teacher_id'],
    $_POST['announcement_id']
);

$stmt->execute();
header("Location: ../announcements/list.php");
exit;
