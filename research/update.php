<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);
include '../common/db.php';

// 更新主資料表
$stmt = $conn->prepare("
    UPDATE research_result SET
    title=?, author=?, type1=?, type2=?, publish_date=?
    WHERE result_id=?
");
$stmt->bind_param("sssssi",
    $_POST['title'],
    $_POST['author'],
    $_POST['type1'],
    $_POST['type2'],
    $_POST['publish_date'],
    $_POST['result_id']
);
$stmt->execute();

// 取得 type1
$type1 = $_POST['type1'];
$result_id = $_POST['result_id'];

// 根據 type1 更新子資料表
switch ($type1) {
    case 'journal':
        $stmt2 = $conn->prepare("
            UPDATE journal_articles SET volume=?, issue=?, pages=?, doi=? 
            WHERE result_id=?
        ");
        $stmt2->bind_param("ssssi",
            $_POST['volume'],
            $_POST['issue'],
            $_POST['pages'],
            $_POST['doi'],
            $result_id
        );
        $stmt2->execute();
        break;
    case 'conference':
        $stmt2 = $conn->prepare("
            UPDATE conference_papers SET conference_name=?, location=?, presentation_type=?, conference_date=? 
            WHERE result_id=?
        ");
        $stmt2->bind_param("ssssi",
            $_POST['conference_name'],
            $_POST['location'],
            $_POST['presentation_type'],
            $_POST['conference_date'],
            $result_id
        );
        $stmt2->execute();
        break;
    case 'book':
        $stmt2 = $conn->prepare("UPDATE books_reports SET book_title=?, book_author=?, book_publisher=?, book_isbn=?, book_chapter_info=?, book_year=? WHERE result_id=?");
        $stmt2->bind_param("ssssssi",
            $_POST['book_title'],
            $_POST['book_author'],
            $_POST['book_publisher'],
            $_POST['book_isbn'],
            $_POST['book_chapter_info'],
            $_POST['book_year'],
            $result_id
        );
        $stmt2->execute();
        break;
    case 'nstc':
        $stmt2 = $conn->prepare("
            UPDATE nstc_projects SET project_number=?, funding_agency=?, nstc_amount=?, start_date=?, end_date=?, status=? 
            WHERE result_id=?
        ");
        $stmt2->bind_param("ssssssi",
            $_POST['project_number'],
            $_POST['funding_agency'],
            $_POST['nstc_amount'],
            $_POST['start_date'],
            $_POST['end_date'],
            $_POST['status'],
            $result_id
        );
        $stmt2->execute();
        break;
    case 'industry':
        $stmt2 = $conn->prepare("
            UPDATE industry_projects SET partner=?, coAmount=?, signed_date=?, outcome=? 
            WHERE result_id=?
        ");
        $stmt2->bind_param("ssssi",
            $_POST['partner'],
            $_POST['coAmount'],
            $_POST['signed_date'],
            $_POST['outcome'],
            $result_id
        );
        $stmt2->execute();
        break;
}

header("Location: list.php");
exit;
?>