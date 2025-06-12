<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM conference_papers WHERE result_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/conference_paper/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>論文名稱</h3><br><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" placeholder="例：葉韋坪" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" placeholder="例：這是一篇關於..." value="<?= htmlspecialchars($data['summary']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>會議名稱</h3><br><input type="text" name="conference_name" value="<?= htmlspecialchars($data['conference_name']) ?>" placeholder="請輸入會議名稱" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>與會地點</h3><br><input type="text" name="locations" value="<?= htmlspecialchars($data['locations']) ?>" placeholder="請輸入與會地點" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>會議日期</h3><br><input type="date" name="conference_date" value="<?= $data['conference_date'] ?>" placeholder="請輸入會議日期" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" value="<?= htmlspecialchars($data['remarks']) ?>" placeholder="請輸入備註" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>