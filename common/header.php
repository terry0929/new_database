<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>

<!DOCTYPE html>
<html lang="zh-Hant">
<head>
    <link rel="stylesheet" href="/~D1285210/common/style.css">
    <meta charset="UTF-8">
    <title>教師系統</title>
    <style>
        * {
            margin: 0; padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: "微軟正黑體", sans-serif;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background: #eee;
            padding: 10px;
            border-bottom: 1px solid #ccc;
        }
        main {
            flex: 1;
            padding: 20px;
        }
        footer {
            background: #f2f2f2;
            text-align: center;
            padding: 10px;
            font-size: 14px;
            border-top: 1px solid #ccc;
        }
        nav a {
            margin-right: 12px;
            text-decoration: none;
            color: purple;
            font-weight: bold;
        }
    </style>
</head>
<body>
<header>
    <div class="logo">👨‍🏫 教師資訊系統</div>
    <nav>
        <a href="/~D1285210/index.php">🏠 首頁</a>
        <a href="/~D1285210/teachers/list.php">👨‍🏫 教師</a>
        <a href="/~D1285210/announcements/list.php">📢 公告</a>
        <a href="/~D1285210/courses/list.php">📘 課程</a>
        <a href="/~D1285210/reservation/list.php">📅 空間預約</a>
        <a href="/~D1285210/edit_mode.php">🛠 控制台</a>
        <?php if (isset($_SESSION['user_id'])): ?>
            <a href="/~D1285210/logout.php">🚪 登出</a>
        <?php else: ?>
            <a href="/~D1285210/login.php">🔐 登入</a>
        <?php endif; ?>
    </nav>
</header>

<main>
