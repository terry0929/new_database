<?php
include '../common/db.php';
include '../common/header.php';

$sql = "SELECT * FROM course ORDER BY course_id ASC";
$result = $conn->query($sql);
?>

<h2>📘 所有課程列表</h2>
<a href="/~D1285210/courses/form.php">➕ 新增課程</a>
<table border="1" cellpadding="8" cellspacing="0">
    <tr>
        <th>課程代碼</th>
        <th>課程名稱</th>
        <th>地點</th>
        <th>時間</th>
        <th>操作</th>
    </tr>

<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['course_id']) ?></td>
        <td><?= htmlspecialchars($row['name']) ?></td>
        <td><?= htmlspecialchars($row['location']) ?></td>
        <td><?= htmlspecialchars($row['time']) ?></td>
        <td>
            <a href="/~D1285210/courses/edit.php?id=<?= $row['course_id'] ?>">✏️ 編輯</a> |
            <a href="/~D1285210/courses/delete.php?id=<?= $row['course_id'] ?>" onclick="return confirm('確定刪除這門課嗎？')">🗑 刪除</a> |
            <a href="/~D1285210/courses/teacher_link.php?course_id=<?= $row['course_id'] ?>">👨‍🏫 配對教師</a>
        </td>
    </tr>
<?php endwhile; ?>
</table>

<?php include 'common/footer.php'; ?>
