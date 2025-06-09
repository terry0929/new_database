<?php include '../common/header.php'; ?>

<div class="page-content">
<h2>➕ 新增經歷</h2>

<form action="/~D1285210/experiences/save.php" method="post">
    <label><h3>類別:</h3><br>
        <select name="type" style="width:80%; padding:10px; font-size: 16px;">
            <option value="in">校內</option>
            <option value="out">校外</option>
        </select>
    </label><br><br>
    <label><h3>內容:</h3><br>
        <textarea name="description" rows="4" cols="50" style="width:80%; padding:10px; font-size: 16px;" required></textarea>
    </label><br>
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="submit" value="儲存" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
    </div>
</form>
</div>

<?php include '../common/footer.php'; ?>
