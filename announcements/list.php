
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
  <aside style="width: 220px; padding: 20px; background-color: #f9f9f9;">
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
        <div class="announcement-container">
            <?php while ($row = $result->fetch_assoc()): ?>
                <div class="announcement">
                    <a href="detail.php?id=<?= $row['announcement_id'] ?>">
                        <?php if (!empty($row['image'])): ?>
                            <img src="/~D1285210/uploads_ann/<?= htmlspecialchars($row['image']) ?>" alt="公告圖片" class="announcement-photo">
                        <?php endif; ?>
                        <h3 style="margin: 0 0 8px;"><?= htmlspecialchars($row['title']) ?></h3>
                        <p style="margin: 4px 0;"><strong>分類：</strong><?= htmlspecialchars($row['category']) ?></p>
                        <p style="margin: 4px 0;"><strong>日期：</strong><?= htmlspecialchars($row['post_date']) ?></p>
                        <p style="margin: 4px 0;"><strong>發佈者：</strong><?= htmlspecialchars($row['teacher_name']) ?></p>
                    </a>
                </div>
            <?php endwhile; ?>
        </div>
    <?php endif; ?>
  </main>
</div>
</div>

<?php include '../common/footer.php'; ?>

<style>
    .announcement-container {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }
    .announcement {
        flex: 1 1 calc(50% - 20px); /* 每個公告占 50% 寬度，減去間距 */
        max-width: calc(50% - 20px); /* 强制最大宽度，确保布局一致 */
        background: #fff;
        padding: 25px 30px;
        margin: 30px 0;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        transition: all 0.2s ease;
    }

    .announcement:hover {
        cursor: pointer;
        background-color: #f0f0f0; /* 鼠標懸停時改變背景色 */
        transform: translateY(-4px);
        box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
    }

    .announcement a {
        text-decoration: none;
        color: #333; /* 文字顏色 */
    }
    .announcement-photo {
        width: 100%; /* 限制圖片寬度為公告方塊的寬度 */
        height: 200px; /* 限制高度 */
        object-fit: cover; /* 確保圖片以正方形顯示，裁剪超出部分 */
        border-radius: 8px; /* 圓角效果 */
        border: 2px solid #ccc; /* 增加邊框 */
    }
</style>