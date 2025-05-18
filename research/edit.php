<?php
include('../common/db.php');
include('../common/header.php');
$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM research_result WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<h2>✏️ 編輯研究成果</h2>

<form action="research/update.php" method="post">
    <input type="hidden" name="result_id" value="<?= $row['result_id'] ?>">
    <label>標題: <input type="text" name="title" value="<?= $row['title'] ?>"></label><br>
    <label>主要類型: <input type="text" name="type1" value="<?= $row['type1'] ?>"></label><br>
    <label>次要類型: <input type="text" name="type2" value="<?= $row['type2'] ?>"></label><br>
    <label>發表時間: <input type="date" name="publish_date" value="<?= $row['publish_date'] ?>"></label><br>
    <label>發表年份: <input type="number" name="year" value="<?= $row['year'] ?>"></label><br>
    <label>關鍵字: <input type="text" name="keywords" value="<?= $row['keywords'] ?>"></label><br>
    <label>附件檔名: <input type="text" name="attachment" value="<?= $row['attachment'] ?>"></label><br>
    <label>作者: <input type="text" name="author" value="<?= $row['author'] ?>"></label><br>
    <input type="submit" value="更新">
</form>

<?php include '../common/footer.php'; ?>
