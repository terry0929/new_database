<?php
include '../common/db.php';
include '../common/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<div class="page-content">
<h2>✏️ 編輯公告</h2>
<form action="/~D1285210/announcements/update.php" method="post">
    <input type="hidden" name="announcement_id" value="<?= $row['announcement_id'] ?>">
    <label>標題: <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>"></label><br>
    <label>分類: <input type="text" name="category" value="<?= $row['category'] ?>"></label><br>
    <label>發佈人: <input type="text" name="poster_name" value="<?= $row['poster_name'] ?>"></label><br>
    <label>發佈日期: <input type="date" name="post_date" required></label><br><br>
    <label>內容: <textarea name="content"><?= htmlspecialchars($row['content']) ?></textarea></label><br>
    <label>教師 ID: <input type="text" name="teacher_id" value="<?= $row['teacher_id'] ?>"></label><br>
    <input type="submit" value="更新">
</form>
</div>

<?php include '../common/footer.php'; ?>
