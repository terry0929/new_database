<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../common/db.php';
include '../common/header.php';

// 取得目前登入者的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result_tid = $stmt->get_result();
$teacher_row = $result_tid->fetch_assoc();
$teacher_id = $teacher_row['teacher_id'];

// 查詢該教師自己的研究成果
$stmt = $conn->prepare("SELECT * FROM research_result WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<div class="page-content">
    <h2>🧪 我的研究成果</h2>
    <!-- <p><a href="/~D1285210/research/form.php">➕ 新增成果</a></p> -->
    <p><a href="/~D1285210/research/choose_type.php">➕ 新增成果</a></p>

    <h3>期刊論文</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>類型</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= $type1_labels[$r['type1']] ?? htmlspecialchars($r['type1'] ?? '') ?> / <?= htmlspecialchars($r['type2']) ?></td>
            <td><?= $r['publish_date'] ?></td>
            <td>
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/research/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>
