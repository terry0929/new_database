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
    <label><h3>標題:</h3><br><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
    <label><h3>分類:</h3><br><input type="text" name="category" value="<?= $row['category'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
    <label><h3>發佈日期:</h3><br><input type="date" name="post_date" style="width:80%; padding:10px; font-size: 16px;" value="<?= $row['post_date'] ?>"></label><br><br>
    <label><h3>內容:</h3><br><textarea name="content" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($row['content']) ?></textarea></label><br><br>
    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
    </div>
</form>
</div>

<?php include '../common/footer.php'; ?>
