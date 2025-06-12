<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$stmt = $conn->prepare(
    "UPDATE conference_papers SET
    title=?, author=?, summary=?, conference_name=?, locations=?, conference_date=?, upload_date=?, remarks=?
    WHERE result_id=?
");

$stmt->bind_param("sssssssss",
    $_POST['title'],
    $_POST['author'],
    $_POST['summary'],
    $_POST['conference_name'],
    $_POST['locations'],
    $_POST['conference_date'],
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