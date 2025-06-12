<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$stmt = $conn->prepare(
    "UPDATE books_reports SET
    title=?, author=?, summary=?, publisher=?, isbn=?, chapter_info=?, book_type=?, upload_date=?, remarks=?
    WHERE result_id=?
");

$stmt->bind_param("ssssisssss",
    $_POST['title'],
    $_POST['author'],
    $_POST['summary'],
    $_POST['publisher'],
    $_POST['isbn'],
    $_POST['chapter_info'],
    $_POST['book_type'],
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