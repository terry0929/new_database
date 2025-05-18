<?php
include '../common/db.php';

$id = $_GET['id'];

$conn->prepare("DELETE FROM teacher_research WHERE result_id = ?")
     ->bind_param("i", $id)
     ->execute();

$conn->prepare("DELETE FROM research_result WHERE result_id = ?")
     ->bind_param("i", $id)
     ->execute();

header("Location: ../research/list.php");
exit;
