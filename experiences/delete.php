<?php
include '../common/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM experience WHERE experience_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../experiences/list.php");
exit;
