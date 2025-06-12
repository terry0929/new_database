<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books_reports WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/books_reports/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>文獻名稱</h3><br><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" value="<?= htmlspecialchars($data['summary']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>出版商</h3><br><input type="text" name="publisher" value="<?= htmlspecialchars($data['publisher']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>ISBN</h3><br><input type="text" name="isbn" value="<?= htmlspecialchars($data['isbn']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>章節資訊</h3><br><input type="text" name="chapter_info" value="<?= htmlspecialchars($data['chapter_info']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>書籍類型</h3><br><input type="text" name="book_type" value="<?= htmlspecialchars($data['book_type']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" value="<?= htmlspecialchars($data['remarks']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>