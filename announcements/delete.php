<?php
include '../common/db.php';

$id = $_GET['id'];
$stmt = $conn->prepare("DELETE FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

header("Location: ../announcements/manage.php");
exit;
