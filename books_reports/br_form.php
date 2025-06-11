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
    <h2>➕ 新增專書與技術報告</h2>
    <form action="/~D1285210/books_reports/save.php" method="post">
        <label><h3>文獻名稱</h3><br><input type="text" name="title" placeholder="例：traralalo tralala" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" placeholder="例：葉韋坪" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" placeholder="例：這是一篇關於..." style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>出版商</h3><br><input type="text" name="publisher" placeholder="請輸入出版商" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>ISBN</h3><br><input type="text" name="isbn" placeholder="請輸入ISBN" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>章節資訊</h3><br><input type="text" name="chapter_info" placeholder="請輸入章節資訊" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>書籍類型</h3><br><input type="text" name="book_type" placeholder="請輸入書籍類型" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" placeholder="請輸入備註" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <input type="hidden" name="category" value="br">
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>