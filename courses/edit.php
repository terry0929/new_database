<?php
include '../common/db.php';
include '../common/header.php';

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
?>

<div class="page-content">
    <h2>✏️ 編輯課程</h2>
    <form action="/~D1285210/courses/update.php" method="post">
        <input type="hidden" name="course_id" value="<?= $course['course_id'] ?>">
        <label>課程名稱: <input type="text" name="name" value="<?= htmlspecialchars($course['name']) ?>"></label><br>
        <label>課堂地點: <input type="text" name="location" value="<?= htmlspecialchars($course['location']) ?>"></label><br>
        <label>上課時間: 
            <select name="day">
                <option value="一">星期一</option>
                <option value="二">星期二</option>
                <option value="三">星期三</option>
                <option value="四">星期四</option>
                <option value="五">星期五</option>
                <option value="六">星期六</option>
                <option value="日">星期日</option>
            </select>
            <input type="time" name="start_time"> 到
            <input type="time" name="end_time">
        </label><br>

        <label>學期: <input type="text" name="semester" value="<?= htmlspecialchars($course['semester']) ?>"></label><br>
        <label>學分數: <input type="number" name="credits" value="<?= $course['credits'] ?>"></label><br>
        <label>課堂教室: <input type="text" name="classroom" value="<?= htmlspecialchars($course['classroom']) ?>"></label><br>
        <label>授課教師: <input type="text" name="teacher_name" value="<?= htmlspecialchars($course['teacher_name']) ?>"></label><br>
        <label>課程大綱: <textarea name="syllabus"><?= htmlspecialchars($course['syllabus']) ?></textarea></label><br>
        <input type="submit" value="儲存變更">
    </form>
</div>

<?php include '../common/footer.php'; ?>
