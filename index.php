<?php
session_start();
include "config.php";

// If user is already logged in, redirect to appropriate dashboard
if (isset($_SESSION['user']) && isset($_SESSION['role'])) {
    $role = strtolower(trim($_SESSION['role']));

    if ($role == 'admin') {
        header("Location: dashboard_admin.php");
        exit();
    } elseif ($role == 'user') {
        header("Location: dashboard_user.php");
        exit();
    }
}

// If not logged in, go to home page
