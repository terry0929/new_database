<?php
include '../common/db.php';
include '../common/header.php';

$course_id = $_GET['course_id'];

$allTeachers = $conn->query("SELECT * FROM teacher");
$linkResult = $conn->prepare("SELECT t.* FROM course_teacher ct JOIN teacher t ON ct.teacher_id = t.teacher_id WHERE ct.course_id = ?");
$linkResult->bind_param("s", $course_id);
$linkResult->execute();
$linkedTeachers = $linkResult->get_result();
?>

<h2>👨‍🏫 課程教師配對（<?= $course_id ?>）</h2>
<form action="/~D1285210/courses/link_save.php" method="post">
    <input type="hidden" name="course_id" value="<?= $course_id ?>">
    <select name="teacher_id">
        <?php while ($row = $allTeachers->fetch_assoc()): ?>
            <option value="<?= $row['teacher_id'] ?>"><?= $row['name'] ?></option>
        <?php endwhile; ?>
    </select>
    <input type="submit" value="配對">
</form>

<h3>已配對教師</h3>
<ul>
    <?php while ($t = $linkedTeachers->fetch_assoc()): ?>
        <li>
            <?= $t['name'] ?> (<?= $t['teacher_id'] ?>)
            <a href="/~D1285210/courses/link_delete.php?course_id=<?= $course_id ?>&teacher_id=<?= $t['teacher_id'] ?>">❌ 移除</a>
        </li>
    <?php endwhile; ?>
</ul>

<?php include '../common/footer.php'; ?>
