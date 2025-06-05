<?php
include '../common/db.php';

// 組合上課時間（例：星期三第9節 ~ 第12節）
$time = "星期" . $_POST['day'] . " 第" . $_POST['start_time'] . "節 ~ 第" . $_POST['end_time'] . "節";

$stmt = $conn->prepare("UPDATE course SET
    name=?, location=?, time=?, semester=?, credits=?, classroom=?, teacher_name=?, syllabus=?
    WHERE course_id=?");

$stmt->bind_param("ssssisssi",
    $_POST['name'],
    $_POST['location'],
    $time,
    $_POST['semester'],
    $_POST['credits'],
    $_POST['classroom'],
    $_POST['teacher_name'],
    $_POST['syllabus'],
    $_POST['course_id']
);

if ($stmt->execute()) {
    header("Location: /~D1285210/courses/my_courses.php");
    exit;
} else {
    echo "❌ 更新失敗：" . $stmt->error;
}
?>
