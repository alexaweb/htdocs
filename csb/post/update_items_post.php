<?php


	$dbfile = "csbDB.php";
    // First we execute our common code to connection to the database and start the session
    //$path = $_SERVER['DOCUMENT_ROOT'];
    require '../../../includes/common.php';

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
		$update_string = $_POST['update_string'];
//echo $update_string;

        try
        {
            $sql = $update_string;
            //echo $sql;
			//die;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


        // This redirects the user back to the login page after they register
        header("Location: ../display_items_pendientes.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_items_pendientes.php");
    }

?>
