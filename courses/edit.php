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

        <label>課程名稱: 
            <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>" required>
        </label><br>

        <label>課堂地點: 
            <input type="text" name="location" value="<?= htmlspecialchars($course['location']) ?>">
        </label><br>

        <label>上課時間: 
            <select name="day" required>
                <?php
                $days = ['一', '二', '三', '四', '五', '六', '日'];
                foreach ($days as $d) {
                    $selected = ($course['day'] === $d) ? 'selected' : '';
                    echo "<option value='$d' $selected>星期$d</option>";
                }
                ?>
            </select>
            第
            <select name="start_time" required>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?= $i ?>" <?= ($course['start_time'] == $i ? 'selected' : '') ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            節 到
            <select name="end_time" required>
                <?php for ($i = 1; $i <= 14; $i++): ?>
                    <option value="<?= $i ?>" <?= ($course['end_time'] == $i ? 'selected' : '') ?>><?= $i ?></option>
                <?php endfor; ?>
            </select>
            節
        </label><br>

        <label>學期: 
            <input type="text" name="semester" value="<?= htmlspecialchars($course['semester']) ?>">
        </label><br>

        <label>學分數: 
            <input type="number" name="credits" value="<?= $course['credits'] ?>">
        </label><br>

        <label>課堂教室: 
            <input type="text" name="classroom" value="<?= htmlspecialchars($course['classroom']) ?>">
        </label><br>

        <label>授課教師: 
            <input type="text" name="teacher_name" value="<?= htmlspecialchars($course['teacher_name'] ?: $teacher_name) ?>">
        </label><br>

        <label>課程大綱: 
            <textarea name="syllabus"><?= htmlspecialchars($course['syllabus']) ?></textarea>
        </label><br>

        <input type="submit" value="儲存變更">
    </form>
</div>

<?php include '../common/footer.php'; ?>
