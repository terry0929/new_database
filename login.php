<?php 
session_start();
include 'common/header.php'; ?>

<div class="page-content">
<h2>🔐 登入系統</h2>
<form action="login_check.php" method="post">
    <label>帳號：<input type="text" name="username" required></label><br>
    <label>密碼：<input type="password" name="password" required></label><br>
    <input type="submit" value="登入">
</form>
</div>

<?php include 'common/footer.php'; ?>
