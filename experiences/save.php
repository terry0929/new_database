<?php
include '../common/db.php';
session_start();

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

$stmt = $conn->prepare("INSERT INTO experience (teacher_id, type, description) VALUES (?, ?, ?)");
$stmt->bind_param("sss", $teacher_id, $_POST['type'], $_POST['description']);
$stmt->execute();

header("Location: ../experiences/list.php");
exit;
