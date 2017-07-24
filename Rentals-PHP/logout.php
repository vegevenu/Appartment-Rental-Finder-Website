<?php
session_start();
unset($_SESSION['username']);
unset($_SESSION['status']);
unset($_SESSION['user_id']);
header('Location: index.php');
?>