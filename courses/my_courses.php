<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';

// 檢查是否登入
if (!isset($_SESSION['user_id'])) {
    echo '<p class="page-content">請先登入。</p>';
    include '../common/footer.php'; 
    exit;
}

// 查詢登入者對應的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'] ?? '';

// 查詢登入者所授的所有課程
$stmt = $conn->prepare("SELECT c.*, t.name AS teacher_name 
                        FROM course c
                        JOIN teacher t ON c.teacher_id = t.teacher_id
                        WHERE c.teacher_id = ?
                        ORDER BY c.course_id ASC");
$teacher_name = '';
if ($teacher_id) {
    // 查詢教師名稱
    $stmt_name = $conn->prepare("SELECT name FROM teacher WHERE teacher_id = ?");
    $stmt_name->bind_param("s", $teacher_id);
    $stmt_name->execute();
    $result_name = $stmt_name->get_result();
    if ($result_name->num_rows > 0) {
        $teacher_name = $result_name->fetch_assoc()['name'];
    }
}
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$courses = $stmt->get_result();
?>

<div class="page-content">
    <div style="display: flex; justify-content: space-between; align-items: center;">
    <h2>📚 課程一覽表</h2>
    <a href="/~D1285210/courses/timetable.php?teacher_search=<?= htmlspecialchars($teacher_name) ?>" class="btn-timetable" style="
        background-color: #4CAF50;
        color: white;
        padding: 8px 14px;
        border-radius: 6px;
        text-decoration: none;
        font-size: 14px;
      ">查看我的課表</a>
    </div>
    <a href="/~D1285210/courses/form.php">➕ 新增課程</a></p>
    <table class="styled-table">
        <thead>
            <tr>
                <th>課堂代碼</th>
                <th>課堂名稱</th>
                <th>上課時間</th>
                <th>授課教師</th>
                <th>詳細資料</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($c = $courses->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($c['course_id']) ?></td>
                    <td><?= htmlspecialchars($c['name']) ?></td>
                    <td><?= htmlspecialchars($c['time']) ?></td>
                    <td><?= htmlspecialchars($c['teacher_name']) ?></td>
                    <td><a href="/~D1285210/courses/detail.php?id=<?= $c['course_id'] ?>">🔍 查看</a></td>
                    <td>
                        <a href="/~D1285210/courses/edit.php?course_id=<?= $c['course_id'] ?>">✏️ 編輯</a>
                        <a href="/~D1285210/courses/delete.php?course_id=<?= $c['course_id'] ?>" onclick="return confirm('確定刪除此課程？')">🗑️ 刪除</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>

<?php include '../common/footer.php'; ?>
