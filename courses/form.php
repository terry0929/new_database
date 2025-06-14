<?php
include '../common/db.php';
include '../common/header.php';

// 初始化變數
$teacher_id = '';
$teacher_name = '';

// 抓取目前登入的使用者對應的教師資料
if (isset($_SESSION['user_id'])) {
    $stmt = $conn->prepare("SELECT u.teacher_id, t.name AS teacher_name
                            FROM user_account u
                            JOIN teacher t ON u.teacher_id = t.teacher_id
                            WHERE u.user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher = $result->fetch_assoc();
    $teacher_id = $teacher['teacher_id'] ?? '';
    $teacher_name = $teacher['teacher_name'] ?? '';
}

// 初始化課程欄位（為了新增頁面）
$course = [
    'name' => '',
    'location' => '',
    'day' => '',
    'start_time' => '',
    'end_time' => '',
    'semester' => '',
    'credits' => '',
    'classroom' => '',
    'teacher_name' => $teacher_name,
    'syllabus' => ''
];
?>

<div class="page-content">
    <h2>➕ 新增課程</h2>
    <form action="/~D1285210/courses/save.php" method="post">
        <label><h3>課程名稱:</h3><br>
            <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>課堂地點:</h3><br>
            <input type="text" name="location" value="<?= htmlspecialchars($course['location']) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>上課時間:</h3><br>
            <select name="day" style="width:80%; padding:10px; font-size: 16px;" required>
                <?php
                $days = ['一', '二', '三', '四', '五' , '六', '日'];
                foreach ($days as $d) {
                    $selected = ($course['day'] === $d) ? 'selected' : '';
                    echo "<option value='$d' $selected>星期$d</option>";
                }
                ?>
            </select><br><br>
            第
            <select name="start_time" style="width:35.2%; padding:10px; font-size: 16px;" required>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?= $i ?>" <?= ($course['start_time'] == $i ? 'selected' : '') ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            節 到 第
            <select name="end_time" style="width:35.2%; padding:10px; font-size: 16px;" required>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?= $i ?>" <?= ($course['end_time'] == $i ? 'selected' : '') ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            節
        </label><br><br>

        <label><h3>學期:</h3><br>
            <input type="text" name="semester" value="<?= htmlspecialchars($course['semester']) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>學分數:</h3><br>
            <input type="number" name="credits" value="<?= $course['credits'] ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>課程大綱:</h3><br>
            <textarea name="syllabus" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($course['syllabus']) ?></textarea>
        </label><br><br>

        <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
    </div>
    </form>
</div>

<script>
document.querySelector('form').addEventListener('submit', function(e) {
    let isValid = true;

    const title = document.querySelector('input[name="name"]');
    const location = document.querySelector('input[name="location"]');
    const semester = document.querySelector('input[name="semester"]');
    const credits = document.querySelector('input[name="credits"]');
    const syllabus = document.querySelector('textarea[name="syllabus"]');

    // 清除舊錯誤訊息
    document.querySelectorAll('.error-msg').forEach(el => el.remove());

    function showError(input, msg) {
        const err = document.createElement('div');
        err.className = 'error-msg';
        err.style.color = 'red';
        err.textContent = msg;
        input.parentNode.appendChild(err);
    }

    if (!title.value.trim()) {
        showError(title, '⚠️ 請填寫課程名稱');
        isValid = false;
    }
    if (!location.value.trim()) {
        showError(location, '⚠️ 請填寫地點');
        isValid = false;
    }
    if (!semester.value.trim()) {
        showError(semester, '⚠️ 請填寫學期');
        isValid = false;
    }
    if (!credits.value.trim() || isNaN(credits.value) || Number(credits.value) <= 0) {
        showError(credits, '⚠️ 學分數需為正數');
        isValid = false;
    }
    if (!syllabus.value.trim()) {
        showError(syllabus, '⚠️ 請填寫課程大綱');
        isValid = false;
    }

    if (!isValid) e.preventDefault();
});
</script>

<?php include '../common/footer.php'; ?>
