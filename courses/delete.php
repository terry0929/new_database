<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include('../common/db.php');

if (!isset($_GET['id'])) {
    echo "⚠️ 缺少課程 ID"; exit;
}

$id = $_GET['id'];

// 先刪除所有該課程的配對教師資料
$stmt1 = $conn->prepare("DELETE FROM course_teacher WHERE course_id = ?");
$stmt1->bind_param("s", $id);
$stmt1->execute();

// 再刪除課程本體
$stmt2 = $conn->prepare("DELETE FROM course WHERE course_id = ?");
$stmt2->bind_param("s", $id);
$stmt2->execute();

header("Location: ../courses/list.php");
exit;
