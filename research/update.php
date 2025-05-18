<?php
include '../common/db.php';

$stmt = $conn->prepare("UPDATE research_result
    SET title=?, type1=?, type2=?, publish_date=?, year=?, keywords=?, attachment=?, author=?
    WHERE result_id=?");
$stmt->bind_param("ssssisssi",
    $_POST['title'], $_POST['type1'], $_POST['type2'], $_POST['publish_date'],
    $_POST['year'], $_POST['keywords'], $_POST['attachment'], $_POST['author'],
    $_POST['result_id']
);
$stmt->execute();

header("Location: ../research/list.php");
exit;
