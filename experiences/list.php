<?php
session_start();
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

$sql = "SELECT * FROM experience WHERE teacher_id = ? ORDER BY experience_id DESC";
$exp_stmt = $conn->prepare($sql);
$exp_stmt->bind_param("s", $teacher_id);
$exp_stmt->execute();
$result = $exp_stmt->get_result();
?>

<h2>📌 我的經歷</h2>
<a href="/~D1285210/experiences/form.php">➕ 新增經歷</a>

<table border="1" cellpadding="8">
    <tr>
        <th>編號</th><th>類別</th><th>內容</th><th>操作</th>
    </tr>
<?php while ($row = $result->fetch_assoc()): ?>
    <tr>
        <td><?= $row['experience_id'] ?></td>
        <td><?= $row['type'] == 'in' ? '校內' : '校外' ?></td>
        <td><?= htmlspecialchars($row['description']) ?></td>
        <td>
            <a href="/~D1285210/experiences/edit.php?id=<?= $row['experience_id'] ?>">✏️ 編輯</a> |
            <a href="/~D1285210/experiences/delete.php?id=<?= $row['experience_id'] ?>" onclick="return confirm('確定刪除？')">🗑 刪除</a>
        </td>
    </tr>
<?php endwhile; ?>
</table>

<?php include '../common/footer.php'; ?>
