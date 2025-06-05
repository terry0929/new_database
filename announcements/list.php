<?php
include '../common/db.php';
include '../common/header.php';

$keyword = $_GET['q'] ?? '';

if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        WHERE a.title LIKE CONCAT('%', ?, '%')
           OR a.category LIKE CONCAT('%', ?, '%')
           OR a.content LIKE CONCAT('%', ?, '%')
        ORDER BY a.post_date DESC
    ");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        ORDER BY a.post_date DESC
    ");
}
?>

<div class="page-content">
    <h2>📢 最新公告</h2>

    <form method="get" action="" class="search-form">
        🔍 關鍵字搜尋（標題、分類、內容）：<br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" >
        <input type="submit" value="搜尋">
    </form>

    <?php if ($result->num_rows === 0): ?>
        <div class="empty-message">
            ❗ 沒有找到符合「<?= htmlspecialchars($keyword) ?>」的公告。
        </div>
    <?php else: ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>標題</th>
                    <th>分類</th>
                    <th>發佈日期</th>
                    <th>詳細資料</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['category']) ?></td>
                    <td><?= htmlspecialchars($row['post_date']) ?></td>
                    <td>
                        <a href="/~D1285210/announcements/detail.php?id=<?= $row['announcement_id'] ?>">🔍 查看</a>
                    </td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../common/footer.php'; ?>
