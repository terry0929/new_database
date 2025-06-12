
<?php
include '../common/db.php';
include '../common/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$all_categories = ['系所公告', '學生專欄', '國際交流', '課務公告', '事務公告', '演講公告', '一般活動公告', '競賽力活動公告', '企業徵才', '系友活動'];
$selected_categories = explode(',', $row['category']);
?>

<div class="page-content">
<h2>✏️ 編輯公告</h2>
<form action="/~D1285210/announcements/update.php" method="post">
    <input type="hidden" name="announcement_id" value="<?= $row['announcement_id'] ?>">

    <label><h3>標題:</h3><br>
        <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" style="width:80%; padding:10px; font-size: 16px;">
    </label><br><br>

    <label><h3>分類（可複選）:</h3><br>
        <?php foreach ($all_categories as $cat): ?>
            <label style="margin-right: 15px;">
                <input type="checkbox" name="category[]" value="<?= $cat ?>" <?= in_array($cat, $selected_categories) ? 'checked' : '' ?>> <?= $cat ?>
            </label>
        <?php endforeach; ?>
    </label><br><br>

    <label><h3>發佈日期:</h3><br>
        <input type="date" name="post_date" style="width:80%; padding:10px; font-size: 16px;" value="<?= $row['post_date'] ?>">
    </label><br><br>

    <label><h3>內容:</h3><br>
        <textarea name="content" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($row['content']) ?></textarea>
    </label><br><br>

    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($row['teacher_id']) ?>">

    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
    </div>
</form>
</div>

<?php include '../common/footer.php'; ?>
