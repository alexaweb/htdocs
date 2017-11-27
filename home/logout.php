<?php
$current = "0";
	require_once('home_common.php');

    // We remove the user's data from the session
    unset($_SESSION['user']);

    // We redirect them to the root page
    header("Location: /index.php");
    die("Redirecting to: /index.php");
?>
