<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$stmt = $conn->prepare(
    "UPDATE industry_projects SET
    title=?, author=?, partners=?, amount=?, signed_date=?, outcome=?, upload_date=?, remarks=?
    WHERE result_id=?
");

$stmt->bind_param("sssisssss",
    $_POST['title'],
    $_POST['author'],
    $_POST['partners'],
    $_POST['amount'],
    $_POST['signed_date'],
    $_POST['outcome'],
    $_POST['upload_date'],
    $_POST['remarks'],
    $_POST['result_id']
);

if ($stmt->execute()) {
    echo "<script>
        alert('✅ 期刊論文更新成功！');
        window.location.href = '/~D1285210/research/list.php';
    </script>";
    exit;
} else {
    echo "<p>❌ 更新失敗：" . htmlspecialchars($stmt->error) . "</p>";
}
?>