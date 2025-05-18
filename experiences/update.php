<?php
include '../common/db.php';

$stmt = $conn->prepare("UPDATE experience SET type = ?, description = ? WHERE experience_id = ?");
$stmt->bind_param("ssi", $_POST['type'], $_POST['description'], $_POST['experience_id']);
$stmt->execute();

header("Location: ../experiences/list.php");
exit;
