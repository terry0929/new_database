
<?php
include '../common/db.php';
include '../common/header.php';

$categories = ['系所公告', '學生專欄', '國際交流', '課務公告', '事務公告', '演講公告', '一般活動公告', '競賽力活動公告', '企業徵才', '系友活動'];

$id = $_GET['id'] ?? null;
if (!$id) {
    echo "<div class='page-content'><p>找不到公告 ID。</p></div>";
    include '../common/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT * FROM announcement WHERE announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

$teacher_result = $conn->query("SELECT teacher_id, name FROM teacher ORDER BY teacher_id");
?>

<div class="page-content">
  <h2>✏️ 編輯公告</h2><br>
  <form method="post" action="update.php" enctype="multipart/form-data">
    <input type="hidden" name="announcement_id" value="<?= $row['announcement_id'] ?>">

    <h3>更換圖片：</h3><br>
    <input type="file" name="image" accept="image/*" style="width:80%; padding:10px; font-size: 16px;"><br><br>
    <h3>標題：</h3><br>
    <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" required style="width:80%; padding:10px; font-size: 16px;"><br><br>

    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($row['teacher_id']) ?>">

    <h3>分類：</h3><br>
    <?php $selected = explode(',', $row['category']); ?>
    <div style="display: flex; flex-wrap: wrap; gap: 10px;">
      <?php foreach ($categories as $cat): ?>
        <label style="display: inline-block; margin-right: 10px;">
          <input type="checkbox" name="category[]" value="<?= $cat ?>" <?= in_array($cat, $selected) ? 'checked' : '' ?>>
          <?= $cat ?>
        </label>
      <?php endforeach; ?>
    </div>
    <br><br>

    <h3>發布日期：</h3><br>
    <input type="date" name="post_date" value="<?= $row['post_date'] ?>" required style="width:80%; padding:10px; font-size: 16px;"><br><br>
    <h3>內容：</h3><br>
    <textarea name="content" rows="6" cols="60" required style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($row['content']) ?></textarea><br><br>

    <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
        </div>
  </form>
</div>

<?php include '../common/footer.php'; ?>
