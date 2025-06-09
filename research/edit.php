<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM research_result WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/research/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>標題</h3><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>作者</h3><input type="text" name="author" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>主要類型</h3><input type="text" name="type1" value="<?= htmlspecialchars($data['type1']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>次要類型</h3><input type="text" name="type2" value="<?= htmlspecialchars($data['type2']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>發表日期</h3><input type="date" name="publish_date" value="<?= $data['publish_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
