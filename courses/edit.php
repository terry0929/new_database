<?php
include '../common/db.php';
include '../common/header.php';

// 檢查是否提供課程 ID
if (!isset($_GET['course_id'])) {
    echo "<p>⚠️ 未指定課程 ID</p>";
    include '../common/footer.php';
    exit;
}

$course_id = $_GET['course_id'];
$stmt = $conn->prepare("SELECT * FROM course WHERE course_id = ?");
$stmt->bind_param("i", $course_id);
$stmt->execute();
$course = $stmt->get_result()->fetch_assoc();

if (!$course) {
    echo "<p>❌ 找不到課程</p>";
    include '../common/footer.php';
    exit;
}

// 抓目前登入的老師資料（自動填入可編輯欄位）
$teacher_name = '';
if (isset($_SESSION['user_id'])) {
    $stmt2 = $conn->prepare("SELECT t.name AS teacher_name
                             FROM user_account u
                             JOIN teacher t ON u.teacher_id = t.teacher_id
                             WHERE u.user_id = ?");
    $stmt2->bind_param("s", $_SESSION['user_id']);
    $stmt2->execute();
    $result2 = $stmt2->get_result();
    $teacher_data = $result2->fetch_assoc();
    $teacher_name = $teacher_data['teacher_name'] ?? '';
}
?>

<div class="page-content">
    <h2>✏️ 編輯課程</h2>
    <form action="/~D1285210/courses/update.php" method="post">
        <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">

        <label><h3>課程名稱:</h3><br>
            <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>課堂地點:</h3><br>
            <input type="text" name="location" value="<?= htmlspecialchars($course['location']) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>上課時間:</h3><br>
            <select name="day" style="width:80%; padding:10px; font-size: 16px;" required>
                <?php
                $days = ['一', '二', '三', '四', '五'];
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
            <input type="text" name="semester" value="<?= htmlspecialchars($course['semester']) ?>" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <label><h3>學分數:</h3><br>
            <input type="number" name="credits" value="<?= $course['credits'] ?>" style="width:80%; padding:10px; font-size: 16px;">
        </label><br><br>

        <label><h3>授課教師:</h3><br>
            <input type="text" name="teacher_name" value="<?= htmlspecialchars($course['teacher_name'] ?: $teacher_name) ?>" style="width:80%; padding:10px; font-size: 16px;" required>
        </label><br><br>

        <label><h3>課程大綱:</h3><br>
            <textarea name="syllabus" style="width:80%; padding:10px; font-size: 16px;"><?= htmlspecialchars($course['syllabus']) ?></textarea>
        </label><br><br>

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
