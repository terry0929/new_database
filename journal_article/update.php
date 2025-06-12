<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$stmt = $conn->prepare(
    "UPDATE journal_articles SET
    title=?, author=?, summary=?, volume=?, issue=?, pages=?, doi=?, upload_date=?, APA=?, remarks=?
    WHERE result_id=?
");

$stmt->bind_param("sssiissssss",
    $_POST['title'],
    $_POST['author'],
    $_POST['summary'],
    $_POST['volume'],
    $_POST['issue'],
    $_POST['pages'],
    $_POST['doi'],
    $_POST['upload_date'],
    $_POST['APA'],
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