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

    <form method="get" action="" class="search-form">
    <label>🔍 關鍵字搜尋（可輸入：姓名、研究領域、職稱）：</label><br>
    <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>">
    <input type="submit" value="搜尋">
    </form>

    <table class="styled-table">
        <thead>
            <tr>
                <th>姓名</th>
                <th>信箱</th>
                <th>電話</th>
                <th>職稱</th>
                <th>詳細資料</th>
            </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
            <tr>
                <td><?= htmlspecialchars($row['name']) ?></td>
                <td><?= htmlspecialchars($row['email']) ?></td>
                <td><?= htmlspecialchars($row['phone']) ?></td>
                <td><?= htmlspecialchars($row['title']) ?></td>
                <td><a href="detail.php?id=<?= $row['teacher_id'] ?>">🔍 <strong>查看</strong></a></td>
            </tr>
        <?php endwhile; ?>
        </tbody>
    </table>

</div>

<?php include '../common/footer.php'; ?>
