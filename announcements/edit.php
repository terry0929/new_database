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
  <form method="post" action="update.php" enctype="multipart/form-data" id="editForm">
    <input type="hidden" name="announcement_id" value="<?= $row['announcement_id'] ?>">

    <h3>更換圖片：</h3><br>
    <input type="file" name="image" accept="image/*" style="width:80%; padding:10px; font-size: 16px;"><br><br>

    <div class="form-group">
      <h3>標題：</h3><br>
      <input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"><br>
    </div><br>

    <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($row['teacher_id']) ?>">

    <h3>分類：</h3><br>
    <div class="category-section" style="display: flex; flex-wrap: wrap; gap: 10px;">
      <?php $selected = explode(',', $row['category']); ?>
      <?php foreach ($categories as $cat): ?>
        <label style="display: inline-block; margin-right: 10px;">
          <input type="checkbox" name="category[]" value="<?= $cat ?>" <?= in_array($cat, $selected) ? 'checked' : '' ?>>
          <?= $cat ?>
        </label>
      <?php endforeach; ?>
    </div>
    <br><br>

    <div class="form-group">
      <h3>發布日期：</h3><br>
      <input type="date" name="post_date" value="<?= $row['post_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"><br>
    </div><br>

    <div class="form-group">
      <h3>內容：</h3><br>
      <textarea name="content" rows="6" cols="60" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($row['content']) ?></textarea><br>
    </div><br>

    <div style="display: flex; justify-content: center; margin-top: 20px;">
      <input type="submit" value="更新" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
    </div>
  </form>
</div>

<script>
document.getElementById('editForm').addEventListener('submit', function (e) {
  let isValid = true;

  const title = document.querySelector('input[name="title"]');
  const content = document.querySelector('textarea[name="content"]');
  const date = document.querySelector('input[name="post_date"]');
  const categories = document.querySelectorAll('input[name="category[]"]');
  const categorySection = document.querySelector('.category-section');

  document.querySelectorAll('.error-msg').forEach(el => el.remove());

  if (title.value.trim() === '') {
    showError(title.parentNode, '⚠️ 請填寫標題');
    isValid = false;
  }

  if (content.value.trim() === '') {
    showError(content.parentNode, '⚠️ 請填寫內容');
    isValid = false;
  }

  if (date.value.trim() === '') {
    showError(date.parentNode, '⚠️ 請選擇日期');
    isValid = false;
  }

  if (![...categories].some(c => c.checked)) {
    showError(categorySection, '⚠️ 請至少選擇一個分類');
    isValid = false;
  }

  if (!isValid) {
    e.preventDefault();
  }

  function showError(target, msg) {
    const err = document.createElement('div');
    err.className = 'error-msg';
    err.style.color = 'red';
    err.style.marginTop = '4px';
    err.textContent = msg;
    target.appendChild(err);
  }
});
</script>

<?php include '../common/footer.php'; ?>
