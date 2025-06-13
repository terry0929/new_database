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
  <h2>🛠️ 編輯控制台（教師編號：<?= $_SESSION['user_id'] ?>）</h2>

  <!-- 👨‍🏫 教師區塊 -->
  <h3 class="section-title">👨‍🏫 教師區塊</h3>
  <div class="dashboard-grid">
    <a href="teachers/edit.php" class="dashboard-card">✏️ 編輯個人基本資料</a>
    <a href="experiences/list.php" class="dashboard-card">📚 管理經歷</a>
    <a href="research/list.php" class="dashboard-card">🧪 管理研究成果</a>
  </div>

  <!-- 📦 其他功能 -->
    <h3 class="section-title">📦 其他功能區塊</h3>
    <div class="dashboard-grid">
        <a href="/~D1285210/announcements/manage.php" class="dashboard-card card-announcement">📢 管理公告</a>
        <a href="/~D1285210/courses/my_courses.php" class="dashboard-card card-course">📘 管理課程</a>
        <a href="/~D1285210/reservation/my_reservations.php" class="dashboard-card card-reservation">📅 空間預約</a>
    </div>
</div>

<?php include('common/footer.php'); ?>
