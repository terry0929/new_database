<?php
include '../common/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM nstc_projects WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../research/list.php");
exit;
?>
