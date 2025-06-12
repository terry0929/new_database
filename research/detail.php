<?php
include('../common/db.php');
include('../common/header.php');

$result_id = $_GET['id'] ?? '';
if (!$result_id) {
    echo "<div class='container'><p>❌ 錯誤：未指定 result_id</p></div>";
    exit;
}

// 查 type
$stmt = $conn->prepare("SELECT type FROM researchs_result WHERE result_id = ?");
$stmt->bind_param("s", $result_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows === 0) {
    echo "<div class='container'><p>❌ 錯誤：找不到這筆研究成果</p></div>";
    exit;
}
$type = $res->fetch_assoc()['type'];

// 對應表名
$tableMap = [
    'ja' => ['table' => 'journal_articles', 'label' => '期刊論文'],
    'cp' => ['table' => 'conference_papers', 'label' => '會議論文'],
    'br' => ['table' => 'books_reports', 'label' => '專書與技術報告'],
    'np' => ['table' => 'nstc_projects', 'label' => '國科會計畫'],
    'ind'=> ['table' => 'industry_projects', 'label' => '產學合作']
];

if (!isset($tableMap[$type])) {
    echo "<div class='container'><p>❌ 錯誤：不支援的 type 類別</p></div>";
    exit;
}

$table = $tableMap[$type]['table'];
$label = $tableMap[$type]['label'];

// 查子表內容
$stmt = $conn->prepare("SELECT * FROM $table WHERE result_id = ?");
$stmt->bind_param("s", $result_id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();
?>

  <style>
    body { font-family: "Noto Sans TC", sans-serif; background: #f9f9f9; padding: 30px; }
    .container { background: #fff; padding: 20px 30px; max-width: 700px; margin: auto; box-shadow: 0 0 10px rgba(0,0,0,0.1); border-radius: 8px; }
    h2 { color: #333; }
    .info { margin: 15px 0; }
    .info label { font-weight: bold; display: inline-block; width: 150px; color: #444; }
    a.back { display: inline-block; margin-top: 20px; color: #007bff; text-decoration: none; }
    a.back:hover { text-decoration: underline; }
  </style>
<body>
  <div class="container">
    <h2>📄 <?= htmlspecialchars($label) ?> 詳細資訊</h2>
    
    <?php
    switch ($type) {
        case 'ja': // 期刊論文
            echo "<div class='field'><label>文獻標題：</label>" . htmlspecialchars($data['title']) . "</div>";
            echo "<div class='field'><label>作者：</label>" . htmlspecialchars($data['author']) . "</div>";
            echo "<div class='field'><label>摘要：</label>" . htmlspecialchars($data['summary']) . "</div>";
            echo "<div class='field'><label>卷號：</label>" . htmlspecialchars($data['volume']) . "</div>";
            echo "<div class='field'><label>期號：</label>" . htmlspecialchars($data['issue']) . "</div>";
            echo "<div class='field'><label>頁碼：</label>" . htmlspecialchars($data['pages']) . "</div>";
            echo "<div class='field'><label>DOI：</label>" . htmlspecialchars($data['doi']) . "</div>";
            echo "<div class='field'><label>上傳日期：</label>" . htmlspecialchars($data['upload_date']) . "</div>";
            echo "<div class='field'><label>APA格式：</label>" . htmlspecialchars($data['APA']) . "</div>";
            echo "<div class='field'><label>備註：</label>" . htmlspecialchars($data['remarks']) . "</div>";
            break;

        case 'cp': // 會議論文
            echo "<div class='field'><label>論文標題：</label>" . htmlspecialchars($data['title']) . "</div>";
            echo "<div class='field'><label>作者：</label>" . htmlspecialchars($data['author']) . "</div>";
            echo "<div class='field'><label>摘要：</label>" . htmlspecialchars($data['summary']) . "</div>";
            echo "<div class='field'><label>會議名稱：</label>" . htmlspecialchars($data['conference_name']) . "</div>";
            echo "<div class='field'><label>地點：</label>" . htmlspecialchars($data['locations']) . "</div>";
            echo "<div class='field'><label>會議日期：</label>" . htmlspecialchars($data['conference_date']) . "</div>";
            echo "<div class='field'><label>上傳日期：</label>" . htmlspecialchars($data['upload_date']) . "</div>";
            echo "<div class='field'><label>備註：</label>" . htmlspecialchars($data['remarks']) . "</div>";
            break;

        case 'br': // 專書與報告
            echo "<div class='field'><label>書籍名稱：</label>" . htmlspecialchars($data['title']) . "</div>";
            echo "<div class='field'><label>作者：</label>" . htmlspecialchars($data['author']) . "</div>";
            echo "<div class='field'><label>摘要：</label>" . htmlspecialchars($data['summary']) . "</div>";
            echo "<div class='field'><label>出版社：</label>" . htmlspecialchars($data['publisher']) . "</div>";
            echo "<div class='field'><label>ISBN：</label>" . htmlspecialchars($data['isbn']) . "</div>";
            echo "<div class='field'><label>章節資訊：</label>" . htmlspecialchars($data['chapter_info']) . "</div>";
            echo "<div class='field'><label>書籍類型：</label>" . htmlspecialchars($data['book_type']) . "</div>";
            echo "<div class='field'><label>上傳日期：</label>" . htmlspecialchars($data['upload_date']) . "</div>";
            echo "<div class='field'><label>備註：</label>" . htmlspecialchars($data['remarks']) . "</div>";
            break;

        case 'np': // 國科會計畫
            echo "<div class='field'><label>專案標題：</label>" . htmlspecialchars($data['title']) . "</div>";
            echo "<div class='field'><label>作者：</label>" . htmlspecialchars($data['author']) . "</div>";
            echo "<div class='field'><label>摘要：</label>" . htmlspecialchars($data['summary']) . "</div>";
            echo "<div class='field'><label>專案編號：</label>" . htmlspecialchars($data['project_number']) . "</div>";
            echo "<div class='field'><label>資助機構：</label>" . htmlspecialchars($data['funding_agency']) . "</div>";
            echo "<div class='field'><label>資助金額：</label>" . htmlspecialchars($data['amount']) . "</div>";
            echo "<div class='field'><label>專案開始日期：</label>" . htmlspecialchars($data['starts_date']) . "</div>";
            echo "<div class='field'><label>專案結束日期：</label>" . htmlspecialchars($data['end_date']) . "</div>";
            echo "<div class='field'><label>上傳日期：</label>" . htmlspecialchars($data['upload_date']) . "</div>";
            echo "<div class='field'><label>備註：</label>" . htmlspecialchars($data['remarks']) . "</div>";
            break;

        case 'ind': // 產學合作
            echo "<div class='field'><label>專案標題：</label>" . htmlspecialchars($data['title']) . "</div>";
            echo "<div class='field'><label>作者：</label>" . htmlspecialchars($data['author']) . "</div>";
            echo "<div class='field'><label>摘要：</label>" . htmlspecialchars($data['outcome']) . "</div>";
            echo "<div class='field'><label>合作單位：</label>" . htmlspecialchars($data['partners']) . "</div>";
            echo "<div class='field'><label>金額：</label>" . htmlspecialchars($data['amount']) . "</div>";
            echo "<div class='field'><label>資助機構：</label>" . htmlspecialchars($data['signed_date']) . "</div>";
            echo "<div class='field'><label>上傳日期：</label>" . htmlspecialchars($data['upload_date']) . "</div>";
            echo "<div class='field'><label>備註：</label>" . htmlspecialchars($data['remarks']) . "</div>";
            break;
    }
    ?>
    
  </div>
</body>
</html>
<?php
include('../common/footer.php');