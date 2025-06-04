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
        <label>標題: <input type="text" name="title" required></label><br><br>
        <label>分類: <input type="text" name="category"></label><br><br>
        <label>發佈人: <input type="text" name="poster_name" value="<?= htmlspecialchars($poster_name) ?>" readonly></label><br><br>
        <label>發佈日期: <input type="date" name="post_date" required></label><br><br>

        <label>內容: <textarea name="content" required></textarea></label><br><br>
        <input type="hidden" name="user_id" value="<?= htmlspecialchars($_SESSION['user_id']) ?>">
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <input type="submit" value="儲存">
    </form>
</div>

<?php include '../common/footer.php'; ?>
