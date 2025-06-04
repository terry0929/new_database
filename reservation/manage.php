<?php
include '../common/db.php';
include '../common/header.php';

// 可依需求判斷只有某些身分能進入此頁
// 例如：if ($_SESSION['user_id'] !== 'admin') { exit; }

$stmt = $conn->prepare("SELECT * FROM reservation ORDER BY reservation_id DESC");
$stmt->execute();
$reservations = $stmt->get_result();
?>

<div class="page-content">
    <h2>📊 所有預約紀錄管理</h2>

    <?php if ($reservations->num_rows > 0): ?>
        <table>
            <tr>
                <th>預約編號</th>
                <th>地點</th>
                <th>房間單位</th>
                <th>時段</th>
                <th>姓名</th>
                <th>Email</th>
                <th>原因</th>
                <th>教師 ID</th>
                <th>操作</th>
            </tr>
            <?php while ($row = $reservations->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['reservation_id']) ?></td>
                    <td><?= htmlspecialchars($row['location']) ?></td>
                    <td><?= htmlspecialchars($row['room_unit']) ?></td>
                    <td><?= htmlspecialchars($row['time_slot']) ?></td>
                    <td><?= htmlspecialchars($row['name']) ?></td>
                    <td><?= htmlspecialchars($row['email']) ?></td>
                    <td><?= nl2br(htmlspecialchars($row['reason'])) ?></td>
                    <td><?= htmlspecialchars($row['teacher_id']) ?></td>
                    <td>
                        <a href="edit.php?reservation_id=<?= htmlspecialchars($row['reservation_id']) ?>">編輯</a>
                        <a href="delete.php?reservation_id=<?= htmlspecialchars($row['reservation_id']) ?>" onclick="return confirm('確定要刪除這筆預約嗎？');">刪除</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>目前尚無任何預約資料。</p>
    <?php endif; ?>
</div>

<?php include '../common/footer.php'; ?>
