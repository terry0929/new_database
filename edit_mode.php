<?php
session_start();
include('common/db.php');
include('common/header.php');

if (!isset($_SESSION['user_id'])) {
    echo '<div class="page-content"><p>請先登入。</p></div>';
    include('common/footer.php');
    exit;
}

// 找出教師ID
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();
$tid = $teacher['teacher_id'] ?? '未知';
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
                <li><a href="/~D1285210/reservation/my_reservations.php">📅 空間預約</a></li>
            </ul>
        </div>
    </div>
</div>

<?php include('common/footer.php'); ?>
