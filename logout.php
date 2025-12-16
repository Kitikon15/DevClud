<?php
require_once 'auth.php';
logout_user();
header('Location: login.php?status=logged_out');
exit;
?>