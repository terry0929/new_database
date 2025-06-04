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
    <p><a href="/~D1285210/edit_mode.php">🔙 回到控制台</a></p>
    <h2>👨‍🏫 教師介紹 - <?= htmlspecialchars($teacher['name']) ?></h2>

    <div style="display: flex; gap: 30px; align-items: flex-start;">
        <div>
            <img src="<?= $photoPath ?>" alt="大頭照" width="150" style="border-radius: 8px; border: 1px solid #ccc;">
        </div>
        <div>
            <ul>
                <li><strong>信箱：</strong><?= htmlspecialchars($teacher['email']) ?></li>
                <li><strong>電話：</strong><?= htmlspecialchars($teacher['phone']) ?></li>
                <li><strong>職稱：</strong><?= htmlspecialchars($teacher['title']) ?></li>
                <li><strong>學歷：</strong><?= htmlspecialchars($teacher['education']) ?></li>
                <li><strong>研究領域：</strong><?= htmlspecialchars($teacher['research_field']) ?></li>
            </ul>
        </div>
    </div>

    <h3>📚 教師經歷</h3>
    <ul>
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

    <h3>🧪 研究成果</h3>
    <ul>
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
        <li><?= htmlspecialchars($r['title']) ?>（<?= htmlspecialchars($r['publish_date']) ?>）</li>
    <?php
        endwhile;
    else:
        echo "<li>尚無研究成果資料。</li>";
    endif;
    ?>
    </ul>
    </div>

<?php include '../common/footer.php'; ?>
