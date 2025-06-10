<?php
include '../common/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM conference_papers WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../research/list.php");
exit;
?>
