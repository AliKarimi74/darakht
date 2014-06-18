<?php

session_start();

if (isset($_SESSION['user_id'])) {
    $_SESSION = array();
    //if (isset($_COOKIE[session_name()])) {setcookie(session_name(), '', time() - 360000);}
    session_destroy();
}

//setcookie('user_id', '', time() - 360000);
//setcookie('display_name', '', time() - 360000);

$home_url = 'http://' . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']) . '/homepage/homepage.php';
header('Location: ' . $home_url);
?>