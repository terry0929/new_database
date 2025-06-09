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
        <label><h3>標題：</h3><br><input type="text" name="title" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>主要類型：</h3><br><input type="text" name="type1" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>次要類型：</h3><br><input type="text" name="type2" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>發表日期：</h3><br><input type="date" name="publish_date" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
