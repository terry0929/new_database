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
                if (!this.checkValidity()) {
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
    <h2>➕ 新增期刊論文</h2>
    <form action="/~D1285210/journal_article/save.php" method="post">
        <label>
            <h3>文獻名稱</h3><br><input type="text" name="title" placeholder="例：traralalo tralala" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>作者(可填寫多位)</h3><br><input type="text" name="author" placeholder="例：葉韋坪" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>作品簡述</h3><br><input type="text" name="summary" placeholder="例：這是一篇關於..." style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>卷號</h3><br><input type="number" name="volume" placeholder="請輸入卷號" style="width:80%; padding:10px; font-size: 16px;" required min="1" max="9999">
        </label><br><br>

        <label>
            <h3>期號</h3><br><input type="number" name="issue" placeholder="請輸入期號" style="width:80%; padding:10px; font-size: 16px;" required min="1" max="9999">
        </label><br><br>

        <label>
            <h3>頁碼</h3><br><input type="text" name="pages" placeholder="請輸入頁碼，整份請填 整份" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>DOI</h3><br><input type="text" name="doi" placeholder="請輸入DOI" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>上傳日期</h3><br><input type="date" name="upload_date" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>文獻APA格式</h3><br><input type="text" name="APA" placeholder="請輸入文獻APA格式" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label>
            <h3>備註</h3><br><input type="text" name="remarks" placeholder="請輸入備註" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>