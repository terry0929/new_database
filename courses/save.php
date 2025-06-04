<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_SESSION['user_id'])) {
    echo "<div class='page-content'><p>請先登入。</p></div>";
    include '../common/footer.php'; exit;
}

// 查詢目前登入使用者的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

// 組合上課時間
$time = "星期" . $_POST['day'] . " " . $_POST['start_time'] . " 到 " . $_POST['end_time'];

// 插入課程資料
$stmt = $conn->prepare("
    INSERT INTO course (name, location, time, semester, credits, classroom, teacher_name, syllabus, teacher_id)
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)
");

$stmt->bind_param(
    "ssssissss",
    $_POST['name'],
    $_POST['location'],
    $time,                        // 組合好的時間
    $_POST['semester'],
    $_POST['credits'],
    $_POST['classroom'],
    $_POST['teacher_name'],
    $_POST['syllabus'],
    $teacher_id
);

if ($stmt->execute()) {
    header("Location: /~D1285210/courses/my_courses.php");
    exit;
} else {
    echo "❌ 儲存失敗：" . $stmt->error;
}
?>
