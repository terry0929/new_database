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
            <input type="text" name="teacher_name" value="<?= htmlspecialchars($course['teacher_name']) ?>">
        </label><br>

        <label>課程大綱: 
            <textarea name="syllabus"><?= htmlspecialchars($course['syllabus']) ?></textarea>
        </label><br>

        <input type="submit" value="新增課程">
    </form>
</div>

<?php include '../common/footer.php'; ?>
