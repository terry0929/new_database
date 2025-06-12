
<?php
include('../common/db.php');
include('../common/header.php');

$categories = ['系主任', '榮譽特聘講座', '講座教授', '特約講座', '特聘教授', '專任教師', '兼任教師', '行政人員', '退休教師'];
$selected_category = $_GET['category'] ?? null;

// 搜尋資料（有分類過濾）
if ($selected_category) {
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE FIND_IN_SET(?, category) ORDER BY teacher_id ASC");
    $stmt->bind_param("s", $selected_category);
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
          <a href="?category=<?= urlencode($cat) ?>" style="text-decoration: none; color: <?= ($cat === $selected_category) ? 'red' : '#333' ?>;">
            › <?= $cat ?>
          </a>
        </li>
      <?php endforeach; ?>
    </ul>
  </aside>

  <!-- 右側教師列表 -->
  <main style="flex: 1; padding: 20px;">
    <h2>👨‍🏫 教師清單 <?= $selected_category ? " - 分類：" . htmlspecialchars($selected_category) : "" ?></h2>

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
  </main>
</div>
</div>

<?php include('../common/footer.php'); ?>
