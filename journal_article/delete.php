<?php
include '../common/db.php';

$id = $_GET['id'];

$stmt = $conn->prepare("DELETE FROM journal_articles WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../research/list.php");
exit;
?>
