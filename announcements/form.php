<?php include '../common/header.php'; ?>

<h2>➕ 新增公告</h2>
<form action="/~D1285210/announcements/save.php" method="post">
    <label>標題: <input type="text" name="title" required></label><br>
    <label>內容: <textarea name="content" required></textarea></label><br>
    <label>分類: <input type="text" name="category"></label><br>
    <label>瀏覽次數: <input type="number" name="view_count" value="0"></label><br>
    <label>發佈日期: <input type="datetime-local" name="post_date"></label><br>
    <label>發佈人: <input type="text" name="poster_name"></label><br>
    <label>發佈單位: <input type="text" name="poster_unit"></label><br>
    <label>教師 ID: <input type="text" name="teacher_id"></label><br>
    <input type="submit" value="儲存">
</form>

<?php include '../common/footer.php'; ?>
