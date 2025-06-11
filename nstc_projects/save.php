<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';
session_start(); // 如果 header.php 裡沒有 session_start()，這邊加上

if (
    isset($_POST['title'], $_POST['author'], $_POST['summary'], $_POST['project_number'], $_POST['funding_agency'], $_POST['amount'], $_POST['starts_date'], $_POST['end_date'], $_POST['upload_date'], $_POST['remarks'])
) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $summary = $_POST['summary'];
    $project_number = $_POST['project_number'];
    $funding_agency = $_POST['funding_agency'];
    $amount = $_POST['amount'];
    $starts_date = $_POST['starts_date'];
    $end_date = $_POST['end_date'];
    $upload_date = $_POST['upload_date'];
    $remarks = $_POST['remarks'];
    $type = 'np';
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
    $stmt = $conn->prepare("INSERT INTO nstc_projects
        (result_id, title, author, summary, project_number, funding_agency, amount, starts_date, end_date, upload_date, remarks)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssissss", $result_id, $title, $author, $summary, $project_number, $funding_agency, $amount, $starts_date, $end_date, $upload_date, $remarks);
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
