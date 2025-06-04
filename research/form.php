<?php
include '../common/db.php';
include '../common/header.php';


// 取得登入者對應的 teacher_id
$teacher_id = '';
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $teacher_id = $row['teacher_id'];
    }
}
?>

<div class="page-content">
    <h2>➕ 新增研究成果</h2>
    <form action="/~D1285210/research/save.php" method="post">
        <label>標題：<input type="text" name="title" required></label><br>
        <label>主要類型：<input type="text" name="type1"></label><br>
        <label>次要類型：<input type="text" name="type2"></label><br>
        <label>發表日期：<input type="date" name="publish_date" required></label><br>
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <input type="submit" value="儲存">
    </form>
</div>

<?php include '../common/footer.php'; ?>
