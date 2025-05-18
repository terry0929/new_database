<?php
include '../common/db.php';
include '../common/header.php';

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM course WHERE course_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<h2>✏️ 編輯課程</h2>
<form action="/~D1285210/courses/update.php" method="post">
    <input type="hidden" name="course_id" value="<?= $row['course_id'] ?>">
    <label>課程名稱: <input type="text" name="name" value="<?= $row['name'] ?>"></label><br>
    <label>地點: <input type="text" name="location" value="<?= $row['location'] ?>"></label><br>
    <label>時間: <input type="text" name="time" value="<?= $row['time'] ?>"></label><br>
    <label>學期: <input type="text" name="semester" value="<?= $row['semester'] ?>"></label><br>
    <label>學分數: <input type="number" name="credits" value="<?= $row['credits'] ?>"></label><br>
    <label>教室: <input type="text" name="classroom" value="<?= $row['classroom'] ?>"></label><br>
    <label>教師名稱: <input type="text" name="teacher_name" value="<?= $row['teacher_name'] ?>"></label><br>
    <label>課程大綱: <textarea name="syllabus"><?= $row['syllabus'] ?></textarea></label><br>
    <input type="submit" value="更新課程">
</form>

<?php include '../common/footer.php'; ?>
