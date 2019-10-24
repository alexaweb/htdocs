<?php
	$current = "42";
	$page_category = "admin";
	$page_name = "usuarios";
	require_once('../../includes/home_common.php');

    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: login.php");

        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to login.php");
    }

    // Everything below this point in the file is secured by the login system

    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.
    $query = "
        SELECT
            id,
            username,
            email
        FROM users
    ";

    try
    {
        // These two statements run the query against your database table.
        $stmt = $db->prepare($query);
        $stmt->execute();
    }
    catch(PDOException $ex)
    {
        // Note: On a production website, you should not output $ex->getMessage().
        // It may provide an attacker with helpful information about your code.
        die("Failed to run query: " . $ex->getMessage());
    }

    // Finally, we can retrieve all of the found rows into an array using fetchAll
    $rows = $stmt->fetchAll();
?>


<div class="table">
	<div class="rowheader">
		<div class="cell">ID</div>
        <div class="cell">Username</div>
        <div class="textcell">E-Mail</div>
    </div>
    <?php foreach($rows as $row): ?>
    <div class="row">
            <div class="cell"><?php echo $row['id']; ?></div>
            <div class="cell"><?php echo $row['username']; ?></div>
            <div class="textcell"><?php echo $row['email']; ?></div>
    </div>
    <?php endforeach; ?>

</div>

<?php require_once($path_include."/cmifooter.php");
