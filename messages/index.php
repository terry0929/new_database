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
    $name_map[$r['student_id']] = $r['name'];  // ✅ 修正這行
}

// 取得與自己有關的所有訊息
$stmt = $conn->prepare("
    SELECT sender_id, receiver_id, message_text, sent_time 
    FROM messages 
    WHERE sender_id = ? OR receiver_id = ?
    ORDER BY sent_time DESC
");
$stmt->bind_param("ss", $user_id, $user_id);
$stmt->execute();
$all_msgs = $stmt->get_result();

// 整理對話對象
$dialogues = []; // contact_id => [msg, time]

while ($row = $all_msgs->fetch_assoc()) {
    $sender = $row['sender_id'];
    $receiver = $row['receiver_id'];

    $contact_id = ($sender == $user_id) ? $receiver : $sender;

    // ❗排除「自己傳給自己」
    if ($contact_id == $user_id) continue;

    if (!isset($dialogues[$contact_id])) {
        $dialogues[$contact_id] = [
            'contact_id' => $contact_id,
            'last_msg' => $row['message_text'],
            'last_time' => $row['sent_time']
        ];
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
            ?>
                <li>
                    <a href="chat.php?target_id=<?= $cid ?>">
                        <div class="chat-item">
                            👤 <?= htmlspecialchars($contact_name) ?><br>
                            <small><?= htmlspecialchars($d['last_msg']) ?> · <?= substr($d['last_time'], 0, 10) ?></small>
                        </div>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>
    <?php else: ?>
        <p style="text-align:center; font-size: 18px; color: #777;">❗目前尚無任何對話</p>
    <?php endif; ?>

    <hr><br>

    <?php
    if (!empty($search)) {
        $like = "%" . $search . "%";
        $stmt = $conn->prepare("SELECT teacher_id, name FROM teacher WHERE name LIKE ?");
        $stmt->bind_param("s", $like);
        $stmt->execute();
        $teachers = $stmt->get_result();

        if ($teachers->num_rows > 0) {
            echo "<ul class='chat-list'>";
            while ($t = $teachers->fetch_assoc()) {
                echo "<li><a href='chat.php?target_id={$t['teacher_id']}'><div class='chat-item'>👨‍🏫 " . htmlspecialchars($t['name']) . "</div></a></li>";
            }
            echo "</ul>";
        } else {
            echo "<p>查無符合的老師。</p>";
        }
    }
    ?>
</div>

<style>
.chat-list { list-style: none; padding: 0; }
.chat-item {
    border-bottom: 1px solid #ccc;
    padding: 10px;
}
.chat-item:hover {
    background-color: #eef;
}
</style>

<?php include('../common/footer.php'); ?>
