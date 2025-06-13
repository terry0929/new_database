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
    <table border="1" cellpadding="8" cellspacing="0">
            <?php if ($photoPath): ?>
                <img src="<?= $photoPath ?>" alt="公告圖片">
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

<style>
    .page-content img {
    width: 30%; /* 限制寬度 */
    height: 30%; /* 限制高度 */
    object-fit: cover; /* 確保圖片以正方形顯示，裁剪超出部分 */
    border-radius: 8px; /* 圓角效果 */
    border: 2px solid #ccc; /* 增加邊框 */
  }
</style>
