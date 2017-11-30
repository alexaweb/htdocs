<?php
$current = "0";
	require_once('../../includes/home_common.php');

    // We remove the user's data from the session
    unset($_SESSION['user']);

    // We redirect them to the root page
    header("Location: /audit/index.php");
    die("Redirecting to: /audit/index.php");
?>
