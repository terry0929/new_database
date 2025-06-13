<?php
include '../common/db.php';
include '../common/header.php';
session_start();

$date = $_GET['date'] ?? date('Y-m-d');
$start_time = $_GET['start_time'] ?? '';
$location = $_GET['room'] ?? '';
?>

<div class="page-content">
  <h2>📝 預約表單</h2>
  <form method="POST" action="save.php">

    <?php if ($date && $start_time && $location): ?>
      <!-- ✅ 若從表格點進來，自動填入且禁止修改 -->
      <label><h3>地點：</h3></label><br>
      <input type="text" value="<?= htmlspecialchars($location) ?>" disabled
             style="width:80%; padding:10px; font-size: 16px;"><br><br>
      <input type="hidden" name="location" value="<?= htmlspecialchars($location) ?>">

      <label><h3>日期：</h3></label><br>
      <input type="text" value="<?= htmlspecialchars($date) ?>" disabled
             style="width:80%; padding:10px; font-size: 16px;"><br><br>
      <input type="hidden" name="date" value="<?= htmlspecialchars($date) ?>">

      <label><h3>開始時間：</h3></label><br>
      <input type="text" value="<?= htmlspecialchars($start_time) ?>" disabled
             style="width:80%; padding:10px; font-size: 16px;"><br><br>
      <input type="hidden" name="start_time" value="<?= htmlspecialchars($start_time) ?>">

    <?php else: ?>
      <!-- ✅ 若使用者自己開新表單，自行選擇 -->
      <label><h3>地點:</h3></label><br>
      <select name="location" style="width:80%; padding:10px; font-size: 16px;" required>
        <option value="">請選擇地點</option>
        <optgroup label="人文大樓 1F / 2F">
          <option>人104(8人)</option>
          <option>人105(8人)</option>
          <option>人205(8人)</option>
          <option>人206(8人)</option>
          <option>人208(6人)</option>
        </optgroup>
        <optgroup label="人文大樓 B1">
          <option>人B101A(16人)</option>
          <option>人B102A(16人)</option>
          <option>人B103A(12人)</option>
          <option>人B104A(8人)</option>
          <option>人B105A(8人)</option>
          <option>人B113A(8人)</option>
          <option>人B114A(8人)</option>
        </optgroup>
        <optgroup label="圖書館視聽小間">
          <option>圖視聽小間303(5人)</option>
          <option>圖視聽小間304(5人)</option>
          <option>圖視聽小間305(5人)</option>
        </optgroup>
        <optgroup label="討論室">
          <option>討論室320(5人)</option>
          <option>討論室321(5人)</option>
        </optgroup>
      </select><br><br>

      <label for="date"><h3>日期：</h3></label><br>
      <input type="date" name="date" style="width:80%; padding:10px; font-size: 16px;" required><br><br>

      <label for="start_time"><h3>開始時間：</h3></label><br>
      <select name="start_time" style="width:80%; padding:10px; font-size: 16px;" required>
        <?php for ($h = 8; $h <= 18; $h++): 
          $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00";
        ?>
          <option value="<?= $time ?>"><?= $time ?></option>
        <?php endfor; ?>
      </select><br><br>
    <?php endif; ?>

    <!-- ⏱️ 借用幾小時：共用區域 -->
    <label for="duration"><h3>借用幾小時：</h3></label><br>
    <select name="duration" style="width:80%; padding:10px; font-size: 16px;" required>
      <option value="1">1 小時</option>
      <option value="2">2 小時</option>
      <option value="3">3 小時</option>
    </select><br><br>

    <div style="display: flex; justify-content: center; margin-top: 20px;">
      <input type="submit" value="儲存"
        style="padding: 10px 20px; width: 60%; font-size: 16px;
               background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer;">
    </div>
  </form>
</div>

<?php include '../common/footer.php'; ?>
