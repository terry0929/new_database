<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM books_reports WHERE result_id = ?");
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
                    const minLength = 10; // 最小位數
                    const maxLength = 13; // 最大位數
                    if (value.length != minLength && value.length != maxLength) {
                        this.style.borderColor = 'red';
                        errorMessage.textContent = `數字位數必須為 ${minLength} 或 ${maxLength} 位`;
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
    <form action="/~D1285210/books_reports/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>文獻名稱</h3><br><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>作者(可填寫多位)</h3><br><input type="text" name="author" value="<?= htmlspecialchars($data['author']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>作品簡述</h3><br><input type="text" name="summary" value="<?= htmlspecialchars($data['summary']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>出版商</h3><br><input type="text" name="publisher" value="<?= htmlspecialchars($data['publisher']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>ISBN</h3><br><input type="number" name="isbn" value="<?= htmlspecialchars($data['isbn']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>章節資訊</h3><br><input type="text" name="chapter_info" value="<?= htmlspecialchars($data['chapter_info']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>書籍類型</h3><br><input type="text" name="book_type" value="<?= htmlspecialchars($data['book_type']) ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>上傳日期</h3><br><input type="date" name="upload_date" value="<?= $data['upload_date'] ?>" style="width:80%; padding:10px; font-size: 16px;" required></label><br><br>
        <label><h3>備註</h3><br><input type="text" name="remarks" value="<?= htmlspecialchars($data['remarks']) ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>