<?php
include('../common/db.php');
include('../common/header.php');

$keyword = $_GET['q'] ?? '';

if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT * FROM teacher
        WHERE name LIKE CONCAT('%', ?, '%')
        OR research_field LIKE CONCAT('%', ?, '%')
        OR title LIKE CONCAT('%', ?, '%')
        ORDER BY teacher_id ASC
    ");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM teacher ORDER BY teacher_id ASC");
}
?>

<div class="page-content">
    <h2>👨‍🏫 教師清單</h2>

    <form method="get" action="">
        🔍 關鍵字搜尋（可輸入：姓名、研究領域、職稱）：<br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>">
        <input type="submit" value="搜尋">
    </form>

    <br>

    <?php if ($result->num_rows === 0): ?>
        <p>❗ 沒有找到符合「<?= htmlspecialchars($keyword) ?>」的教師。</p>
    <?php else: ?>
        <table border="1" cellpadding="8">
            <tr>
                <th>姓名</th><th>信箱</th><th>電話</th><th>職稱</th><th>詳細資料</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= htmlspecialchars($row['phone']) ?></td>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><a href="/~D1285210/teachers/detail.php?id=<?= $row['teacher_id'] ?>">🔍 查看</a></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>
</div>

<?php include '../common/footer.php'; ?>
