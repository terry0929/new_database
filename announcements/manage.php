<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p class="page-content">請先登入。</p>';
    include '../common/footer.php'; exit;
}

// 查詢目前登入者的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$teacher_id = $result->fetch_assoc()['teacher_id'] ?? '';

if (!$teacher_id) {
    echo '<p class="page-content">⚠️ 查無教師資料</p>';
    include '../common/footer.php'; exit;
}

// 查詢該老師的公告
$stmt = $conn->prepare("SELECT * FROM announcement WHERE teacher_id = ? ORDER BY post_date DESC");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$announcements = $stmt->get_result();
?>

<div class="page-content">
    <h2>📢 我的公告</h2>
    <a href="/~D1285210/announcements/form.php">➕ 新增公告</a>
    <table border="1" cellpadding="8" cellspacing="0">
        <tr>
            <th>標題</th>
            <th>分類</th>
            <th>發佈日期</th>
            <th>詳細資料</th>
            <th>操作</th>
        </tr>

        <?php while($row = $announcements->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><?= htmlspecialchars($row['category']) ?></td>
            <td><?= htmlspecialchars($row['post_date']) ?></td>
            <td>
                <a href="/~D1285210/announcements/detail.php?id=<?= $row['announcement_id'] ?>">🔍 查看</a>
            </td>
            <td>
                <a href="/~D1285210/announcements/edit.php?id=<?= $row['announcement_id'] ?>">✏️ 編輯</a>
                <a href="/~D1285210/announcements/delete.php?id=<?= $row['announcement_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>
