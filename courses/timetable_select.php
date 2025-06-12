<?php
include '../common/db.php';
include '../common/header.php';

// 從 teacher 表抓所有老師
$result = $conn->query("SELECT teacher_id, name FROM teacher");
?>

<div class="page-content">
  <h2>🧑‍🏫 選擇老師查詢課表</h2><br>

  <form action="timetable.php" method="GET" style="max-width: 1000px; margin: auto;">
    <!-- 選擇與輸入同行 -->
    <div style="display: flex; align-items: center; justify-content: space-between; gap: 15px; margin-bottom: 30px; flex-wrap: wrap;">
      <div style="flex: 1.2; min-width: 300px;">
        <label style="white-space: nowrap;"><h3>🔽 選擇老師：</h3></label><br>
        <select name="teacher_select" style="width: 100%; padding: 10px; font-size: 16px;">
          <option value="">請選擇老師</option>
          <?php while ($row = $result->fetch_assoc()): ?>
            <option value="<?= htmlspecialchars($row['teacher_id']) ?>">
              <?= htmlspecialchars($row['name']) ?>
            </option>
          <?php endwhile; ?>
        </select>
      </div>

      <div style="flex: 1.2; min-width: 300px;">
        <label style="white-space: nowrap;"><h3>🔍 輸入老師姓名：</h3></label><br>
        <input type="text" name="teacher_search" placeholder="輸入老師姓名"
          style="width: 100%; padding: 10px; font-size: 16px;">
      </div>
    </div>

    <!-- 查詢按鈕置中 -->
    <div style="text-align: center;">
      <button type="submit"
        style="padding: 12px 400px; font-size: 16px; background-color: #4CAF50; color: white;
               border: none; border-radius: 8px; cursor: pointer;">
        查詢課表
      </button>
    </div>
  </form>
</div>

<?php include '../common/footer.php'; ?>
