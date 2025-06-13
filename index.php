<?php
include 'common/db.php';
include 'common/header.php';
$sql = "SELECT announcement_id, title, image FROM announcement ORDER BY post_date DESC LIMIT 4";
$result = $conn->query($sql);

$user_id = $_SESSION['user_id'] ?? null;
$is_teacher = false;

// 判斷是否為老師
if ($user_id) {
    $stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
    $stmt->bind_param("s", $user_id);
    $stmt->execute();
    $res = $stmt->get_result();
    $is_teacher = $res->num_rows > 0;
}
?>

<div class="page-content">
    <h2>🎓 歡迎來到教師資訊系統</h2>
    <p>這是一個整合公告、教師、課程、經歷與研究的系統。</p>

    <?php if (!$user_id): ?>
        <p>請 <a href="/~D1285210/login.php">登入</a> 後使用控制台功能。</p>
    <?php elseif ($is_teacher): ?>
        <p>您已登入教師帳號，請進入 <a href="/~D1285210/edit_mode.php">控制台</a> 開始管理您的資料。</p>
    <?php else: ?>
        <p>您是學生帳號，無法使用控制台功能，但可以瀏覽教師資訊與公告。</p>
    <?php endif; ?>
</div>



<div class="page-content">
  <h2>📢 最新公告</h2>
  <div class="announcement-grid">
    <?php while ($row = $result->fetch_assoc()): ?>
      <a href="announcements/detail.php?id=<?= $row['announcement_id'] ?>" class="announcement-card">
        <img src="/~D1285210/uploads_ann/<?= htmlspecialchars($row['image']) ?>" alt="公告圖片">
        <div class="announcement-title"><?= htmlspecialchars($row['title']) ?></div>
      </a>
    <?php endwhile; ?>
  </div>
  <div style="text-align: right; margin-top: 10px;">
            <a href="announcements/list.php" class="btn-more">👉 顯示更多公告</a>
    </div>
</div>
<?php
$teacher_sql = "SELECT teacher_id, name, photo FROM teacher ORDER BY RAND() LIMIT 4";
$teacher_result = $conn->query($teacher_sql);
?>
<div class="page-content">
  <h2>👨‍🏫 認識我們的老師</h2>
  <div class="teacher-grid">
    

    <?php while ($teacher = $teacher_result->fetch_assoc()): ?>
      <a href="teachers/detail.php?id=<?= $teacher['teacher_id'] ?>" class="teacher-card">
        <img src="/~D1285210/uploads/<?= htmlspecialchars($teacher['photo'] ?? 'default_avatar.png') ?>" alt="教師照片">
        <div class="teacher-name"><?= htmlspecialchars($teacher['name']) ?></div>
      </a>
    <?php endwhile; ?>
    </div>
    <div style="text-align: right; margin-top: 10px;">
            <a href="teachers/list.php" class="btn-more">👉 顯示更多老師</a>
    </div>
</div>


<?php include 'common/footer.php'; ?>
