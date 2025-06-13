<?php
session_start();
include('common/db.php');
include('common/header.php');


$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE teacher_id = ?");
$stmt->bind_param("s", $_SESSION['teacher_id']);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();
$tid = $teacher['teacher_id'] ?? '未知';

if (!isset($_SESSION['teacher_id']) || !preg_match('/^T\d+$/', $_SESSION['teacher_id'])) {
    echo '<div class="page-content"><p>⚠️ 您沒有權限進入此頁面（僅限教師）</p></div>';
    include('common/footer.php');
    exit;
}

?>

<div class="page-content">
    <h2>🛠 編輯控制台（教師編號：<?= htmlspecialchars($tid) ?>）</h2>

    <div class="dashboard-wrapper">
        <div class="dashboard-box">
            <h3>👨‍🏫 教師區塊</h3>
            <ul>
                <li><a href="/~D1285210/teachers/edit.php">✏️ 編輯個人基本資料</a></li>
                <li><a href="/~D1285210/experiences/list.php">📚 管理經歷</a></li>
                <li><a href="/~D1285210/research/list.php">🧪 管理研究成果</a></li>
            </ul>
        </div>

        <div class="dashboard-box">
            <h3>📢 公告區塊</h3>
            <ul>
                <li><a href="/~D1285210/announcements/manage.php">📢 管理公告</a></li>
            </ul>
        </div>

        <div class="dashboard-box">
            <h3>📘 課程區塊</h3>
            <ul>
                <li><a href="/~D1285210/courses/my_courses.php">📘 管理課程</a></li>
            </ul>
        </div>

        <div class="dashboard-box">
            <h3>📅 空間預約區塊</h3>
            <ul>
                <li><a href="/~D1285210/reservation/manage.php">📅 空間預約</a></li>
            </ul>
        </div>

    </div>
</div>

<?php include('common/footer.php'); ?>
