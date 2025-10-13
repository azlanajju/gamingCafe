<?php
// Redirect to login page or dashboard
require_once 'config/config.php';
require_once 'config/auth.php';

if (Auth::check()) {
    header('Location: pages/dashboard.php');
} else {
    header('Location: login.php');
}
exit;

