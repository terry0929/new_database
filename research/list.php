<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

include '../common/db.php';
include '../common/header.php';

// 取得目前登入者的 teacher_id
$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$result_tid = $stmt->get_result();
$teacher_row = $result_tid->fetch_assoc();
$teacher_id = $teacher_row['teacher_id'];

// 查詢該教師自己的研究成果
$sql1 = "SELECT r.result_id, j.title, j.author, j.summary, j.upload_date
        FROM Teacher_research tr
        JOIN researchs_result r ON tr.results_id = r.result_id
        JOIN journal_articles j ON r.result_id = j.result_id
        WHERE tr.teachers_id = ?
        ORDER BY r.created_at DESC";
$result1 = $conn->prepare($sql1);
$result1->bind_param("s", $teacher_id);
$result1->execute();
$result1 = $result1->get_result();

$sql2 = "SELECT r.result_id, c.title, c.author, c.summary, c.upload_date
        FROM Teacher_research tr
        JOIN researchs_result r ON tr.results_id = r.result_id
        JOIN conference_papers c ON r.result_id = c.result_id
        WHERE tr.teachers_id = ?
        ORDER BY r.created_at DESC";
$result2 = $conn->prepare($sql2);
$result2->bind_param("s", $teacher_id);
$result2->execute();
$result2 = $result2->get_result();

$sql3 = "SELECT r.result_id, b.title, b.author, b.summary, b.upload_date
        FROM Teacher_research tr
        JOIN researchs_result r ON tr.results_id = r.result_id
        JOIN books_reports b ON r.result_id = b.result_id
        WHERE tr.teachers_id = ?
        ORDER BY r.created_at DESC";
$result3 = $conn->prepare($sql3);
$result3->bind_param("s", $teacher_id);
$result3->execute();
$result3 = $result3->get_result();

$sql4 = "SELECT r.result_id, n.title, n.author, n.summary, n.upload_date
        FROM Teacher_research tr
        JOIN researchs_result r ON tr.results_id = r.result_id
        JOIN nstc_projects n ON r.result_id = n.result_id
        WHERE tr.teachers_id = ?
        ORDER BY r.created_at DESC";
$result4 = $conn->prepare($sql4);
$result4->bind_param("s", $teacher_id);
$result4->execute();
$result4 = $result4->get_result();

$sql5 = "SELECT r.result_id, i.title, i.author, i.outcome, i.upload_date
        FROM Teacher_research tr
        JOIN researchs_result r ON tr.results_id = r.result_id
        JOIN industry_projects i ON r.result_id = i.result_id
        WHERE tr.teachers_id = ?
        ORDER BY r.created_at DESC";
$result5 = $conn->prepare($sql5);
$result5->bind_param("s", $teacher_id);
$result5->execute();
$result5 = $result5->get_result();
?>


<div class="page-content">
    <h2>🧪 我的研究成果</h2>
    <!-- <p><a href="/~D1285210/research/form.php">➕ 新增成果</a></p> -->
    <p><a href="/~D1285210/research/choose_type.php">➕ 新增成果</a></p>

    <h3>期刊論文</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>作者</th>
            <th>作品簡述</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result1->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['author']) ?></td>
            <td><?= htmlspecialchars($r['summary']) ?></td>
            <td><?= htmlspecialchars($r['upload_date']) ?></td>
            <td>
                <a href="/~D1285210/journal_article/ja_edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/journal_article/ja_delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>會議論文</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>作者</th>
            <th>作品簡述</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result2->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['author']) ?></td>
            <td><?= htmlspecialchars($r['summary']) ?></td>
            <td><?= htmlspecialchars($r['upload_date']) ?></td>
            <td>
                <a href="/~D1285210/conference_paper/cp_edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/conference_paper/cp_delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>專書與技術報告</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>作者</th>
            <th>作品簡述</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result3->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['author']) ?></td>
            <td><?= htmlspecialchars($r['summary']) ?></td>
            <td><?= htmlspecialchars($r['upload_date']) ?></td>
            <td>
                <a href="/~D1285210/books_reports/br_edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/books_reports/br_delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>國科會計劃</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>作者</th>
            <th>作品簡述</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result4->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['author']) ?></td>
            <td><?= htmlspecialchars($r['summary']) ?></td>
            <td><?= htmlspecialchars($r['upload_date']) ?></td>
            <td>
                <a href="/~D1285210/nstc_projects/np_edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/nstc_projects/np_delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
    <h3>產學合作計劃</h3>
    <table class="styled-table">
        <tr>
            <th>標題</th>
            <th>作者</th>
            <th>作品簡述</th>
            <th>發表日期</th>
            <th>操作</th>
        </tr>
        <?php while ($r = $result5->fetch_assoc()): ?>
        <tr>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><?= htmlspecialchars($r['author']) ?></td>
            <td><?= htmlspecialchars($r['outcome']) ?></td>
            <td><?= htmlspecialchars($r['upload_date']) ?></td>
            <td>
                <a href="/~D1285210/industry_projects/ind_edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/industry_projects/ind_delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>


<!-- ALTER TABLE conference_papers
ADD COLUMN result_id VARCHAR(50); -->
<!-- 
ALTER TABLE conference_papers
ADD CONSTRAINT fk_cp_r
FOREIGN KEY (result_id) REFERENCES researchs_result(result_id) ON DELETE CASCADE; -->

<!-- ALTER TABLE conference_papers
ADD COLUMN id INT(50) PRIMARY KEY AUTO_INCREMENT; -->