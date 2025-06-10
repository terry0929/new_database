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
    <h2>➕ 新增會議論文</h2>
    <form action="/~D1285210/conference_paper/save.php" method="post">
        <label><h3>論文名稱</h3><br><input type="text" name="title" placeholder="例：traralalo tralala" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" placeholder="例：葉韋坪" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>會議名稱</h3><br><input type="text" name="conference_name" placeholder="請輸入會議名稱" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>與會地點</h3><br><input type="text" name="locations" placeholder="請輸入與會地點" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>會議日期</h3><br><input type="date" name="conference_date" placeholder="請輸入會議日期" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>文獻簡述</h3><br><input type="text" name="summary" placeholder="請輸入文獻簡述" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" placeholder="請輸入備註" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <input type="hidden" name="category" value="cp">
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>