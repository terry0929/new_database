<?php
$servername = "127.0.0.1";
$username = "D1285210";
$password = "#3h4qXnRP";
$dbname = "D1285210";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("連線失敗：" . $conn->connect_error);
}
