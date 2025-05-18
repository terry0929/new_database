<?php
session_start();
session_destroy();
echo "<script>alert('您已成功登出！'); location.href='index.php';</script>";
exit;
