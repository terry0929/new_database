<?php
include '../common/db.php';
include '../common/header.php';

$types = [
    'journal' => '期刊論文',
    'conference' => '會議論文',
    'book' => '專書與技術報告',
    'nstc' => '國科會計劃',
    'industry' => '產學合作計劃'
];

if (!isset($_GET['id'])) {
    echo "<p>未指定 ID</p>"; exit;
}

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM research_result WHERE result_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$data = $stmt->get_result()->fetch_assoc();

if (!$data) {
    echo "<p>資料不存在</p>"; exit;
}
$type1 = $data['type1'] ?? '無';
switch ($type1) {
    case 'journal':
        $Stmt = $conn->prepare("SELECT * FROM journal_articles WHERE result_id = ?");
        break;
    case 'conference':
        $Stmt = $conn->prepare("SELECT * FROM conference_papers WHERE result_id = ?");
        break;
    case 'book':
        $Stmt = $conn->prepare("SELECT * FROM books_reports WHERE result_id = ?");
        break;
    case 'nstc':
        $Stmt = $conn->prepare("SELECT * FROM nstc_projects WHERE result_id = ?");
        break;
    case 'industry':
        $Stmt = $conn->prepare("SELECT * FROM industry_projects WHERE result_id = ?");
        break;
    default:
        $Stmt = $conn->prepare("SELECT * FROM journal_articles WHERE result_id = ?");
        break;
}
$Stmt->bind_param("i", $id);
$Stmt->execute();
$subData = $Stmt->get_result()->fetch_assoc();
?>
<link rel="stylesheet" href="/~D1285210/common/style.css?v=<?= time() ?>">

<script>
function showSubForm(type) {
    const forms = document.querySelectorAll('.subform');
    forms.forEach(form => form.style.display = 'none');
    if (type) {
        const target = document.getElementById(type + 'Form');
        if (target) target.style.display = 'block';
    }
}
</script>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/research/update.php" method="post">
</script>

