<?php
include '../common/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM research_result WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: list.php");
exit;
?>
