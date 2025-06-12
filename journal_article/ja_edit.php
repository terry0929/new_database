<?php
include '../common/db.php';
include '../common/header.php';

$id = $_GET['id'] ?? '';

if (!$id) {
    die("⚠️ 錯誤：沒有指定成果 ID");
}

$stmt = $conn->prepare("SELECT * FROM journal_articles WHERE result_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<div class="page-content">
    <h2>✏️ 編輯期刊論文資訊</h2>
    <form action="/~D1285210/journal_article/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>文獻名稱</h3><br><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" placeholder="例：葉韋坪" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" placeholder="例：這是一篇關於..." value="<?= htmlspecialchars($data['summary']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>卷號</h3><br><input type="text" name="volume" value="<?= htmlspecialchars($data['volume']) ?>" placeholder="請輸入卷號" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>期號</h3><br><input type="text" name="issue" value="<?= htmlspecialchars($data['issue']) ?>" placeholder="請輸入期號" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>頁碼</h3><br><input type="text" name="pages" value="<?= htmlspecialchars($data['pages']) ?>" placeholder="請輸入頁碼" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>DOI</h3><br><input type="text" name="doi" value="<?= htmlspecialchars($data['doi']) ?>" placeholder="請輸入DOI" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>文獻APA格式</h3><br><input type="text" name="APA" value="<?= htmlspecialchars($data['APA']) ?>" placeholder="請輸入文獻APA格式" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" value="<?= htmlspecialchars($data['remarks']) ?>" placeholder="請輸入備註" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>