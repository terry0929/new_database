<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';
include '../common/header.php';
session_start(); // 如果 header.php 裡沒有 session_start()，這邊加上

if (
    isset($_POST['title'], $_POST['author'], $_POST['project_number'], $_POST['funding_agency'], $_POST['amount'], $_POST['starts_date'], $_POST['end_date'], $_POST['upload_date'], $_POST['summary'], $_POST['remarks'])
) {
    $title = $_POST['title'];
    $author = $_POST['author'];
    $project_number = $_POST['project_number'];
    $funding_agency = $_POST['funding_agency'];
    $amount = $_POST['amount'];
    $starts_date = $_POST['starts_date'];
    $end_date = $_POST['end_date'];
    $upload_date = $_POST['upload_date'];
    $summary = $_POST['summary'];
    $remarks = $_POST['remarks'];
    $category = 'np';


    // ✅ 抓目前登入使用者的 teacher_id
    $stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
    $stmt->bind_param("s", $_SESSION['user_id']);
    $stmt->execute();
    $result = $stmt->get_result();
    $teacher_row = $result->fetch_assoc();
    $teacher_id = $teacher_row['teacher_id'];

    // ✅ 插入研究成果
    $stmt = $conn->prepare("INSERT INTO nstc_projects
        (teacher_id, category, title, author, project_number, funding_agency, amount, starts_date, end_date, upload_date, summary, remarks)
        VALUES ( ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssssisssss", $teacher_id, $category, $title, $author, $project_number, $funding_agency, $amount, $starts_date, $end_date, $upload_date, $summary, $remarks);
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
