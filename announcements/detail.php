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
    $photoPath = !empty($row['image']) ? "/~D1285210/uploads_ann/" . $row['image'] : null;
?>

<div class="page-content">
    <h2>📢 公告詳情</h2><br><br>
    <table border="1" cellpadding="8" cellspacing="0">
            <?php if ($photoPath): ?>
                <img src="<?= $photoPath ?>" alt="公告圖片" style="max-width: 50%; height: auto; margin: 0 auto;">
            <?php else: ?>
                無圖片
            <?php endif; ?>
        <br><br><h3>標題</h3><?= htmlspecialchars($row['title']) ?>
        <br><br><h3>發佈日期</h3><?= htmlspecialchars($row['post_date']) ?>
        <br><br><h3>發佈者</h3><?= htmlspecialchars($row['teacher_name']) ?>
        <br><br><h3>內容</h3><?= nl2br(htmlspecialchars($row['content'])) ?>
    </table>
</div>

<?php
}
include '../common/footer.php';
?>
