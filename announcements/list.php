<?php
include '../common/db.php';
include '../common/header.php';

$sql = "SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        ORDER BY a.post_date DESC";
$result = $conn->query($sql);
?>

<div class="page-content">
    <h2>📢 最新公告</h2>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>標題</th>
            <th>分類</th>
            <th>發佈日期</th>
            <th>詳細資料</th>
        </tr>

        <?php while($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['post_date']) ?></td>
            <td>
                <a href="/~D1285210/announcements/detail.php?id=<?= $row['announcement_id'] ?>">🔍 查看</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>
