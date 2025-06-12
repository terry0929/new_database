
<?php
session_start();
include('../common/db.php');
include('../common/header.php');

$user_id = $_SESSION['user_id'] ?? null;
if (!$user_id) {
    echo "<div class='page-content'><p>請先登入。</p></div>";
    include('../common/footer.php'); exit;
}

$search = $_GET['search'] ?? '';

// 建立名稱對應（學生＋老師）
$name_map = [];

// 老師名稱對應
$res = $conn->query("SELECT teacher_id, name FROM teacher");
while ($r = $res->fetch_assoc()) {
    $name_map[$r['teacher_id']] = $r['name'];
}

// 學生名稱對應
$res = $conn->query("SELECT student_id, name FROM student_account");
while ($r = $res->fetch_assoc()) {
    $name_map[$r['student_id']] = $r['name'];
}

// 撈出所有與自己有關的訊息
$stmt = $conn->prepare("
    SELECT sender_id, receiver_id, message_text, sent_time, is_read
    FROM messages
    WHERE sender_id = ? OR receiver_id = ?
    ORDER BY sent_time DESC
");
$stmt->bind_param("ss", $user_id, $user_id);
$stmt->execute();
$all_msgs = $stmt->get_result();

// 整理對話清單
$dialogues = [];  // contact_id => [msg, time, unread]
while ($row = $all_msgs->fetch_assoc()) {
    $sender = $row['sender_id'];
    $receiver = $row['receiver_id'];
    $contact_id = ($sender == $user_id) ? $receiver : $sender;

    if ($contact_id == $user_id) continue;

    if (!isset($dialogues[$contact_id])) {
        $dialogues[$contact_id] = [
            'contact_id' => $contact_id,
            'last_msg' => $row['message_text'],
            'last_time' => $row['sent_time'],
            'unread' => 0
        ];
    }

    // ✅ 若該訊息是對方傳給我且尚未讀取，加未讀標記
    if ($receiver == $user_id && $row['is_read'] == 0) {
        $dialogues[$contact_id]['unread'] = 1;
    }
}
?>

<div class="page-content">
    <h2>📨 我的對話紀錄</h2>

    <form method="get" style="margin-bottom: 20px;">
        <input type="text" name="search" placeholder="🔍 搜尋老師名稱..." value="<?= htmlspecialchars($search) ?>" style="padding: 8px; width: 250px;">
        <input type="submit" value="搜尋" style="padding: 8px;">
    </form>

    <?php if (count($dialogues) > 0): ?>
        <ul class="chat-list">
            <?php foreach ($dialogues as $d):
                $cid = $d['contact_id'];
                $contact_name = $name_map[$cid] ?? $cid;
                if ($search && strpos($contact_name, $search) === false) continue;
                ?>
                <li style="margin-bottom: 12px;">
                    <a href="chat.php?target_id=<?= urlencode($cid) ?>" style="text-decoration: none;">
                        <?= htmlspecialchars($contact_name) ?>
                        <?php if ($d['unread']): ?>
                            <span style="color:red;">●</span>
                        <?php endif; ?>
                    </a><br>
                    <small style="color:gray;"><?= htmlspecialchars($d['last_msg']) ?> ‧ <?= $d['last_time'] ?></small>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p>❗ 目前沒有任何對話紀錄。</p>
    <?php endif; ?>
</div>

<?php include('../common/footer.php'); ?>
