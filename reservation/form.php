<?php
include '../common/db.php';
include '../common/header.php';
session_start();


?>

<div class="page-content">
  <h2>📝 預約表單</h2>
  <form method="POST" action="save.php">
    <label>地點:
    <select name="location" required>
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
    </select>
    </label><br>


    <label for="date">日期：</label>
    <input type="date" name="date" required><br>

    <label for="start_time">開始時間：</label>
    <select name="start_time" required>
      <?php for ($h = 8; $h <= 18; $h++) {
        $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00";
        echo "<option value='$time'>$time</option>";
      } ?>
    </select><br>

    <label for="duration">借用幾小時：</label>
    <select name="duration" required>
      <option value="1">1 小時</option>
      <option value="2">2 小時</option>
      <option value="3">3 小時</option>
    </select><br>

    <input type="submit" value="送出預約">
  </form>
</div>

<?php include '../common/footer.php'; ?>
