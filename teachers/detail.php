<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<p>⚠️ 未指定教師 ID</p>";
    include '../common/footer.php';
    exit;
}

$id = $_GET['id'];

// 抓教師基本資料
$stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if (!$teacher) {
    echo "<p>⚠️ 找不到這位教師</p>";
    include '../common/footer.php';
    exit;
}

// 處理圖片路徑
$photoPath = $teacher['photo']
    ? "/~D1285210/uploads/" . $teacher['photo']
    : "/~D1285210/common/default_avatar.png";
?>

<h2>👨‍🏫 教師介紹 - <?= htmlspecialchars($teacher['name']) ?></h2>

<img src="<?= $photoPath ?>" alt="大頭照" width="150" style="border-radius: 8px; border: 1px solid #ccc;"><br><br>

<ul>
    <li><strong>信箱：</strong><?= htmlspecialchars($teacher['email']) ?></li>
    <li><strong>電話：</strong><?= htmlspecialchars($teacher['phone']) ?></li>
    <li><strong>職稱：</strong><?= htmlspecialchars($teacher['title']) ?></li>
    <li><strong>學歷：</strong><?= htmlspecialchars($teacher['education']) ?></li>
    <li><strong>研究領域：</strong><?= htmlspecialchars($teacher['research_field']) ?></li>
</ul>

<hr>

<h3>📚 經歷</h3>
<ul>
<?php
$exp = $conn->prepare("SELECT * FROM experience WHERE teacher_id = ?");
$exp->bind_param("s", $id);
$exp->execute();
$exp_result = $exp->get_result();
while ($e = $exp_result->fetch_assoc()):
?>
    <li><?= $e['type'] === 'in' ? '校內' : '校外' ?>：<?= htmlspecialchars($e['description']) ?></li>
<?php endwhile; ?>
</ul>

<h3>📘 課程</h3>
<ul>
<?php
$course = $conn->prepare("SELECT c.* FROM course_teacher ct JOIN course c ON ct.course_id = c.course_id WHERE ct.teacher_id = ?");
$course->bind_param("s", $id);
$course->execute();
$course_result = $course->get_result();
while ($c = $course_result->fetch_assoc()):
?>
    <li><?= htmlspecialchars($c['name']) ?>（<?= htmlspecialchars($c['semester']) ?>）</li>
<?php endwhile; ?>
</ul>

<?php include '../common/footer.php'; ?>