<div class="page-content">
    <h2>✏️ 編輯研究成果</h2>
    <form action="/~D1285210/research/update.php" method="post">
        <input type="hidden" name="result_id" value="<?= $data['result_id'] ?>">
        <label><h3>標題</h3><input type="text" name="title" value="<?= htmlspecialchars($data['title']) ?>" class="inputFrame"></label><br><br>
        <label><h3>主要填寫人</h3><input type="text" name="author" value="<?= htmlspecialchars($data['author']) ?>" class="inputFrame"></label><br><br>
        <div style="display: flex; width: 100%; align-items: center;">
            <label style="flex: 1; margin-right: 10px;">
                <h3>文獻類型</h3>
                <select name="type1" id="type1" onchange="showSubForm(this.value)" class="inputFrame">
                    <option value="無" <?= empty($data['type1']) || $data['type1'] === '無' ? 'selected' : '' ?>>請選擇</option>
                    <?php foreach ($types as $key => $label): ?>
                        <option value="<?= $key ?>" <?= $data['type1'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                    <?php endforeach; ?>
                </select>
            </label>
            <label style="flex: 1;">
                <h3>成果類型</h3>
                <input type="text" name="type2" value="<?= htmlspecialchars($data['type2']) ?>" style="padding:10px; font-size: 16px; width: 100%;">
            </label>
        </div>
        <br>
        <label><h3>文件上傳日期</h3><input type="date" name="publish_date" value="<?= $data['publish_date'] ?>" class="inputFrame"></label><br><br>
        <div id="journalForm" class="subform" style="display:none;">
            <div style="display: flex; width: 100%; align-items: center;">
                <label style="flex: 1; margin-right: 10px;">
                    <input name="volume" placeholder="卷" class="inputFrame" value="<?= htmlspecialchars($subData['volume']) ?>">
                </label>
                <label style="flex: 1;">
                    <input name="issue" placeholder="期"  value="<?= htmlspecialchars($subData['issue']) ?>"class="inputFrame">
                </label>
            </div>

            <input name="pages" placeholder="頁碼" class="inputFrame" value="<?= htmlspecialchars($subData['pages']) ?>">
            <input name="doi" placeholder="DOI" class="inputFrame" value="<?= htmlspecialchars($subData['doi']) ?>">
            <br>
        </div>

        <div id="conferenceForm" class="subform" style="display:none;">
            <input name="conference_name" placeholder="會議名稱" class="inputFrame" value="<?= htmlspecialchars($subData['conference_name']) ?>">
            <input name="location" placeholder="地點" class="inputFrame" value="<?= htmlspecialchars($subData['location']) ?>">
            <input name="presentation_type" placeholder="發表形式" class="inputFrame" value="<?= htmlspecialchars($subData['presentation_type']) ?>">
            <input name="conference_date" type="date" placeholder="日期" class="inputFrame" value="<?= htmlspecialchars($subData['conference_date']) ?>">
            <br>
        </div>

        <div id="bookForm" class="subform" style="display:none;">
            <input name="book_title" placeholder="書名" class="inputFrame" value="<?= htmlspecialchars($subData['book_title']) ?>">
            <input name="book_author" placeholder="作者們" class="inputFrame" value="<?= htmlspecialchars($subData['book_author']) ?>">
            <input name="book_publisher" placeholder="出版社" class="inputFrame" value="<?= htmlspecialchars($subData['book_publisher']) ?>">
            <input name="book_isbn" placeholder="國際書號" class="inputFrame" value="<?= htmlspecialchars($subData['book_isbn']) ?>">
            <input name="book_chapter_info" placeholder="章節資訊" class="inputFrame" value="<?= htmlspecialchars($subData['book_chapter_info']) ?>">
            <input name="book_year" placeholder="出版年份" class="inputFrame" value="<?= htmlspecialchars($subData['book_year']) ?>">
            <br>
        </div>

        <div id="nstcForm" class="subform" style="display:none;">
            <input name="project_number" placeholder="計畫編號" class="inputFrame" value="<?= htmlspecialchars($subData['project_number']) ?>">
            <input name="funding_agency" placeholder="資助機構" class="inputFrame" value="<?= htmlspecialchars($subData['funding_agency']) ?>">
            <input name="nstc_amount" placeholder="補助金額" class="inputFrame" value="<?= htmlspecialchars($subData['nstc_amount']) ?>">
            <input name="start_date" type="date" placeholder="開始日期" class="inputFrame" value="<?= htmlspecialchars($subData['start_date']) ?>">
            <input name="end_date" type="date" placeholder="結束日期" class="inputFrame" value="<?= htmlspecialchars($subData['end_date']) ?>">
            <select name="status" id="status" onchange="showSubForm(this.value)" class="inputFrame">
                <option value="無">請選擇</option>
                <?php
                    $statusOptions = [
                        'ongoing' => "進行中",
                        'completed' => "已完成",
                        'cancelled' => "已取消"
                    ];
                ?>
                <?php foreach ($statusOptions as $key => $label): ?>
                    <option value="<?= $key ?>" <?= $data['status'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                <?php endforeach; ?>
            </select>
            <br>
        </div>

        <div id="industryForm" class="subform" style="display:none;">
            <input name="partner" placeholder="合作夥伴" class="inputFrame" value="<?= htmlspecialchars($subData['partner']) ?>">
            <input name="coAmount" placeholder="合作金額" class="inputFrame" value="<?= htmlspecialchars($subData['coAmount']) ?>">
            <input name="signed_date" placeholder="簽約日期" class="inputFrame" value="<?= htmlspecialchars($subData['signed_date']) ?>">
            <input name="outcome" placeholder="成果說明" class="inputFrame" value="<?= htmlspecialchars($subData['outcome']) ?>">
            <br>
        </div>
        
        <div style="display: flex; justify-content: center; margin-top: 20px;">
            <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:8px; cursor: pointer; transition: background-color 0.3s;">
        </div>
    </form>
</div>

<?php include '../common/footer.php'; ?>
