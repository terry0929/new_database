<?php
include '../common/db.php';
include '../common/header.php';

$result = $conn->query("SELECT * FROM reservation ORDER BY date ASC, start_time ASC");
?>

<div class="page-content">
    <h2>📅 空間預約一覽</h2>
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
</div>

<?php include '../common/footer.php'; ?>
