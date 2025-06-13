
<?php
session_start();
include '../common/db.php';
include '../common/header.php';

// 初始化變數
$teacher_id = '';
$teacher_name = '';

if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT u.teacher_id, t.name AS teacher_name
                            FROM user_account u
                            JOIN teacher t ON u.teacher_id = t.teacher_id
                            WHERE u.user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $teacher_data = $stmt->get_result()->fetch_assoc();
    $teacher_id = $teacher_data['teacher_id'] ?? '';
    $teacher_name = $teacher_data['teacher_name'] ?? '';
}

$all_categories = ['系所公告', '學生專欄', '國際交流', '課務公告', '事務公告', '演講公告', '一般活動公告', '競賽力活動公告', '企業徵才', '系友活動'];
$today = date('Y-m-d');
?>

<div class="page-content">
    <h2>➕ 新增公告</h2>
    <form action="/~D1285210/announcements/save.php" method="post" enctype="multipart/form-data">
        <label>公告圖片：</label>
         <label><br><input type="file" name="image" accept="image/*" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
         <div class="form-group">
        <label><h3>標題:</h3><br>
            <input type="text" name="title" style="width:80%; padding:10px; font-size: 16px;">
        </label>
        </div><br><br>

        <label><h3>分類（可複選）:</h3></label>
<div class="category-section">
  <?php foreach ($all_categories as $cat): ?>
    <label style="display:inline-block; margin-right: 10px;">
      <input type="checkbox" name="category[]" value="<?= $cat ?>">
      <?= $cat ?>
    </label>
  <?php endforeach; ?>
</div><br><br>

        <label><h3>發佈日期:</h3><br>
            <input type="date" name="post_date" value="<?= $today ?>" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <div class="form-group">
        <label><h3>內容:</h3><br>
            <textarea name="content" style="width:80%; padding:10px; font-size: 16px;"></textarea>
        </label>
        </div><br><br>

        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
        </div>
    </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function (e) {
    let isValid = true;

    const title = document.querySelector('input[name="title"]');
    const content = document.querySelector('textarea[name="content"]');
    const date = document.querySelector('input[name="post_date"]');
    const categories = document.querySelectorAll('input[name="category[]"]');
    const categorySection = document.querySelector('.category-section');

    // 移除所有舊的錯誤訊息
    document.querySelectorAll('.error-msg').forEach(el => el.remove());

    // 標題驗證
    if (title.value.trim() === '') {
        showError(title.parentNode, '⚠️ 請填寫標題');
        isValid = false;
    }

    // 內容驗證
    if (content.value.trim() === '') {
        showError(content.parentNode, '⚠️ 請填寫內容');
        isValid = false;
    }

    // 日期驗證
    if (date.value.trim() === '') {
        showError(date.parentNode, '⚠️ 請選擇日期');
        isValid = false;
    }

    // 至少選擇一個分類
    if (![...categories].some(cat => cat.checked)) {
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
