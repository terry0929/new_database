<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_SESSION['user_id'])) {
    echo '<p class="page-content">請先登入。</p>';
    include '../common/footer.php'; exit;
}

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'] ?? '';

$stmt = $conn->prepare("SELECT * FROM course WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$courses = $stmt->get_result();
?>

<div class="page-content">
    <h2>📚 課程一覽表</h2>
        <a href="/~D1285210/courses/form.php">➕ 新增課程</a></p>
        <table class="styled-table">
            <tr>
                <th>課堂代碼</th>
                <th>課堂名稱</th>
                <th>上課時間</th>
                <th>課堂教室</th>
                <th>授課教師</th>
                <th>詳細資料</th>
                <th>操作</th>
            </tr>
            <?php while ($c = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($c['course_id']) ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['time']) ?></td>
                    <td><?= htmlspecialchars($c['classroom']) ?></td>
                    <td><?= htmlspecialchars($c['teacher_name']) ?></td>
                    <td><a href="/~D1285210/courses/detail.php?id=<?= $c['course_id'] ?>">🔍 查看</a></td>
                    <td>
                        <a href="/~D1285210/courses/edit.php?course_id=<?= $c['course_id'] ?>">✏️ 編輯</a>
                        <a href="/~D1285210/courses/delete.php?course_id=<?= $c['course_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
</div>

<?php include '../common/footer.php'; ?>

