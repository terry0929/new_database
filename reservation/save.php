<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
session_start();

if (!isset($_SESSION['user_id'])) {
    exit('請先登入');
}

// 取得教師資訊
$stmt = $conn->prepare("
    SELECT t.name, t.email, t.teacher_id
    FROM user_account u
    JOIN teacher t ON u.teacher_id = t.teacher_id
    WHERE u.user_id = ?
");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$name = $user['name'];
$email = $user['email'];
$teacher_id = $user['teacher_id'];

$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();
$teacher_id = $user['teacher_id'];
$name = $user['name'];
$email = $user['email'];

// 處理輸入時間
$date = $_POST['date'];
$start_time = $_POST['start_time'];
$duration = (int)$_POST['duration'];
$start_hour = (int)substr($start_time, 0, 2);
$end_hour = $start_hour + $duration;
if ($end_hour > 19) {
    exit('❌ 結束時間不可超過 19:00');
}
$end_time = str_pad($end_hour, 2, '0', STR_PAD_LEFT) . ':00';

// 檢查重疊預約
$stmt = $conn->prepare("SELECT * FROM reservation WHERE location = ? AND date = ? AND (
    (start_time < ? AND end_time > ?) OR
    (start_time < ? AND end_time > ?) OR
    (start_time >= ? AND start_time < ?)
)");
$stmt->bind_param("ssssssss", $_POST['location'], $date, $end_time, $start_time, $start_time, $end_time, $start_time, $end_time);
$stmt->execute();
$conflict = $stmt->get_result();
if ($result->num_rows > 0) {
    echo "<script>
        alert('❌ 此地點在這段時間已有預約，請重新選擇。');
        window.history.back();
    </script>";
    exit;
}

// 寫入預約紀錄
$stmt = $conn->prepare("INSERT INTO reservation (location, date, start_time, end_time, teacher_id, name, email)
VALUES (?, ?, ?, ?, ?, ?, ?)");
$stmt->bind_param("sssssss", $_POST['location'], $date, $start_time, $end_time, $teacher_id, $name, $email);
if ($stmt->execute()) {
    header("Location: /~D1285210/reservation/my_reservations.php");
    exit;
} else {
    echo "❌ 預約失敗: " . $stmt->error;
}
?>