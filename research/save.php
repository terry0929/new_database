<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';
session_start(); // 如果 header.php 裡沒有 session_start()，這邊加上

if (
    isset($_POST['title'], $_POST['author'],$_POST['type1'], $_POST['type2'], $_POST['publish_date'],
          )
) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $type1 = $_POST['type1'];
    $type2 = $_POST['type2'];
    $publish_date = $_POST['publish_date'];


    // ✅ 抓目前登入使用者的 teacher_id
    $stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher_row = $result->fetch_assoc();
    $teacher_id = $teacher_row['teacher_id'];

    // ✅ 插入研究成果
    $stmt = $conn->prepare("INSERT INTO research_result
        (title, author, type1, type2, publish_date, teacher_id)
        VALUES ( ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $title, $author, $type1, $type2, $publish_date, $teacher_id);
    $stmt->execute();

    $new_result_id = $conn->insert_id;
    $link = $conn->prepare("INSERT INTO teacher_research (teacher_id, result_id) VALUES (?, ?)");
    $link->bind_param("si", $teacher_id, $new_result_id);
    $link->execute();

    header("Location: /~D1285210/research/list.php");
    exit;

} else {
    echo "<div class='page-content'><p>⚠️ 請填寫所有欄位</p></div>";
}

include '../common/footer.php';
?>
