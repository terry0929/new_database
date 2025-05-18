<?php include '../common/header.php'; ?>
<h2>➕ 新增研究成果</h2>

<form action="research/save.php" method="post">
    <label>標題: <input type="text" name="title" required></label><br>
    <label>主要類型: <input type="text" name="type1"></label><br>
    <label>次要類型: <input type="text" name="type2"></label><br>
    <label>發表時間: <input type="date" name="publish_date"></label><br>
    <label>發表年份: <input type="number" name="year"></label><br>
    <label>關鍵字: <input type="text" name="keywords"></label><br>
    <label>附件檔名: <input type="text" name="attachment"></label><br>
    <label>作者: <input type="text" name="author"></label><br>
    <input type="submit" value="儲存">
</form>

<?php include '../common/footer.php'; ?>
