<?php
session_start();
include('../common/db.php');
include('../common/header.php');

if (!isset($_SESSION['user_id'])) {
    echo "<p>請先登入。</p>";
    include 'common/footer.php';
    exit;
}

$stmt = $conn->prepare("SELECT teacher_id FROM user_account WHERE user_id = ?");
$stmt->bind_param("s", $_SESSION['user_id']);
$stmt->execute();
$teacher_id = $stmt->get_result()->fetch_assoc()['teacher_id'];

$stmt = $conn->prepare("SELECT * FROM teacher WHERE teacher_id = ?");
$stmt->bind_param("s", $teacher_id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<div class="page-content">
    <h2>✏️ 編輯個人資料</h2>
    <form action="/~D1285210/teachers/update.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="teacher_id" value="<?= $row['teacher_id'] ?>">
        <label>姓名: <input type="text" name="name" value="<?= $row['name'] ?>"></label><br>
        <label>信箱: <input type="email" name="email" value="<?= $row['email'] ?>"></label><br>
        <label>電話: <input type="text" name="phone" value="<?= $row['phone'] ?>"></label><br>
        <label>職稱: <input type="text" name="title" value="<?= $row['title'] ?>"></label><br>
        <label>學歷: <input type="text" name="education" value="<?= $row['education'] ?>"></label><br>
        <label>研究領域: <input type="text" name="research_field" value="<?= $row['research_field'] ?>"></label><br>
    <label>大頭照: <input type="file" name="photo"></label><br>
    <input type="submit" value="更新">
</form>

<?php include '../common/footer.php'; ?>
