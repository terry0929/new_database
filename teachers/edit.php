
<?php
session_start();
include('../common/db.php');
include('../common/header.php');

if (!isset($_SESSION['user_id'])) {
    echo "<p>請先登入。</p>";
    include 'common/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

$stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();

// 多分類處理
$all_categories = ['系主任', '榮譽特聘講座', '講座教授', '特約講座', '特聘教授', '專任教師', '兼任教師', '行政人員', '退休教師'];
$selected_categories = explode(',', $row['category'] ?? '');
?>

<div class="page-content">
    <h2>✏️ 編輯個人資料</h2>
        <form id="editForm" action="/~D1285210/teachers/update.php" method="post" onsubmit="return validateForm()">
        <input type="hidden" name="teacher_id" value="<?= $row['teacher_id'] ?>">

        <label><h3>姓名</h3><br><input type="text" name="name" value="<?= $row['name'] ?>" style="width:80%; padding: 10px; font-size: 16px;"></label><br><br>
        <label><h3>信箱</h3><br><input type="text" name="email" id="email" value="<?= $row['email'] ?>" required style="width:80%; padding:10px;font-size: 16px;">
        <span id="emailError" style="color:red; display:none;">⚠ 請輸入有效的 Email</span>
        </label><br><br>
        <label><h3>電話</h3><br>
        <input type="text" name="phone" id="phone" value="<?= $row['phone'] ?>" required style="width:80%; padding:10px;font-size: 16px;">
        <span id="phoneError" style="color:red; display:none;">⚠ 電話需為 10 位數字</span>
        </label><br><br>
        <label><h3>職稱</h3><br><input type="text" name="title" value="<?= $row['title'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>學歷</h3><br><input type="text" name="education" value="<?= $row['education'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>研究領域</h3><br><input type="text" name="research_field" value="<?= $row['research_field'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>

        <label><h3>分類（可複選）</h3><br>
        <?php foreach ($all_categories as $cat): ?>
            <label style="margin-right: 15px;">
                <input type="checkbox" name="category[]" value="<?= $cat ?>" <?= in_array($cat, $selected_categories) ? 'checked' : '' ?>>
                <?= $cat ?>
            </label>
        <?php endforeach; ?>
        </label><br><br>

        <label><h3>大頭照</h3><br><input type="file" name="photo"></label><br><br>
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius: 8px; cursor: pointer;">
        </div>
    </form>
</div>

<script>
function validateForm() {
    let valid = true;

    // Email 驗證
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('emailError');
    if (!email.includes('@') || !email.match(/^[^@]+@[^@]+\.[^@]+$/)) {
        emailError.style.display = 'inline';
        valid = false;
    } else {
        emailError.style.display = 'none';
    }

    // 電話驗證
    const phone = document.getElementById('phone').value;
    const phoneError = document.getElementById('phoneError');
    if (!/^\d{10}$/.test(phone)) {
        phoneError.style.display = 'inline';
        valid = false;
    } else {
        phoneError.style.display = 'none';
    }

    return valid; // 有錯誤會阻止表單送出
}
</script>


<?php include('../common/footer.php'); ?>
