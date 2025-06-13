<?php
session_start();
include('../common/db.php');
include('../common/header.php');

$current_user = $_SESSION['user_id'] ?? null;
$target_id = $_GET['target_id'] ?? null;

if (!$current_user || !$target_id) {
    echo "<div class='page-content'><p>⚠️ 錯誤：未登入或未指定聊天對象</p></div>";
    include('../common/footer.php');
    exit;
}

// ⬇️ 查對方姓名（先查老師，再查學生）
$target_name = null;

// 若是老師 ID（如 T001）
$stmt = $conn->prepare("SELECT name FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $target_id);
$stmt->execute();
$res = $stmt->get_result();
if ($res->num_rows > 0) {
    $target_name = $res->fetch_assoc()['name'];
} else {
    // 若是學生 ID（如 1）
    $stmt = $conn->prepare("SELECT name FROM student_account WHERE student_id = ?");
    $stmt->bind_param("s", $target_id);
    $stmt->execute();
    $res = $stmt->get_result();
    if ($res->num_rows > 0) {
        $target_name = $res->fetch_assoc()['name'];  // ✅ student_account 有 name 欄位
    }
}

if (!$target_name) $target_name = $target_id;

// ⬇️ 發送新訊息
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $message = trim($_POST['message'] ?? '');
    if (!empty($message)) {
        $stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message_text) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $current_user, $target_id, $message);
        $stmt->execute();
    }
}

// ⬇️ 取得雙方訊息紀錄
$stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE (sender_id = ? AND receiver_id = ?) 
       OR (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_time ASC
");
$stmt->bind_param("ssss", $current_user, $target_id, $target_id, $current_user);
$stmt->execute();
$messages = $stmt->get_result();
?>

<div class="page-content">
    <a href="index.php" class="back-link" style="display: inline-block; margin-top: 20px; padding: 10px 15px; background-color: #7f7f7f; color: white; text-decoration: none; border-radius: 5px;">回到對話列表</a>
    <h2>💬 與 <?= htmlspecialchars($target_name) ?> 的對話</h2>

    <div class="chat-box">
    <?php while ($row = $messages->fetch_assoc()): ?>
        <?php
            $is_me = ($row['sender_id'] == $current_user);
            $display_name = $is_me ? '我' : $target_name;
        ?>
        <div class="message <?= $is_me ? 'me' : 'other' ?>">
            <span><?= nl2br(htmlspecialchars($row['message_text'])) ?></span><br>
            <small><?= htmlspecialchars($display_name) ?> · <?= $row['sent_time'] ?></small>
        </div>
    <?php endwhile; ?>
    </div>


    <form method="post" class="message-form">
        <textarea name="message" rows="3" required placeholder="輸入訊息..."></textarea><br>
        <button type="submit">傳送</button>
    </form>
</div>

<script>
window.addEventListener('DOMContentLoaded', function () {
    const chatBox = document.querySelector('.chat-box');
    if (chatBox) {
        chatBox.scrollTop = chatBox.scrollHeight;
    }
});
</script>


<style>
.back-link {
    display: inline-block;
    margin-bottom: 10px;
    text-decoration: none;
    color: #007bff;
}
.back-link:hover {
    text-decoration: underline;
}
.chat-box {
    max-height: 400px;
    overflow-y: auto;
    background: #f8f8f8;
    padding: 10px;
    margin-bottom: 20px;
    border-radius: 6px;
    border: 1px solid #ccc;
}
.message {
    margin: 10px 0;
    padding: 8px;
    border-radius: 6px;
    max-width: 70%;
}
.message.me {
    background: #d4f1d4;
    text-align: right;
    margin-left: auto;
    color: #333;
}

.message.other {
    background: #e3e3ff;
    text-align: left;
    margin-right: auto;
    color: #333;
}

.message-form {
    display: flex;
    flex-direction: column;
    align-items: flex-end; /* 將按鈕置右 */
}

.message-form textarea {
    width: 100%;
    font-size: 14px;
    padding: 10px;
    resize: none;
}
.message-form button {
    padding: 6px 20px;
    font-size: 14px;
    background: #4CAF50;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.message-form button:hover {
    background: #45a049; /* 按鈕 hover 效果 */
}
</style>

<?php include('../common/footer.php'); ?>
