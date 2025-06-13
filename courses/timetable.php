<?php
include '../common/db.php';
include '../common/header.php';

$teacher_id = '';

// 優先選擇下拉選單
if (!empty($_GET['teacher_select'])) {
    $teacher_id = $_GET['teacher_select'];
} elseif (!empty($_GET['teacher_search'])) {
    // 根據名字反查 ID（模糊搜尋）
    $stmt = $conn->prepare("SELECT teacher_id FROM teacher WHERE name LIKE CONCAT('%', ?, '%') LIMIT 1");
    $stmt->bind_param("s", $_GET['teacher_search']);
    $stmt->execute();
    $stmt->bind_result($teacher_id);
    $stmt->fetch();
    $stmt->close();
}

// 如果還是沒抓到 ID
if (empty($teacher_id)) {
    echo "<script>alert('查無此老師，請重新輸入'); history.back();</script>";
    include '../common/footer.php';
    exit;
}

// 取得老師名字
$stmt = $conn->prepare("SELECT name FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$stmt->bind_result($teacher_name);
$stmt->fetch();
$stmt->close();

// 初始化課表陣列
$days = ['一', '二', '三', '四', '五', '六', '日'];
$timetable = [];
foreach ($days as $d) {
    for ($i = 1; $i <= 14; $i++) {
        $timetable[$d][$i] = '-';
    }
}

function expandTimeString($raw) {
    $raw = str_replace(' ', '', $raw);
    $results = [];
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

// 取課程資料，改用 teacher_id 查詢
$stmt = $conn->prepare("SELECT course_id, name, time, location FROM course WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
while ($row = $result->fetch_assoc()) {
    $course_id = $row['course_id'];
    $course_name = htmlspecialchars($row['name']);
    $course_location = htmlspecialchars($row['location']);
    $course = "<a href='detail.php?id=$course_id'>$course_name</a><br><small>($course_location)</small>";
    $expanded = expandTimeString($row['time']);
    foreach ($expanded as $slot) {
        $day = mb_substr($slot, 0, 1);
        $period = (int)mb_substr($slot, 1);
        $timetable[$day][$period] = $course;
    }
}
$stmt->close();
?>

<div class="page-content">
  <h2>📚 <?= htmlspecialchars($teacher_name) ?> 的課表</h2>
  <table border="1" cellpadding="10" cellspacing="0" style="width: 100%; text-align: center;">
    <thead>
      <tr>
        <th>節次＼星期</th>
        <?php foreach ($days as $d) echo "<th>星期$d</th>"; ?>
      </tr>
    </thead>
    <tbody>
      <?php for ($i = 1; $i <= 14; $i++): ?>
        <tr>
          <td>第 <?= $i ?> 節</td>
          <?php foreach ($days as $d): ?>
            <td><?= $timetable[$d][$i] ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endfor; ?>
    </tbody>
  </table>
</div>

<?php include '../common/footer.php'; ?>
