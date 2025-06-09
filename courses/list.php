<?php
include '../common/db.php';
include '../common/header.php';

$keyword = $_GET['q'] ?? '';  // 使用 null 合併運算符確保沒有傳遞 q 參數時，預設為空字串

// 如果有關鍵字，則執行搜尋
if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT * FROM course
        WHERE course_id LIKE CONCAT('%', ?, '%')
           OR name LIKE CONCAT('%', ?, '%')
           OR teacher_name LIKE CONCAT('%', ?, '%')
           OR time LIKE CONCAT('%', ?, '%')
           OR classroom LIKE CONCAT('%', ?, '%')
        ORDER BY course_id ASC
    ");
    $stmt->bind_param("sssss", $keyword, $keyword, $keyword, $keyword, $keyword);
    $stmt->execute();
    $courses = $stmt->get_result();
} else {
    // 若沒有提供搜尋關鍵字，顯示所有課程
    $courses = $conn->query("SELECT * FROM course ORDER BY course_id ASC");
}
?>

<div class="page-content">
    <h2>📚 課程一覽表</h2>

    <!-- 搜尋表單 -->
    <form method="get" action="" class="search-form">
        🔍 關鍵字搜尋（課程代碼、名稱、教師、時間、教室）：<br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" >
        <input type="submit" value="搜尋">
    </form>

    <?php if ($courses->num_rows === 0): ?>
        <div class="empty-message">
            ❗ 沒有找到符合「<?= htmlspecialchars($keyword) ?>」的課程。
        </div>
    <?php else: ?>
        <!-- 顯示課程表格 -->
        <table class="styled-table">
            <thead>
                <tr>
                    <th>課堂代碼</th>
                    <th>課堂名稱</th>
                    <th>上課時間</th>
                    <th>授課教師</th>
                    <th>詳細資料</th>
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
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php include '../common/footer.php'; ?>
