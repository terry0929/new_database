<?php
session_start();
include('common/db.php');
include('common/header.php');

if (!isset($_SESSION['user_id'])) {
    echo "<p>請先登入。</p>";
    include('common/footer.php'); // ← 修這裡！
    exit;
}

// 找到該帳號對應的教師資料
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$teacher = $result->fetch_assoc();

if (!$teacher) {
    echo "<p>⚠️ 找不到對應的教師資料</p>";
    include('common/footer.php'); // ← 修這裡！
    exit;
}

$tid = $teacher['teacher_id'];
?>

<h2>🛠 編輯控制台（教師編號：<?= htmlspecialchars($tid) ?>）</h2>
<ul>
    <li><a href="/~D1285210/teachers/edit.php">✏️ 編輯個人基本資料</a></li>
    <li><a href="/~D1285210/experiences/list.php">📚 管理經歷</a></li>
    <li><a href="/~D1285210/research/list.php">🧪 管理研究成果</a></li>
    <li><a href="/~D1285210/courses/list.php">📘 管理課程</a></li>
</ul>

<?php include('common/footer.php'); ?>
