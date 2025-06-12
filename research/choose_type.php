<?php
include '../common/header.php';

$table = [
    'journal_article' => ['label' => '期刊論文', 'form' => 'ja_form.php'],
    'conference_paper' => ['label' => '會議論文', 'form' => 'cp_form.php'],
    'books_reports' => ['label' => '專書與技術報告', 'form' => 'br_form.php'],
    'nstc_projects' => ['label' => '國科會計劃', 'form' => 'np_form.php'],
    'industry_projects' => ['label' => '產學合作計劃', 'form' => 'ip_form.php']
];
?>

<div class="page-content">
    <h2>🧪 選擇研究成果類型</h2>
    <p>請選擇您要新增的研究成果類型：</p>
    <ul>
        <?php foreach ($table as $key => $data): ?>
            <div class="research-box">
                <a style="color: #333;" href="/~D1285210/<?= htmlspecialchars($key) ?>/<?= htmlspecialchars($data['form']) ?>">
                    <div style="text-align: center;">
                        <h2><?= htmlspecialchars($data['label']) ?></h2>
                    </div>
                </a>
            </div>
        <?php endforeach; ?>
    </ul>
</div>

<?php include '../common/footer.php'; ?>