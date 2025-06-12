<?php
include '../common/db.php';
include '../common/header.php';

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

$stmt2 = $conn->prepare("SELECT teachers_id FROM Teacher_research WHERE results_id = ?");
$stmt2->bind_param("s", $result_id);
$stmt2->execute();
$res2 = $stmt2->get_result();
if ($res2->num_rows === 0) {
    echo "<div class='container'><p>❌ 錯誤：找不到這筆研究成果</p></div>";
    exit;
}
$teacher_id = $res2->fetch_assoc()['teachers_id'];

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
  <div class="page-content">
    <h2>詳細資訊</h2>
    
    <?php
    switch ($type) {
        case 'ja': // 期刊論文
            echo "<table>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>文獻標題</th><td style='padding: 10px;'>" . htmlspecialchars($data['title']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>作者</th><td style='padding: 10px;'>" . htmlspecialchars($data['author']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>摘要</th><td style='padding: 10px;'>" . htmlspecialchars($data['summary']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>卷號</th><td style='padding: 10px;'>" . htmlspecialchars($data['volume']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>期號</th><td style='padding: 10px;'>" . htmlspecialchars($data['issue']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>頁碼</th><td style='padding: 10px;'>" . htmlspecialchars($data['pages']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>DOI</th><td style='padding: 10px;'>" . htmlspecialchars($data['doi']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>上傳日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['upload_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>APA格式</th><td style='padding: 10px;'>" . htmlspecialchars($data['APA']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>備註</th><td style='padding: 10px;'>" . htmlspecialchars($data['remarks']) . "</td></tr>";
            echo "</table>";
            break;

        case 'cp': // 會議論文
            echo "<table>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>論文標題</th><td style='padding: 10px;'>" . htmlspecialchars($data['title']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>作者</th><td style='padding: 10px;'>" . htmlspecialchars($data['author']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>摘要</th><td style='padding: 10px;'>" . htmlspecialchars($data['summary']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>會議名稱</th><td style='padding: 10px;'>" . htmlspecialchars($data['conference_name']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>地點</th><td style='padding: 10px;'>" . htmlspecialchars($data['locations']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>會議日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['conference_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>上傳日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['upload_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>備註</th><td style='padding: 10px;'>" . htmlspecialchars($data['remarks']) . "</td></tr>";
            echo "</table>";
            break;

        case 'br': // 專書與報告
            echo "<table>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>書籍名稱</th><td style='padding: 10px;'>" . htmlspecialchars($data['title']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>作者</th><td style='padding: 10px;'>" . htmlspecialchars($data['author']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>摘要</th><td style='padding: 10px;'>" . htmlspecialchars($data['summary']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>出版社</th><td style='padding: 10px;'>" . htmlspecialchars($data['publisher']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>ISBN</th><td style='padding: 10px;'>" . htmlspecialchars($data['isbn']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>章節資訊</th><td style='padding: 10px;'>" . htmlspecialchars($data['chapter_info']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>書籍類型</th><td style='padding: 10px;'>" . htmlspecialchars($data['book_type']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>上傳日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['upload_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>備註</th><td style='padding: 10px;'>" . htmlspecialchars($data['remarks']) . "</td></tr>";
            echo "</table>";
            break;

        case 'np': // 國科會計畫
            echo "<table>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>專案標題</th><td style='padding: 10px;'>" . htmlspecialchars($data['title']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>作者</th><td style='padding: 10px;'>" . htmlspecialchars($data['author']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>摘要</th><td style='padding: 10px;'>" . htmlspecialchars($data['summary']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>專案編號</th><td style='padding: 10px;'>" . htmlspecialchars($data['project_number']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>資助機構</th><td style='padding: 10px;'>" . htmlspecialchars($data['funding_agency']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>資助金額</th><td style='padding: 10px;'>" . htmlspecialchars($data['amount']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>專案開始日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['starts_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>專案結束日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['end_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>上傳日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['upload_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>備註</th><td style='padding: 10px;'>" . htmlspecialchars($data['remarks']) . "</td></tr>";
            echo "</table>";
            break;

        case 'ind': // 產學合作
            echo "<table>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>專案標題</th><td style='padding: 10px;'>" . htmlspecialchars($data['title']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>作者</th><td style='padding: 10px;'>" . htmlspecialchars($data['author']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>摘要</th><td style='padding: 10px;'>" . htmlspecialchars($data['outcome']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>合作單位</th><td style='padding: 10px;'>" . htmlspecialchars($data['partners']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>金額</th><td style='padding: 10px;'>" . htmlspecialchars($data['amount']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>資助機構</th><td style='padding: 10px;'>" . htmlspecialchars($data['signed_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>上傳日期</th><td style='padding: 10px;'>" . htmlspecialchars($data['upload_date']) . "</td></tr>";
            echo "<tr><th style='text-align: center; padding: 10px; width: 20%'>備註</th><td style='padding: 10px;'>" . htmlspecialchars($data['remarks']) . "</td></tr>";
            echo "</table>";
            break;
    }
    ?>

    <a href="/~D1285210/teachers/detail.php?id=<?= htmlspecialchars($teacher_id) ?>" style="display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #7f7f7f; color: white; text-decoration: none; border-radius: 5px;">回研究成果列表</a>
  </div>
  <?php include '../common/footer.php'; ?>
