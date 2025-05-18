<?php
include('../common/db.php');
include('../common/header.php');
session_start();

if (!isset($_SESSION['user_id'])) {
    echo "<p>請先登入。</p>";
    include 'common/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

$sql = "SELECT r.* FROM teacher_research tr
        JOIN research_result r ON tr.result_id = r.result_id
        WHERE tr.teacher_id = ?
        ORDER BY r.publish_date DESC";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>

<h2>🧪 我的研究成果</h2>
<a href="/~D1285210/research/form.php">➕ 新增成果</a>

<table border="1" cellpadding="8">
    <tr>
        <th>標題</th><th>類型</th><th>年份</th><th>操作</th>
    </tr>
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= htmlspecialchars($row['title']) ?></td>
        <td><?= $row['type1'] ?> / <?= $row['type2'] ?></td>
        <td><?= $row['year'] ?></td>
        <td>
            <a href="/~D1285210/research/edit.php?id=<?= $row['result_id'] ?>">✏️ 編輯</a> |
            <a href="/~D1285210/research/delete.php?id=<?= $row['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑 刪除</a>
        </td>
    </tr>
<?php endwhile; ?>
</table>

<?php include '../common/footer.php'; ?>
