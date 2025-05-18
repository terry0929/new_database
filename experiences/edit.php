<?php
include('../common/db.php');
include('../common/header.php');

$id = $_GET['id'];
$stmt = $conn->prepare("SELECT * FROM experience WHERE experience_id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$row = $stmt->get_result()->fetch_assoc();
?>

<h2>✏️ 編輯經歷</h2>
<form action="experiences/update.php" method="post">
    <input type="hidden" name="experience_id" value="<?= $row['experience_id'] ?>">
    <label>類別:
        <select name="type">
            <option value="in" <?= $row['type'] == 'in' ? 'selected' : '' ?>>校內</option>
            <option value="out" <?= $row['type'] == 'out' ? 'selected' : '' ?>>校外</option>
        </select>
    </label><br>
    <label>內容:<br>
        <textarea name="description" rows="4" cols="50"><?= htmlspecialchars($row['description']) ?></textarea>
    </label><br>
    <input type="submit" value="更新">
</form>

<?php include '../common/footer.php'; ?>
