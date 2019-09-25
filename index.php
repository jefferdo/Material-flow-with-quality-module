<?php
session_start();
if (isset($_SESSION['uid']) && isset($_SESSION['key'])) {
    $user = new User($_SESSION['uid']);
    $user->session();
} else {
    header("Location: /login.html");
    die();
}
