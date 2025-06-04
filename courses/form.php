<?php
include '../common/db.php';
include '../common/header.php';

// 初始化
$teacher_id = '';
$teacher_name = '';

// 抓登入者的 teacher_id 與名字
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
?>

<div class="page-content">
    <h2>➕ 新增課程</h2>
    <form action="/~D1285210/courses/save.php" method="post">
        <label>課程名稱: <input type="text" name="name" required></label><br>
        <label>課堂地點: <input type="text" name="location"></label><br>
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
        <label>學期: <input type="text" name="semester"></label><br>
        <label>學分數: <input type="number" name="credits"></label><br>
        <label>課堂教室: <input type="text" name="classroom"></label><br>

        <!-- 自動填入授課教師 -->
        <input type="hidden" name="teacher_id" value="<?= htmlspecialchars($teacher_id) ?>">
        <label>授課教師: 
            <input type="text" name="teacher_name" value="<?= htmlspecialchars($teacher_name) ?>" readonly>
        </label><br>

        <label>課程大綱: <textarea name="syllabus"></textarea></label><br>
        <input type="submit" value="新增課程">
    </form>
</div>

<?php include '../common/footer.php'; ?>
