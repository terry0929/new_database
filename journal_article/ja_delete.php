<?php
include('../common/db.php');

$result_id = $_GET['id'] ?? '';

if (!$result_id) {
    die("⚠️ 錯誤：沒有指定成果 ID");
}

// 先刪關聯表
$stmt = $conn->prepare(
    "DELETE FROM Teacher_research WHERE results_id = ?"
);
$stmt->bind_param("s", $result_id);
$stmt->execute();

// 刪除子表：journal_articles
$stmt = $conn->prepare(
    "DELETE FROM journal_articles WHERE result_id = ?"
);
$stmt->bind_param("s", $result_id);
$stmt->execute();

// 最後刪主表
$stmt = $conn->prepare(
    "DELETE FROM researchs_result WHERE result_id = ?"
);
$stmt->bind_param("s", $result_id);
$stmt->execute();

echo "<script>
        alert('✅ 期刊論文刪除成功！');
        window.location.href = '/~D1285210/research/list.php';
    </script>";
exit;
?>