<?php
	$current=511;
	$dbfile = "csbDB.php";
    // First we execute our common code to connection to the database and start the session
    $path = $_SERVER['DOCUMENT_ROOT'];
    require $path.'/../includes/common.php';
	//require_once('../csb_common.php');
	        
    
    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        
        // Ensure that the user has entered a non-empty password
        if(empty($_POST['fecha']))
        {
            die("Ingresar fecha");
        }
        if(empty($_POST['detalle']))
        {
            die("Ingresar detalle");
        }
	    if(empty($_POST['item_id']))
        {
            die("Ingresar item_id");
        }
		if(empty($_POST['item_descripcion']))
        {
            die("Ingresar item_descripcion");
        }
        
        $status_fecha = $_POST['fecha'];
		$status_detalle = $_POST['detalle'];
		
        $item_id = $_POST['item_id'];
		$item_descripcion = $_POST['item_descripcion'];
      
       
        try
        {
            $sql = "call proc_insert_status('{$item_id}','{$status_fecha}','{$status_detalle}');";
            //echo $sql;
			//die;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
        }
        catch(PDOException $ex)
        {
            die("Failed to run querys: " . $ex->getMessage());
        }
        
        
        // This redirects the user back to the login page after they register
        header("Location: ../display_status_item.php?item_id=$item_id&item_descripcion=$item_descripcion");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_status_item.php");
    }

?>

