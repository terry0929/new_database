<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='page-content'><p>⚠️ 找不到課程 ID。</p></div>";
    include '../common/footer.php';
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM course WHERE course_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='page-content'><p>⚠️ 查無此課程。</p></div>";
} else {
    $c = $result->fetch_assoc();
?>

<div class="page-content">
    <p><a href="/~D1285210/courses/list.php">🔙回課程列表</a></p>
    <h2>📘 課程詳細資料</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr><th>課堂代碼</th><td><?= htmlspecialchars($c['course_id']) ?></td></tr>
        <tr><th>課堂名稱</th><td><?= htmlspecialchars($c['name']) ?></td></tr>
        <tr><th>課堂地點</th><td><?= htmlspecialchars($c['location']) ?></td></tr>
        <tr><th>課堂時間</th><td><?= htmlspecialchars($c['time']) ?></td></tr>
        <tr><th>學期</th><td><?= htmlspecialchars($c['semester']) ?></td></tr>
        <tr><th>學分</th><td><?= htmlspecialchars($c['credits']) ?></td></tr>
        <tr><th>課堂教室</th><td><?= htmlspecialchars($c['classroom']) ?></td></tr>
        <tr><th>授課教師</th><td><?= htmlspecialchars($c['teacher_name']) ?></td></tr>
        <tr><th>大綱</th><td><?= nl2br(htmlspecialchars($c['syllabus'])) ?></td></tr>
    </table>
</div>

<?php
}
include '../common/footer.php';
?>
