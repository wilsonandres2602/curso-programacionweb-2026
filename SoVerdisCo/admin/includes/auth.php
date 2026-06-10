<?php
if (session_status() === PHP_SESSION_NONE) session_start();
if (!isset($_SESSION['admin_id'])) {
    header('Location: login.php');
    exit;
}
$adminName = $_SESSION['admin_name'];
$adminRol  = $_SESSION['admin_rol'];