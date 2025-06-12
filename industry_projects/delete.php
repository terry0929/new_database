<?php
include '../common/db.php';

$result_id = $_GET['result_id'];

// 刪除 researchs_result 中的資料（會自動刪除 nstc_projects 中的相關資料）
$stmt = $conn->prepare("DELETE FROM researchs_result WHERE result_id = ?");
$stmt->bind_param("s", $result_id);
$stmt->execute();

header("Location: ../research/list.php");
exit;
?>
