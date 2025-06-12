
<?php
include '../common/db.php';
include '../common/header.php';

$categories = ['全部訊息', '系所公告', '學生專欄', '國際交流', '課務公告', '事務公告', '演講公告', '一般活動公告', '競賽力活動公告', '企業徵才', '系友活動'];
$selected_category = $_GET['category'] ?? null;

// ✅ 排除「全部訊息」等同於不選分類
if ($selected_category === '全部訊息') {
    $selected_category = null;
}

$keyword = $_GET['q'] ?? '';

if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        WHERE (a.title LIKE CONCAT('%', ?, '%')
           OR a.category LIKE CONCAT('%', ?, '%')
           OR a.content LIKE CONCAT('%', ?, '%'))
        " . ($selected_category ? " AND FIND_IN_SET(?, a.category)" : "") . "
        ORDER BY a.post_date DESC
    ");
    if ($selected_category) {
        $stmt->bind_param("ssss", $keyword, $keyword, $keyword, $selected_category);
    } else {
        $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    }
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($selected_category) {
    $stmt = $conn->prepare("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        WHERE FIND_IN_SET(?, a.category)
        ORDER BY a.post_date DESC
    ");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        ORDER BY a.post_date DESC
    ");
}
?>

<div class="page-content">
<div style="display: flex;">
  <!-- 側邊分類欄 -->
  <aside style="width: 220px; padding: 20px; border-right: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>📁 分類</h3><br>
    <ul style="list-style-type: none; padding-left: 0;">
      <?php foreach ($categories as $cat): ?>
        <li style="margin-bottom: 10px;">
          <a href="?category=<?= urlencode($cat) ?>" style="text-decoration: none; color: <?= ($cat === ($_GET['category'] ?? '')) ? 'red' : '#333' ?>;">
            › <?= $cat ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>

  <!-- 公告主區塊 -->
  <main style="flex: 1; padding: 20px;">
    <h2>📢 公告清單<?= $selected_category ? " - " . htmlspecialchars($_GET['category']) : "" ?></h2>

    <form method="get" action="" class="search-form" style="margin-bottom: 20px;">
        🔍 關鍵字搜尋（標題、分類、內容）：<br><br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" style="padding: 5px;">
        <?php if ($selected_category): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif; ?>
        <input type="submit" value="搜尋">
    </form>

    <?php if ($result->num_rows === 0): ?>
        <div class="empty-message" style="color: crimson; font-weight: bold;">
            ❗ 沒有找到符合條件的公告。
        </div>
    <?php else: ?>
        <table class="styled-table">
            <thead>
                <tr>
                    <th>標題</th>
                    <th>發佈日期</th>
                    <th>詳細資料</th>
                </tr>
            </thead>
            <tbody>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= htmlspecialchars($row['title']) ?></td>
                    <td><?= htmlspecialchars($row['post_date']) ?></td>
                    <td><a href="/~D1285210/announcements/detail.php?id=<?= $row['announcement_id'] ?>">🔍 查看</a></td>
                </tr>
            <?php endwhile; ?>
            </tbody>
        </table>
    <?php endif; ?>
  </main>
</div>
</div>

<?php include '../common/footer.php'; ?>
