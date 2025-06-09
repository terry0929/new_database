<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo "<div class='page-content'><p>請先登入。</p></div>";
    include '../common/footer.php'; 
    exit;
}

// 查詢目前登入使用者的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$teacher_id = $result->fetch_assoc()['teacher_id'] ?? '';  // 若查無 teacher_id，預設為空字串

if (!$teacher_id) {
    echo "<div class='page-content'><p>無效的使用者資料。</p></div>";
    include '../common/footer.php';
    exit;
}

// 組合上課時間
if (isset($_POST['day'], $_POST['start_time'], $_POST['end_time'])) {
    $time = "星期" . $_POST['day'] . " 第" . $_POST['start_time'] . "節 ~ 第" . $_POST['end_time'] . "節";
} else {
    echo "<div class='page-content'><p>⚠️ 無法組合上課時間，請檢查輸入。</p></div>";
    include '../common/footer.php';
    exit;
}

// 插入課程資料
$stmt = $conn->prepare("
    INSERT INTO course (name, location, time, semester, credits, teacher_name, syllabus, teacher_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssisss",
    $_POST['name'],               // 課程名稱
    $_POST['location'],           // 地點
    $time,                        // 組合好的時間
    $_POST['semester'],           // 學期
    $_POST['credits'],            // 學分
    $_POST['teacher_name'],       // 授課教師
    $_POST['syllabus'],           // 課程大綱
    $teacher_id                   // 教師 ID
);

if ($stmt->execute()) {
    header("Location: /~D1285210/courses/my_courses.php");
    exit;
} else {
    echo "<div class='page-content'><p>❌ 儲存失敗：" . htmlspecialchars($stmt->error) . "</p></div>";
    include '../common/footer.php';
    exit;
}
?>
