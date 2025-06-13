
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
        <label><h3>標題:</h3><br>
            <input type="text" name="title" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <label><h3>分類（可複選）:</h3><br>
            <?php foreach ($all_categories as $cat): ?>
                <label style="margin-right: 15px;">
                    <input type="checkbox" name="category[]" value="<?= $cat ?>"> <?= $cat ?>
                </label>
            <?php endforeach; ?>
        </label><br><br>

        <label><h3>發佈日期:</h3><br>
            <input type="date" name="post_date" value="<?= $today ?>" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <label><h3>內容:</h3><br>
            <textarea name="content" style="width:80%; padding:10px; font-size: 16px;"></textarea>
        </label><br><br>

        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
