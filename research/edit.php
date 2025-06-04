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
        <label>標題：<input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>"></label><br>
        <label>主要類型：<input type="text" name="type1" value="<?= htmlspecialchars($data['type1']) ?>"></label><br>
        <label>次要類型：<input type="text" name="type2" value="<?= htmlspecialchars($data['type2']) ?>"></label><br>
        <label>發表日期：<input type="date" name="publish_date" value="<?= $data['publish_date'] ?>"></label><br>
        <input type="submit" value="更新">
    </form>
</div>

<?php include '../common/footer.php'; ?>
