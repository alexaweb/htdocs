<?php
	$current = "41";
	$page_category = "admin";
	$page_name = "home";
	require_once('../../includes/home_common.php');

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: /home/login.php");

        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to login.php");
    }

    // Everything below this point in the file is secured by the login system

    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.

?>

<img src="../images/intranet.jpg" align="center" alt="" width="100%" title="">

<?php require_once($path_include."/cmifooter.php");
