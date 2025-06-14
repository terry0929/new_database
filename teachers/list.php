<?php
include '../common/db.php';
include '../common/header.php';

$categories = ['所有人員', '系主任', '榮譽特聘講座', '講座教授', '特約講座', '特聘教授', '專任教師', '兼任教師', '行政人員', '退休教師'];
$selected_category = $_GET['category'] ?? null;
$keyword = $_GET['q'] ?? '';

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
    <div class="teacher-grid">
      <?php while ($row = $result->fetch_assoc()): ?>
        <div class="teacher-card">
          <a href="detail.php?id=<?= $row['teacher_id'] ?>">
          <div class="photo">
            <img src="<?= $row['photo'] ? '/~D1285210/uploads/' . $row['photo'] : '/~D1285210/common/default_avatar.png' ?>" alt="照片">
          </div>
          <div class="info">
            <h3><?= htmlspecialchars($row['name']) ?></h3>
            <p>職稱：<?= htmlspecialchars($row['title']) ?></p>
            <p>電話：<?= htmlspecialchars($row['phone']) ?></p>
          </div>
          </a>
        </div>
      <?php endwhile; ?>
    </div>
    <?php endif; ?>
  </main>
</div>
</div>

<style>
.teacher-grid {
  display: flex;
  flex-wrap: wrap;
  gap: 20px;
  margin-top: 20px;
}
.teacher-card {
  flex: 1 1 calc(50% - 20px); /* 每個公告占 50% 寬度，減去間距 */
  max-width: calc(50% - 20px); /* 强制最大宽度，确保布局一致 */
  background: #fff;
  padding: 25px 30px;
  margin: 30px 0;
  border-radius: 12px;
  box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
  transition: all 0.2s ease;
}

.teacher-card:hover{
  cursor: pointer;
  background-color: #f0f0f0; /* 鼠標懸停時改變背景色 */
  transform: translateY(-4px);
  box-shadow: 0 6px 18px rgba(0, 0, 0, 0.1);
}

.teacher-card a {
  text-decoration: none;
  color: #333; /* 文字顏色 */
}

.teacher-card .photo img {
  width: 140px;
  height: 180px;
  object-fit: cover;
  border-right: 1px solid #ccc;
}
.teacher-card .info {
  padding: 15px;
}
.teacher-card h3 {
  margin: 0 0 10px;
  font-size: 20px;
  color: #333;
}
.teacher-card p {
  margin: 4px 0;
  font-size: 16px;
  color: #555;
}

</style>

<?php include '../common/footer.php'; ?>