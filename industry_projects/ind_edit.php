<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM industry_projects WHERE result_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/industry_projects/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>計劃名稱</h3><br><input type="text" name="title" placeholder="例：產學合作計劃" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>負責人</h3><br><input type="text" name="author" placeholder="例：葉韋坪" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>合作單位</h3><br><input type="text" name="partners" placeholder="請輸入合作單位" value="<?= htmlspecialchars($data['partners']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>計劃資金</h3><br><input type="text" name="amount" placeholder="例：10000" value="<?= htmlspecialchars($data['amount']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>計劃簽署日期</h3><br><input type="date" name="signed_date" value="<?= $data['signed_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>成果摘要</h3><br><textarea name="outcome" placeholder="請輸入成果摘要" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($data['outcome']) ?></textarea></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" placeholder="請輸入備註" value="<?= htmlspecialchars($data['remarks']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>