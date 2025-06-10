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
$stmt1 = $conn->prepare("SELECT * FROM journal_articles WHERE teacher_id = ?");
$stmt1->bind_param("s", $teacher_id);
$stmt1->execute();
$result1 = $stmt1->get_result();

$stmt2 = $conn->prepare("SELECT * FROM conference_papers WHERE teacher_id = ?");
$stmt2->bind_param("s", $teacher_id);
$stmt2->execute();
$result2 = $stmt2->get_result();

$stmt3 = $conn->prepare("SELECT * FROM books_reports WHERE teacher_id = ?");
$stmt3->bind_param("s", $teacher_id);
$stmt3->execute();
$result3 = $stmt3->get_result();

$stmt4 = $conn->prepare("SELECT * FROM nstc_projects WHERE teacher_id = ?");
$stmt4->bind_param("s", $teacher_id);
$stmt4->execute();
$result4 = $stmt4->get_result();

$stmt5 = $conn->prepare("SELECT * FROM industry_projects WHERE teacher_id = ?");
$stmt5->bind_param("s", $teacher_id);
$stmt5->execute();
$result5 = $stmt5->get_result();
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
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/journal_article/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
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
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/conference_paper/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
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
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/books_reports/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
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
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/nstc_projects/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
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
                <a href="/~D1285210/research/edit.php?id=<?= $r['result_id'] ?>">✏️ 編輯</a> |
                <a href="/~D1285210/industry_projects/delete.php?id=<?= $r['result_id'] ?>" onclick="return confirm('確定刪除？')">🗑️ 刪除</a>
            </td>
        </tr>
        <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>
