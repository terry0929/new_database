<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='page-content'><p>❌ 缺少預約 ID</p></div>";
    include '../common/footer.php'; exit;
}

$id = $_GET['id'];

// 查詢該筆預約資料
$stmt = $conn->prepare("SELECT * FROM reservation WHERE reservation_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<div class='page-content'><p>❌ 查無此預約資料</p></div>";
    include '../common/footer.php'; exit;
}

// 計算借用幾小時
$start = strtotime($data['start_time']);
$end = strtotime($data['end_time']);
$duration = ($end - $start) / 3600;
?>

<div class="page-content">
    <h2>✏️ 編輯預約</h2>
    <form action="update.php" method="post">
        <input type="hidden" name="reservation_id" value="<?= $id ?>">

        <label><h3>地點:</h3><br>
            <select name="location" style="width:80%; padding:10px; font-size: 16px;" required>
                <option value="">請選擇地點</option>

                <optgroup label="人文大樓 1F / 2F">
                    <?php foreach (["人104(8人)", "人105(8人)", "人205(8人)", "人206(8人)", "人208(6人)"] as $loc): ?>
                        <option value="<?= $loc ?>" <?= ($data['location'] === $loc) ? 'selected' : '' ?>><?= $loc ?></option>
                    <?php endforeach; ?>
                </optgroup>

                <optgroup label="人文大樓 B1">
                    <?php foreach (["人B101A(16人)", "人B102A(16人)", "人B103A(12人)", "人B104A(8人)", "人B105A(8人)", "人B113A(8人)", "人B114A(8人)"] as $loc): ?>
                        <option value="<?= $loc ?>" <?= ($data['location'] === $loc) ? 'selected' : '' ?>><?= $loc ?></option>
                    <?php endforeach; ?>
                </optgroup>

                <optgroup label="圖書館視聽小間">
                    <?php foreach (["圖視聽小間303(5人)", "圖視聽小間304(5人)", "圖視聽小間305(5人)"] as $loc): ?>
                        <option value="<?= $loc ?>" <?= ($data['location'] === $loc) ? 'selected' : '' ?>><?= $loc ?></option>
                    <?php endforeach; ?>
                </optgroup>

                <optgroup label="討論室">
                    <?php foreach (["討論室320(5人)", "討論室321(5人)"] as $loc): ?>
                        <option value="<?= $loc ?>" <?= ($data['location'] === $loc) ? 'selected' : '' ?>><?= $loc ?></option>
                    <?php endforeach; ?>
                </optgroup>
            </select>
        </label><br><br>
        <label for="date"><h3>日期：</h3></label><br>
        <input type="date" name="date" value="<?= $data['date'] ?>"  style="width:80%; padding:10px; font-size: 16px;" required><br><br>

        <label for="start_time"><h3>開始時間：</h3></label><br>
        <select name="start_time"  style="width:80%; padding:10px; font-size: 16px;" required>
            <?php for ($h = 8; $h <= 18; $h++): 
                $time = str_pad($h, 2, '0', STR_PAD_LEFT) . ":00"; ?>
                <option value="<?= $time ?>" <?= ($data['start_time'] === $time) ? 'selected' : '' ?>><?= $time ?></option>
            <?php endfor; ?>
        </select><br><br>

        <label for="duration"><h3>借用幾小時：</h3></label><br>
        <select name="duration"  style="width:80%; padding:10px; font-size: 16px;" required>
            <option value="1" <?= ($duration == 1) ? 'selected' : '' ?>>1 小時</option>
            <option value="2" <?= ($duration == 2) ? 'selected' : '' ?>>2 小時</option>
            <option value="3" <?= ($duration == 3) ? 'selected' : '' ?>>3 小時</option>
        </select><br><br>

        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>