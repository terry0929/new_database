<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';
session_start(); // 如果 header.php 裡沒有 session_start()，這邊加上

if (
    isset($_POST['title'], $_POST['author'], $_POST['summary'], $_POST['volume'], $_POST['issue'], $_POST['pages'], $_POST['doi'], $_POST['upload_date'], $_POST['APA'], $_POST['remarks'])
) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $summary = $_POST['summary'];
    $volume = $_POST['volume'];
    $issue = $_POST['issue'];
    $pages = $_POST['pages'];
    $doi = $_POST['doi'];
    $upload_date = $_POST['upload_date'];
    $APA = $_POST['APA'];
    $remarks = $_POST['remarks'];
    $type = 'ja';
    $result_id = uniqid('R');


    // ✅ 抓目前登入使用者的 teacher_id
    $stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher_row = $result->fetch_assoc();
    $teacher_id = $teacher_row['teacher_id'];

    $stmt = $conn->prepare("INSERT INTO researchs_result (result_id, type) VALUES (?, ?)");
    $stmt->bind_param("ss", $result_id, $type);
    $stmt->execute();

    // ✅ 插入研究成果
    $stmt = $conn->prepare("INSERT INTO journal_articles
        (result_id, title, author, summary, volume, issue, pages, doi, upload_date, APA, remarks)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssiisssss", $result_id, $title, $author, $summary, $volume, $issue, $pages, $doi, $upload_date, $APA, $remarks);
    $stmt->execute();

    $link = $conn->prepare("INSERT INTO Teacher_research (teachers_id, results_id) VALUES (?, ?)");
    $link->bind_param("ss", $teacher_id, $result_id);
    $link->execute();

    header("Location: /~D1285210/research/list.php");
    exit;

} else {
    echo "<div class='page-content'><p>⚠️ 請填寫所有欄位</p></div>";
}

include '../common/footer.php';
?>
