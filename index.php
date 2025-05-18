<?php
include 'common/db.php';
include 'common/header.php';
?>

<h2>🎓 歡迎來到教師資訊系統</h2>
<p>這是一個整合公告、教師、課程、經歷與研究的系統。</p>

<?php if (!isset($_SESSION['user_id'])): ?>
    <p>請 <a href="/~D1285210/login.php">登入</a> 後使用控制台功能。</p>
<?php else: ?>
    <p>您已登入，請進入 <a href="/~D1285210/edit_mode.php">控制台</a> 開始管理您的資料。</p>
<?php endif; ?>

<?php include 'common/footer.php'; ?>
