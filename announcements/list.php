
<?php
include '../common/db.php';
include '../common/header.php';

$categories = ['全部訊息', '系所公告', '學生專欄', '國際交流', '課務公告', '事務公告', '演講公告', '一般活動公告', '競賽力活動公告', '企業徵才', '系友活動'];
$selected_category = $_GET['category'] ?? null;
$keyword = $_GET['q'] ?? '';

// 排除全部訊息分類
if ($selected_category === '全部訊息') {
    $selected_category = null;
}

if ($keyword !== '') {
    $stmt = $conn->prepare("
        SELECT a.*, t.name AS teacher_name
        FROM announcement a
        LEFT JOIN teacher t ON a.teacher_id = t.teacher_id
        WHERE (a.title LIKE CONCAT('%', ?, '%')
           OR a.category LIKE CONCAT('%', ?, '%')
           OR a.content LIKE CONCAT('%', ?, '%'))
        " . ($selected_category ? " AND a.category LIKE CONCAT('%', ? ,'%')" : "") . "
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
        WHERE a.category LIKE CONCAT('%', ? ,'%')
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

  <!-- 公告清單 -->
  <main style="flex: 1; padding: 20px;">
    <h2>📢 公告清單<?= $_GET['category'] ? " - " . htmlspecialchars($_GET['category']) : "" ?></h2>

    <form method="get" action="" class="search-form" style="margin-bottom: 20px;">
        🔍 關鍵字搜尋（標題、分類、內容）：<br><br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" style="padding: 5px; width: 250px;">
        <?php if ($selected_category): ?>
            <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif; ?>
        <input type="submit" value="搜尋">
    </form>

    <?php if ($result->num_rows === 0): ?>
        <p style="color: red;">❗ 沒有符合條件的公告。</p>
    <?php else: ?>
        <div style="display: flex; flex-wrap: wrap; gap: 20px;">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div style="border: 1px solid #ccc; border-radius: 8px; padding: 16px; width: 300px; background: #fdfdfd; box-shadow: 2px 2px 5px rgba(0,0,0,0.05);">
                    <?php if (!empty($row['image'])): ?>
                        <img src="/~D1285210/uploads_ann/<?= htmlspecialchars($row['image']) ?>" alt="公告圖片" style="width: 100%; border-radius: 6px; margin-bottom: 10px;">
                    <?php endif; ?>
                    <h3 style="margin: 0 0 8px;"><?= htmlspecialchars($row['title']) ?></h3>
                    <p style="margin: 4px 0;"><strong>分類：</strong><?= htmlspecialchars($row['category']) ?></p>
                    <p style="margin: 4px 0;"><strong>日期：</strong><?= htmlspecialchars($row['post_date']) ?></p>
                    <p style="margin: 4px 0;"><strong>發佈者：</strong><?= htmlspecialchars($row['teacher_name']) ?></p>
                    <a href="detail.php?id=<?= $row['announcement_id'] ?>" style="text-decoration: none; color: #007bff;">🔍 查看詳細</a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
  </main>
</div>
</div>

<?php include '../common/footer.php'; ?>
