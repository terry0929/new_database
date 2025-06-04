<?php
include('../common/db.php');
include('../common/header.php');

$result = $conn->query("SELECT * FROM teacher ORDER BY teacher_id ASC");
?>

<div class="page-content">
    <h2>👨‍🏫 教師清單</h2>
    <table border="1" cellpadding="8">
        <tr>
            <th>姓名</th><th>信箱</th><th>電話</th><th>職稱</th><th>詳細資料</th>
        </tr>
    <?php while ($row = $result->fetch_assoc()): ?>
        <tr>
            <td><?= $row['name'] ?></td>
            <td><?= $row['email'] ?></td>
            <td><?= $row['phone'] ?></td>
            <td><?= $row['title'] ?></td>
            <td><a href="/~D1285210/teachers/detail.php?id=<?= $row['teacher_id'] ?>">🔍 查看</a></td>
        </tr>
    <?php endwhile; ?>
    </table>
</div>

<?php include '../common/footer.php'; ?>
