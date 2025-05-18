<?php include '../common/header.php'; ?>

<h2>➕ 新增課程</h2>
<form action="/~D1285210/courses/save.php" method="post">
    <label>課程代碼: <input type="text" name="course_id" required></label><br>
    <label>課程名稱: <input type="text" name="name"></label><br>
    <label>地點: <input type="text" name="location"></label><br>
    <label>時間: <input type="text" name="time"></label><br>
    <label>學期: <input type="text" name="semester"></label><br>
    <label>學分數: <input type="number" name="credits"></label><br>
    <label>教室: <input type="text" name="classroom"></label><br>
    <label>教師名稱: <input type="text" name="teacher_name"></label><br>
    <label>課程大綱: <textarea name="syllabus"></textarea></label><br>
    <input type="submit" value="新增課程">
</form>

<?php include '../common/footer.php'; ?>
