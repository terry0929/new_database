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
        <label><h3>姓名</h3><input type="text" name="name" value="<?= $row['name'] ?>" style="width:80%; padding: 10px; font-size: 16px;"></label><br><br>
        <label><h3>信箱</h3><input type="email" name="email" value="<?= $row['email'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>電話</h3><input type="text" name="phone" value="<?= $row['phone'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>職稱</h3><input type="text" name="title" value="<?= $row['title'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>學歷</h3><input type="text" name="education" value="<?= $row['education'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
        <label><h3>研究領域</h3><input type="text" name="research_field" value="<?= $row['research_field'] ?>" style="width:80%; padding:10px; font-size: 16px;"></label><br><br>
    <label><h3>大頭照</h3><input type="file" name="photo"></label><br><br>
    <div style="display: flex; justify-content: center; margin-top: 20px;">
        <input type="submit" value="更新" style="padding: 10px 20px; width: 60%; font-size: 16px; background-color: #4CAF50; color: white; border: none; border-radius:4px; cursor: pointer; transition: background-color 0.3s;">
    </div>
</form>

<?php include '../common/footer.php'; ?>
