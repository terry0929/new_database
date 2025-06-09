<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

// 確認接收到的 POST 資料是否有效
if (!isset($_POST['day'], $_POST['start_time'], $_POST['end_time'], $_POST['name'], $_POST['location'], $_POST['semester'], $_POST['credits'], $_POST['teacher_name'], $_POST['syllabus'], $_POST['course_id'])) {
    echo "❌ 請填寫所有必要的資料。";
    exit;
}

// 組合上課時間（例：星期三第9節 ~ 第12節）
$time = "星期" . $_POST['day'] . " 第" . $_POST['start_time'] . "節 ~ 第" . $_POST['end_time'] . "節";

// 使用預處理語句更新課程資料
$stmt = $conn->prepare("UPDATE course SET
    name = ?, location = ?, time = ?, semester = ?, credits = ?, teacher_name = ?, syllabus = ?
    WHERE course_id = ?");

if ($stmt === false) {
    die("❌ SQL 語句準備失敗：" . $conn->error);
}

// 綁定參數
$stmt->bind_param("sssssssi",
    $_POST['name'],
    $_POST['location'],
    $time,
    $_POST['semester'],
    $_POST['credits'],
    $_POST['teacher_name'],
    $_POST['syllabus'],
    $_POST['course_id']
);

// 執行更新並檢查是否成功
if ($stmt->execute()) {
    header("Location: /~D1285210/courses/my_courses.php");
    exit;
} else {
    // 顯示錯誤訊息
    echo "❌ 更新失敗：" . $stmt->error;
}
?>
