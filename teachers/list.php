
<?php
include '../common/db.php';
include '../common/header.php';

$categories = ['所有人員', '系主任', '榮譽特聘講座', '講座教授', '特約講座', '特聘教授', '專任教師', '兼任教師', '行政人員', '退休教師'];
$selected_category = $_GET['category'] ?? null;
$keyword = $_GET['q'] ?? '';

// ✅ 排除「所有人員」 → 不套分類過濾
if ($selected_category === '所有人員') {
    $selected_category = null;
}

if ($selected_category && $keyword !== '') {
    $stmt = $conn->prepare("SELECT * FROM teacher 
        WHERE FIND_IN_SET(?, category) 
        AND (name LIKE CONCAT('%', ?, '%') 
          OR email LIKE CONCAT('%', ?, '%') 
          OR title LIKE CONCAT('%', ?, '%'))
        ORDER BY teacher_id ASC");
    $stmt->bind_param("ssss", $selected_category, $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($selected_category) {
    $stmt = $conn->prepare("SELECT * FROM teacher 
        WHERE FIND_IN_SET(?, category) 
        ORDER BY teacher_id ASC");
    $stmt->bind_param("s", $selected_category);
    $stmt->execute();
    $result = $stmt->get_result();
} elseif ($keyword !== '') {
    $stmt = $conn->prepare("SELECT * FROM teacher 
        WHERE name LIKE CONCAT('%', ?, '%') 
           OR email LIKE CONCAT('%', ?, '%') 
           OR title LIKE CONCAT('%', ?, '%') 
        ORDER BY teacher_id ASC");
    $stmt->bind_param("sss", $keyword, $keyword, $keyword);
    $stmt->execute();
    $result = $stmt->get_result();
} else {
    $result = $conn->query("SELECT * FROM teacher ORDER BY teacher_id ASC");
}
?>

<div class="page-content">
<div style="display: flex;">
  <!-- 側邊分類 -->
  <aside style="width: 200px; padding: 20px; border-right: 1px solid #ccc; background-color: #f9f9f9;">
    <h3>📁 類別分類</h3><br>
    <ul style="list-style-type: none; padding: 0;">
      <?php foreach ($categories as $cat): ?>
        <li style="margin-bottom: 10px;">
          <a href="?category=<?= urlencode($cat) ?>" style="text-decoration: none; color: <?= ($cat === ($_GET['category'] ?? '')) ? 'red' : '#333' ?>;">
            › <?= $cat ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>

  <!-- 教師清單 -->
  <main style="flex: 1; padding: 20px;">
    <h2>👨‍🏫 教師清單 <?= $selected_category ? " - 分類：" . htmlspecialchars($_GET['category']) : "" ?></h2>

    <form method="get" style="margin-bottom: 20px;">
        🔍 關鍵字搜尋（姓名、信箱、職稱）：<br><br>
        <input type="text" name="q" value="<?= htmlspecialchars($keyword) ?>" style="padding: 6px; width: 250px;">
        <?php if ($selected_category): ?>
          <input type="hidden" name="category" value="<?= htmlspecialchars($_GET['category']) ?>">
        <?php endif; ?>
        <input type="submit" value="搜尋">
    </form>

    <?php if ($result->num_rows === 0): ?>
        <p style="color: red;">❗ 找不到符合條件的教師。</p>
    <?php else: ?>
      <table class="styled-table">
        <thead>
          <tr>
            <th>姓名</th>
            <th>信箱</th>
            <th>電話</th>
            <th>職稱</th>
            <th>詳細資料</th>
          </tr>
        </thead>
        <tbody>
        <?php while ($row = $result->fetch_assoc()): ?>
          <tr>
            <td><?= htmlspecialchars($row['name']) ?></td>
            <td><?= htmlspecialchars($row['email']) ?></td>
            <td><?= htmlspecialchars($row['phone']) ?></td>
            <td><?= htmlspecialchars($row['title']) ?></td>
            <td><a href="detail.php?id=<?= $row['teacher_id'] ?>">🔍 <strong>查看</strong></a></td>
          </tr>
        <?php endwhile; ?>
        </tbody>
      </table>
    <?php endif; ?>
  </main>
</div>
</div>

<?php include '../common/footer.php'; ?>
