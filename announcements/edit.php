<?php
include '../common/db.php';
include '../common/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<h2>✏️ 編輯公告</h2>
<form action="announcement/update.php" method="post">
    <input type="hidden" name="announcement_id" value="<?= $row['announcement_id'] ?>">
    <label>標題: <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>"></label><br>
    <label>內容: <textarea name="content"><?= htmlspecialchars($row['content']) ?></textarea></label><br>
    <label>分類: <input type="text" name="category" value="<?= $row['category'] ?>"></label><br>
    <label>瀏覽次數: <input type="number" name="view_count" value="<?= $row['view_count'] ?>"></label><br>
    <label>發佈日期: <input type="datetime-local" name="post_date" value="<?= date('Y-m-d\TH:i', strtotime($row['post_date'])) ?>"></label><br>
    <label>發佈人: <input type="text" name="poster_name" value="<?= $row['poster_name'] ?>"></label><br>
    <label>發佈單位: <input type="text" name="poster_unit" value="<?= $row['poster_unit'] ?>"></label><br>
    <label>教師 ID: <input type="text" name="teacher_id" value="<?= $row['teacher_id'] ?>"></label><br>
    <input type="submit" value="更新">
</form>

<?php include '../common/footer.php'; ?>
