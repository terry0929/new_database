<?php
include '../common/db.php';
include '../common/header.php';

$keyword = $_GET['q'] ?? '';

if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT * FROM reservation 
        WHERE location LIKE CONCAT('%', ?, '%')
           OR date LIKE CONCAT('%', ?, '%')
           OR name LIKE CONCAT('%', ?, '%')
        ORDER BY date ASC, start_time ASC
    ");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM reservation ORDER BY date ASC, start_time ASC");
}
?>

<div class="page-content">
    <h2>📅 空間預約一覽</h2>

    <form method="get" action="">
        🔍 關鍵字搜尋（地點、日期、姓名）：<br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" >
        <input type="submit" value="搜尋">
    </form>
    <br>

    <?php if ($result->num_rows === 0): ?>
        <p>❗ 沒有找到符合「<?= htmlspecialchars($keyword) ?>」的預約紀錄。</p>
    <?php else: ?>
    <table class="styled-table">
        <tr>
            <th>地點</th>
            <th>時間</th>
            <th>預約人</th>
            <th>Email</th>
        </tr>
        <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($row['location']) ?></td>
            <td><?= htmlspecialchars($row['date']) ?> <?= $row['start_time'] ?> ~ <?= $row['end_time'] ?></td>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
        </tr>
        <?php endwhile; ?>
    </table>
    <?php endif; ?>
</div>

<?php include '../common/footer.php'; ?>
