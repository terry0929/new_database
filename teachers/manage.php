<?php
include '../common/db.php';
include '../common/header.php';

if (!isset($_GET['id'])) {
    echo "<div class='page-content'><p>⚠️ 未指定教師 ID</p></div>";
    include '../common/footer.php'; exit;
}

$id = $_GET['id'];

// 取得教師資料
$stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $id);
$stmt->execute();
$teacher = $stmt->get_result()->fetch_assoc();

if (!$teacher) {
    echo "<div class='page-content'><p>⚠️ 找不到這位教師</p></div>";
    include '../common/footer.php'; exit;
}

// 頭像處理
$photoPath = $teacher['photo']
    ? "/~D1285210/uploads/" . $teacher['photo']
    : "/~D1285210/common/default_avatar.png";
?>

<div class="page-content">
    <h2>👨‍🏫 教師介紹</h2>
    <div class="profile-container">
        <div class="profile-header">
            <h2><?= htmlspecialchars($teacher['name']) ?></h2>
            <label><?= htmlspecialchars($teacher['title']) ?></label>
        </div>
    </div>
    <div class="profile-photo">
        <img src="<?= $photoPath ?>" alt="大頭照" width="200">
    </div><br><br>
    <div class="profile-info">
        <h3>聯絡資訊</h3>
        <ul>
            <li><strong>教師編號：</strong><?= htmlspecialchars($teacher['teacher_id']) ?></li>
            <li><strong>信箱：</strong><?= htmlspecialchars($teacher['email']) ?></li>
            <li><strong>電話：</strong><?= htmlspecialchars($teacher['phone']) ?></li>
        </ul>
    </div>
    <br></br>
    <h3>教師經歷</h3>
    <ul class="experience-list">
    <?php
    $exp = $conn->prepare("SELECT * FROM experience WHERE teacher_id = ?");
    $exp->bind_param("s", $id);
    $exp->execute();
    $exp_result = $exp->get_result();
    if ($exp_result->num_rows > 0):
        while ($e = $exp_result->fetch_assoc()):
    ?>
        <li><?= $e['type'] === 'in' ? '校內' : '校外' ?>：<?= htmlspecialchars($e['description']) ?></li>
    <?php
        endwhile;
    else:
        echo "<li>尚無經歷資料。</li>";
    endif;
    ?>
    </ul>
    <br></br>
    <h3>研究成果</h3>
    <ul class="research-list">
    <?php
    $research = $conn->prepare("
        SELECT rr.* 
        FROM research_result rr
        JOIN teacher_research tr ON rr.result_id = tr.result_id
        WHERE tr.teacher_id = ?
    ");
    $research->bind_param("s", $id);
    $research->execute();
    $research_result = $research->get_result();

    if ($research_result->num_rows > 0):
        while ($r = $research_result->fetch_assoc()):
    ?>
        <li>
            <?= htmlspecialchars($r['title']) ?>
            （<?= htmlspecialchars($r['type1']) ?> / <?= htmlspecialchars($r['type2']) ?>）
            （<?= htmlspecialchars($r['publish_date']) ?>）
        </li>
    <?php
        endwhile;
    else:
        echo "<li>尚無研究成果資料。</li>";
    endif;
    ?>
    </ul>
</div>

<?php include '../common/footer.php'; ?>

<style>
  .profile-container {
    display: flex;
    gap: 50px; /* 增加照片與資訊之間的間距 */
    align-items: flex-start;
    margin-bottom: 30px;
  }

  .profile-photo img {
    border-radius: 8px;
    border: 2px solid #ccc;
    width: 400px; /* 照片放大 */
  }

  .profile-info {
    max-width: 500px; /* 增加文字寬度，讓字不會太擁擠 */
  }

  h2 {
    font-size: 32px; /* 標題字體加大 */
    margin-bottom: 30px;
    font-weight: bold;
    color: #333;
  }

  h3 {
    font-size: 26px; /* 標題字體加大 */
    margin-bottom: 20px;
    color: #333;
  }

  .profile-info h3 {
    font-size: 24px; 
    margin-bottom: 10px;
  }

  .profile-info ul {
    list-style-type: none;
    padding: 0;
  }

  .profile-info li {
    margin-bottom: 15px; /* 增加間距，讓內容不會擁擠 */
    font-size: 18px; /* 字體加大 */
    color: #555;
  }

  .experience-list, .research-list {
    padding-left: 20px;
    font-size: 18px; /* 增加列表文字字體 */
  }

  .experience-list li, .research-list li {
    margin-bottom: 10px;
    color: #555;
  }

  .experience-list li:before, .research-list li:before {
    margin-right: 10px;
  }

  /* 給容器加上陰影和圓角 */
  .page-content {
    background-color: #fff;
    padding: 40px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 30px auto;
    width: 80%;
  }
</style>
