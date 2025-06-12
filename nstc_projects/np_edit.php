<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM nstc_projects WHERE result_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
?>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');
        const inputs = form.querySelectorAll('input');

        inputs.forEach(input => {
            const errorMessage = document.createElement('span'); // 建立錯誤訊息元素
            errorMessage.classList.add('error-message');
            errorMessage.style.color = 'red';
            input.parentElement.appendChild(errorMessage); // 將錯誤訊息元素添加到輸入欄下方

            input.addEventListener('input', function () {
                if (this.type === 'number') {
                    const value = this.value;
                    const minlength = 1; // 指定數字最小位數為 1 位
                    const maxlength = 8; // 指定數字位數為 8 位
                    if (value.length > maxlength) {
                        this.style.borderColor = 'red';
                        errorMessage.textContent = `數字位數必須小於 ${maxlength} 位`;
                    } else if (value.length < minlength) {
                        this.style.borderColor = 'red';
                        errorMessage.textContent = `數字位數必須大於 ${minlength} 位`;
                    } else {
                        this.style.borderColor = '';
                        errorMessage.textContent = ''; // 清除錯誤訊息
                    }
                } else if (!this.checkValidity()) {
                    this.style.borderColor = 'red';
                    errorMessage.textContent = this.validationMessage || '必填'; // 顯示錯誤訊息或「必填」
                } else {
                    this.style.borderColor = '';
                    errorMessage.textContent = ''; // 清除錯誤訊息
                }
            });
        });
    });
</script>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/nstc_projects/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>計劃名稱</h3><br><input type="text" name="title" placeholder="例：產學合作計劃" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>負責人</h3><br><input type="text" name="author" placeholder="例：葉韋坪" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" placeholder="例：這是一篇關於..." value="<?= htmlspecialchars($data['summary']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>專案編號</h3><br><input type="text" name="project_number" placeholder="請輸入專案編號" value="<?= htmlspecialchars($data['project_number']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>資助單位</h3><br><input type="text" name="funding_agency" placeholder="請輸入資助單位" value="<?= htmlspecialchars($data['funding_agency']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>資助金額</h3><br><input type="number" name="amount" placeholder="請輸入資助金額" value="<?= htmlspecialchars($data['amount']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>計劃開始日期</h3><br><input type="date" name="starts_date" value="<?= $data['starts_date'] ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>計劃結束日期</h3><br><input type="date" name="end_date" value="<?= $data['end_date'] ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>計劃摘要</h3><br><textarea name="summary" style="width:80%; padding:10px; font-size: 16px;" required><?= htmlspecialchars($data['summary']) ?></textarea></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" placeholder="請輸入備註" value="<?= htmlspecialchars($data['remarks']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>