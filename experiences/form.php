<?php include '../common/header.php'; ?>

<div class="page-content">
<h2>➕ 新增經歷</h2>

<form action="/~D1285210/experiences/save.php" method="post">
    <label>類別:
        <select name="type">
            <option value="in">校內</option>
            <option value="out">校外</option>
        </select>
    </label><br>
    <label>內容:<br>
        <textarea name="description" rows="4" cols="50" required></textarea>
    </label><br>
    <input type="submit" value="儲存">
</form>
</div>

<?php include '../common/footer.php'; ?>
