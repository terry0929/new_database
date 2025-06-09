<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

$stmt = $conn->prepare("
    UPDATE research_result SET
    title=?, author=?, type1=?, type2=?, publish_date=?
    WHERE result_id=?
");

$stmt->bind_param("sssssi",
    $_POST['title'],
    $_POST['author'],
    $_POST['type1'],
    $_POST['type2'],
    $_POST['publish_date'],
    $_POST['result_id']
);

if ($stmt->execute()) {
    header("Location: list.php");
    exit;
} else {
    echo "<p>❌ 更新失敗：" . htmlspecialchars($stmt->error) . "</p>";
}
?>
