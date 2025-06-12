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
    $research = $conn->prepare(
      "SELECT rr.result_id, rr.type, rr.created_at AS publish_date,
           CASE rr.type
               WHEN 'ja' THEN ja.title
               WHEN 'cp' THEN cp.title
               WHEN 'br' THEN br.title
               WHEN 'np' THEN np.title
               WHEN 'ind' THEN ind.title
           END AS title,
           CASE rr.type
               WHEN 'ja' THEN ja.summary
               WHEN 'cp' THEN cp.summary
               WHEN 'br' THEN br.summary
               WHEN 'np' THEN np.summary
               WHEN 'ind' THEN ind.outcome
           END AS summary,
           CASE rr.type
               WHEN 'ja' THEN ja.author
               WHEN 'cp' THEN cp.author
               WHEN 'br' THEN br.author
               WHEN 'np' THEN np.author
               WHEN 'ind' THEN ind.author
           END AS author
        FROM researchs_result rr
        JOIN Teacher_research tr ON rr.result_id = tr.results_id
        LEFT JOIN journal_articles ja ON rr.result_id = ja.result_id
        LEFT JOIN conference_papers cp ON rr.result_id = cp.result_id
        LEFT JOIN books_reports br ON rr.result_id = br.result_id
        LEFT JOIN nstc_projects np ON rr.result_id = np.result_id
        LEFT JOIN industry_projects ind ON rr.result_id = ind.result_id
        WHERE tr.teachers_id = ?
        ORDER BY rr.created_at DESC"
    );
    $research->bind_param("s", $id);
    $research->execute();
    $research_result = $research->get_result();

    $categories = [
    'ja' => '期刊論文',
    'cp' => '會議論文',
    'br' => '專書與技術報告',
    'np' => '國科會計劃',
    'ind' => '產學合作計劃'];

    $research_data = [];
    while ($r = $research_result->fetch_assoc()) {
        $research_data[$r['type']][] = $r;
    }

    foreach ($categories as $type => $label):
?>
    <h4><?= htmlspecialchars($label) ?></h4>
    <ul class="research-list">
    <?php if (!empty($research_data[$type])): ?>
        <?php foreach ($research_data[$type] as $r): ?>
          <div class="research-box">
            <a href="/~D1285210/research/detail.php?id=<?= htmlspecialchars($r['result_id']) ?>">
            <div>
              <div>
                <strong>標題：</strong>
                  <?= htmlspecialchars($r['title']) ?>
              </div>
              <div>
                <strong>作者：</strong>
                  <?= htmlspecialchars($r['author']) ?>
              </div>
              <div>
                <strong>摘要：</strong><?= htmlspecialchars($r['summary']) ?>
              </div>
              <div>
                <strong>檔案建置時間：</strong><?= htmlspecialchars($r['publish_date']) ?>
              </div>
            </div>
          </a>
          </div>
        <?php endforeach; ?>
    <?php else: ?>
        <li>尚無 <?= htmlspecialchars($label) ?> 資料。</li>
    <?php endif; ?>
    </ul>
<?php endforeach; ?>
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

  .research-content {
    background-color: #fff;
    padding: 25px;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    margin: 30px 0;
    width: 100%;
  }
</style>
