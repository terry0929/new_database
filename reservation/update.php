<?php
include '../common/db.php';

$reservation_id = $_POST['reservation_id'];
$location = $_POST['location'];
$date = $_POST['date'];
$start_time = $_POST['start_time'];
$duration = (int)$_POST['duration']; // 新欄位：借用幾小時
$start_hour = (int)substr($start_time, 0, 2);
$end_hour = $start_hour + $duration;
if ($end_hour > 19) {
    exit('❌ 結束時間不可超過 19:00');
}
$end_time = str_pad($end_hour, 2, '0', STR_PAD_LEFT) . ':00';


// 防止重疊預約（排除自己這筆）
$check = $conn->prepare("
    SELECT * FROM reservation 
    WHERE location = ? AND date = ? AND reservation_id != ?
    AND (
        (start_time < ? AND end_time > ?) OR
        (start_time < ? AND end_time > ?) OR
        (start_time >= ? AND end_time <= ?)
    )
");
$check->bind_param("sssssssss",
    $location, $date, $reservation_id,
    $end_time, $end_time,
    $start_time, $start_time,
    $start_time, $end_time
);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "<script>
        alert('❌ 此地點在這段時間已有預約，請重新選擇。');
        window.history.back();
    </script>";
    exit;
}

// 執行更新
$stmt = $conn->prepare("UPDATE reservation 
    SET location = ?, date = ?, start_time = ?, end_time = ?
    WHERE reservation_id = ?");
$stmt->bind_param("sssss", $location, $date, $start_time, $end_time, $reservation_id);
$stmt->execute();

header("Location: /~D1285210/reservation/my_reservations.php");
exit;
?>
