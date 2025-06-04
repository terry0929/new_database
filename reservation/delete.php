<?php
include '../common/db.php';

if (!isset($_GET['id'])) {
    exit("❌ 缺少預約 ID");
}

$id = $_GET['id'];


$stmt = $conn->prepare("DELETE FROM reservation WHERE reservation_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();

if ($stmt->affected_rows > 0) {
    echo "<div class='page-content'><p>✅ 預約已成功刪除。</p></div>";
} else {
    echo "<div class='page-content'><p>❌ 刪除失敗，請檢查預約 ID 是否正確。</p></div>";
}

header("Location: /~D1285210/reservation/my_reservations.php");
exit;
?>
