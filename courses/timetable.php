<?php
include '../common/db.php';
include '../common/header.php';

$teacher_name = '';

if (!empty($_GET['teacher_select'])) {
    $teacher_name = $_GET['teacher_select'];
} elseif (!empty($_GET['teacher_search'])) {
    $teacher_name = $_GET['teacher_search'];
}

// 查詢資料庫確認老師是否存在
$check = $conn->prepare("SELECT COUNT(*) FROM course WHERE teacher_name = ?");
$check->bind_param("s", $teacher_name);
$check->execute();
$check->bind_result($count);
$check->fetch();
$check->close();

if ($count == 0) {
    echo "<script>alert('查無此老師，請重新輸入'); history.back();</script>";
    include '../common/footer.php';
    exit;
}


if (empty($teacher_name)) {
    echo "<div class='page-content'><p>⚠️ 請選擇或輸入老師名稱。</p></div>";
    include '../common/footer.php';
    exit;
}


// 初始化課表陣列
$days = ['一', '二', '三', '四', '五', '六', '日'];
$timetable = [];
foreach ($days as $d) {
    for ($i = 1; $i <= 14; $i++) {
        $timetable[$d][$i] = '-';
    }
}

function expandTimeString($raw) {
    // ✅ 正確解析：星期一 第1節 ~ 第4節
    $raw = str_replace(' ', '', $raw); // 移除所有空白
    $results = [];

    // 支援多段時間：例如「星期一第1節~第4節;星期三第2節~第3節」
    foreach (explode(';', $raw) as $segment) {
        if (preg_match('/星期([一二三四五六日])第(\d+)節~第(\d+)節/u', $segment, $match)) {
            $day = $match[1];
            $start = (int)$match[2];
            $end = (int)$match[3];
            for ($i = $start; $i <= $end; $i++) {
                $results[] = $day . $i;
            }
        }
    }

    return $results;
}



// 取資料並填入課表
$stmt = $conn->prepare("SELECT course_id, name, time, location FROM course WHERE teacher_name = ?");
$stmt->bind_param("s", $teacher_name);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $course_id = $row['course_id'];
    $course_name = htmlspecialchars($row['name']);
    $course_location = htmlspecialchars($row['location']);
    $course = "<a href='detail.php?id=$course_id'>$course_name</a><br><small>($course_location)</small>";
    $expanded = expandTimeString($row['time']);
    foreach ($expanded as $slot) {
        if (preg_match('/([一二三四五六日])(\d+)/u', $slot, $match)) {
            $day = $match[1];
            $period = (int)$match[2];
            $timetable[$day][$period] = $course;
        }
    }
}
?>

<div class="page-content">
  <h2>🟦 <?= htmlspecialchars($teacher_name) ?> 的課表</h2>
  <table border="1" cellpadding="8" cellspacing="0">
    <tr>
      <th>節次</th>
      <?php foreach ($days as $d): ?>
        <th>星期<?= $d ?></th>
      <?php endforeach; ?>
    </tr>
    <?php
    $periodTimes = [
      1 => '08:10-09:00', 2 => '09:10-10:00', 3 => '10:10-11:00', 4 => '11:10-12:00',
      5 => '12:10-13:00', 6 => '13:10-14:00', 7 => '14:10-15:00', 8 => '15:10-16:00',
      9 => '16:10-17:00', 10 => '17:10-18:00', 11 => '18:30-19:20', 12 => '19:25-20:15',
      13 => '20:25-21:15', 14 => '21:20-22:10'
    ];
    foreach ($periodTimes as $p => $timeLabel):
    ?>
    <tr <?= $p % 2 == 0 ? 'style="background:#f9f9f9"' : '' ?>>
      <td>第<?= $p ?>節<br><?= $timeLabel ?></td>
      <?php foreach ($days as $d): ?>
        <td><?= $timetable[$d][$p] ?></td>
      <?php endforeach; ?>
    </tr>
    <?php endforeach; ?>
  </table>
</div>

<?php include '../common/footer.php'; ?>
