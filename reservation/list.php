
<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';

// 預設日期是今天
$date = $_GET['date'] ?? date('Y-m-d');

// 所有時間格子（8:00~20:00）
$hours = range(8, 20);

// 空間清單（可以從資料庫撈，這裡寫死）
$rooms = [
  '人104(8人)', '人105(8人)', '人205(8人)', '人206(8人)', '人208(6人)',
  '人B101A(16人)', '人B102A(16人)', '人B103A(12人)', '人B104A(8人)',
  '人B105A(8人)', '人B113A(8人)', '人B114A(8人)',
  '圖視聽小間303(5人)', '圖視聽小間304(5人)', '圖視聽小間305(5人)',
  '討論室320(5人)', '討論室321(5人)'
];

// 改為同時撈取預約人姓名與 email
$stmt = $conn->prepare("
    SELECT r.location, r.start_time, r.end_time, t.name, t.email
    FROM reservation r
    JOIN teacher t ON r.teacher_id = t.teacher_id
    JOIN user_account u ON u.teacher_id = t.teacher_id
    WHERE r.date = ?
");
$stmt->bind_param("s", $date);
$stmt->execute();
$reserved = $stmt->get_result();

$reserved_map = [];
while ($row = $reserved->fetch_assoc()) {
    if (isset($row['start_time']) && isset($row['end_time'])) {
        $location = $row['location'];
        $start = (int)date('G', strtotime($row['start_time']));
        $end = (int)date('G', strtotime($row['end_time']));
        $name = $row['name'];
        $email = $row['email'];

        for ($h = $start; $h < $end; $h++) {
            $reserved_map[$location][$h] = [
                'reserved' => true,
                'name' => $name,
                'email' => $email
            ];
        }
    }
}
?>

<div class="page-content">
  <h2>📅 選擇日期查看可預約時段</h2>
  <form method="GET" style="margin-bottom: 20px;">
    <label for="date">借用日期：</label>
    <input type="date" name="date" value="<?= htmlspecialchars($date) ?>" required>
    <button type="submit">查詢</button>
  </form>

  <p>✅：可借用　｜　❌：已借出或不開放（點擊查看預約者）</p>

  <table border="1" cellpadding="6" cellspacing="0">
    <tr style="background:#ddd;">
      <th>開始時間＼地點</th>
      <?php foreach ($hours as $h): ?>
        <th><?= $h ?>:00</th>
      <?php endforeach; ?>
    </tr>

    <?php foreach ($rooms as $room): ?>
      <tr>
        <th style="background:#3A80C1; color:white;"><?= $room ?></th>
        <?php foreach ($hours as $h): ?>
          <td style="text-align: center;">
            <?php if (!isset($reserved_map[$room][$h])): ?>
              <span title="可借用" style="color: green;">✅</span>
            <?php else:
              $info = $reserved_map[$room][$h];
              $js_name = htmlspecialchars($info['name'], ENT_QUOTES);
              $js_email = htmlspecialchars($info['email'], ENT_QUOTES);
            ?>
              <span style="color: red; cursor: pointer;"
                    onclick="alert('此時段已由 <?= $js_name ?> 預約\nEmail：<?= $js_email ?>');"
                    title="點擊查看預約者資訊">❌</span>
            <?php endif; ?>
          </td>
        <?php endforeach; ?>
      </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php include '../common/footer.php'; ?>
