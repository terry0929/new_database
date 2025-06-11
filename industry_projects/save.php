<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';
session_start(); // 如果 header.php 裡沒有 session_start()，這邊加上

if (
    isset($_POST['title'], $_POST['author'], $_POST['partners'], $_POST['amount'], $_POST['signed_date'], $_POST['outcome'], $_POST['upload_date'], $_POST['remarks'])
) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $partners = $_POST['partners'];
    $amount = $_POST['amount'];
    $signed_date = $_POST['signed_date'];
    $outcome = $_POST['outcome'];
    $upload_date = $_POST['upload_date'];
    $remarks = $_POST['remarks'];
    


    // ✅ 抓目前登入使用者的 teacher_id
    $stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher_row = $result->fetch_assoc();
    $teacher_id = $teacher_row['teacher_id'];
    $category = 'ind';

    // ✅ 插入研究成果
    $stmt = $conn->prepare("INSERT INTO industry_projects
        (teacher_id, category, title, author, partners, amount, signed_date, outcome, upload_date, remarks)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssissss", $teacher_id, $category, $title, $author, $partners, $amount, $signed_date, $outcome, $upload_date, $remarks);
    $stmt->execute();

    $new_result_id = $conn->insert_id;
    $link = $conn->prepare("INSERT INTO teacher_research (teacher_id, result_id, category) VALUES (?, ?, ?)");
    $link->bind_param("sis", $teacher_id, $new_result_id, $category);
    $link->execute();

    header("Location: /~D1285210/research/list.php");
    exit;

} else {
    echo "<div class='page-content'><p>⚠️ 請填寫所有欄位</p></div>";
}

include '../common/footer.php';
?>
