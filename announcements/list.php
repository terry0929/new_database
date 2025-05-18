<?php
include '../common/db.php';
include '../common/header.php';

$sql = "SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        ORDER BY a.post_date DESC";
$result = $conn->query($sql);
?>

<h2>📢 最新公告</h2>
<a href="/~D1285210/announcements/form.php">➕ 新增公告</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>ID</th>
        <th>標題</th>
        <th>分類</th>
        <th>發佈人</th>
        <th>單位</th>
        <th>日期</th>
        <th>操作</th>
    </tr>

<?php while($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['announcement_id']) ?></td>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= htmlspecialchars($row['category']) ?></td>
        <td><?= htmlspecialchars($row['poster_name']) ?></td>
        <td><?= htmlspecialchars($row['poster_unit']) ?></td>
        <td><?= htmlspecialchars($row['post_date']) ?></td>
        <td>
            <a href="/~D1285210/announcements/edit.php?id=<?= $row['announcement_id'] ?>">✏️ 編輯</a> |
            <a href="/~D1285210/announcements/delete.php?id=<?= $row['announcement_id'] ?>" onclick="return confirm('確定要刪除嗎？')">🗑 刪除</a>
        </td>
    </tr>
<?php endwhile; ?>

</table>

<?php include '../common/footer.php'; ?>
