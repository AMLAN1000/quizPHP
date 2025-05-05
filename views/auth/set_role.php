<?php
session_start();
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['role'])) {
    $_SESSION['role'] = $_POST['role'];
    header("Location: login.php");
    exit();
} else {
    header("Location: role_select.php");
    exit();
}
?>