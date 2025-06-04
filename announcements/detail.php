<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='page-content'><p>⚠️ 找不到公告 ID。</p></div>";
    include '../common/footer.php';
    exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT a.*, t.name AS teacher_name 
                        FROM announcement a 
                        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id 
                        WHERE a.announcement_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "<div class='page-content'><p>⚠️ 查無此公告。</p></div>";
} else {
    $row = $result->fetch_assoc();
?>

<div class="page-content">
    <p><a href="/~D1285210/announcements/list.php">🔙回公告列表</a></p>
    <h2>📢 公告詳情</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr><th>標題</th><td><?= htmlspecialchars($row['title']) ?></td></tr>
        <tr><th>分類</th><td><?= htmlspecialchars($row['category']) ?></td></tr>
        <tr><th>發佈人</th><td><?= htmlspecialchars($row['poster_name']) ?></td></tr>
        <tr><th>發佈日期</th><td><?= htmlspecialchars($row['post_date']) ?></td></tr>
        <tr><th>內容</th><td><?= nl2br(htmlspecialchars($row['content'])) ?></td></tr>
    </table>
</div>

<?php
}
include '../common/footer.php';
?>
