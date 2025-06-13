<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_SESSION['user_id'])) {
    echo '<div class="page-content"><p>⚠️ 請先登入以查看預約紀錄。</p></div>';
    include '../common/footer.php';
    exit;
}

// 查出登入者的 teacher_id
$teacher_id = '';
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$teacher_id = $data['teacher_id'] ?? '';

if (!$teacher_id) {
    echo '<div class="page-content"><p>⚠️ 找不到對應教師資料。</p></div>';
    include '../common/footer.php';
    exit;
}

// 查詢這個老師的預約紀錄
$stmt = $conn->prepare("SELECT * FROM reservation WHERE teacher_id = ? ORDER BY date DESC, start_time DESC");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$reservations = $stmt->get_result();
?>

<div class="page-content">
    <h2>📋 我的預約紀錄</h2>
        <table class="styled-table">
        <tr>
            <th>地點</th>
            <th>時段</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $reservations->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['location']) ?></td>
            <td><?= $r['date'] . ' ' . $r['start_time'] . ' ~ ' . $r['end_time'] ?></td>
            <td>
                <a href="edit.php?id=<?= $r['reservation_id'] ?>">✏️ 編輯</a> |
                <a href="delete.php?id=<?= $r['reservation_id'] ?>" onclick="return confirm('確定要取消這筆預約？')">🗑️ 取消</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>

</div>

<?php include '../common/footer.php'; ?>
