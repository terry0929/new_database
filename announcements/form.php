<?php
include '../common/db.php';
include '../common/header.php';

// 初始化變數
$teacher_id = '';
$poster_name = '';

if (isset($_SESSION['user_id'])) {
    // 查詢對應的 teacher_id 和 教師姓名
    $stmt = $conn->prepare("SELECT u.teacher_id, t.name AS teacher_name
                            FROM user_account u
                            JOIN teacher t ON u.teacher_id = t.teacher_id
                            WHERE u.user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $teacher_result = $stmt->get_result();
    $teacher_data = $teacher_result->fetch_assoc();
    $teacher_id = $teacher_data['teacher_id'] ?? '';
    $poster_name = $teacher_data['teacher_name'] ?? '';
}
?>

<div class="page-content">
    <h2>➕ 新增公告</h2>
    <form action="/~D1285210/announcements/save.php" method="post">
        <label><h3>標題:</h3><br><input type="text" name="title" value="<?= htmlspecialchars($row['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>分類:</h3><br><input type="text" name="category" value="<?= $row['category'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>發佈人:</h3><br><input type="text" name="poster_name" value="<?= htmlspecialchars($poster_name) ?>" style="width:80%; padding:10px; font-size: 16px;" readonly></label><br><br>
        <label><h3>發佈日期:</h3><br><input type="date" name="post_date" style="width:80%; padding:10px; font-size: 16px;" value="<?= $row['post_date'] ?>"></label><br><br>
        <label><h3>內容:</h3><br><textarea name="content" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($row['content']) ?></textarea></label><br><br>
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
