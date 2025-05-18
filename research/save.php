<?php
include 'common/db.php';
session_start();

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

$stmt = $conn->prepare("INSERT INTO research_result (title, type1, type2, publish_date, year, keywords, attachment, author)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("ssssisss",
    $_POST['title'], $_POST['type1'], $_POST['type2'], $_POST['publish_date'],
    $_POST['year'], $_POST['keywords'], $_POST['attachment'], $_POST['author']
);
$stmt->execute();

$result_id = $conn->insert_id;
$link = $conn->prepare("INSERT INTO teacher_research (teacher_id, result_id) VALUES (?, ?)");
$link->bind_param("si", $teacher_id, $result_id);
$link->execute();

header("Location: ../research/list.php");
exit;
