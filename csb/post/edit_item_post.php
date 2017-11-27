<?php

    	
	$dbfile = "csbDB.php";
    // First we execute our common code to connection to the database and start the session
    $path = $_SERVER['DOCUMENT_ROOT'];
    require $path.'/../includes/common.php';
 
    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
		$item_id = $_POST['item_id'];
		$item_buque = $_POST['item_buque'];
		$item_descripcion = $_POST['item_descripcion'];
		$item_origen = $_POST['item_origen'];
		$item_proveedor = $_POST['item_proveedor'];
		$item_cotizacion = $_POST['item_cotizacion'];
		$item_factura = $_POST['item_factura'];
		$item_fecha_rfq = $_POST['item_fecha_rfq'];
		$item_bl_awb = $_POST['item_bl_awb'];
		$item_container = $_POST['item_container'];
		$item_carrier = $_POST['item_carrier'];
		$item_status = $_POST['item_status'];
        if(empty($item_id))
        {
           $item_id=0;
        }
       
       
        try
        {
            $sql = "call proc_update_item('{$item_id}','{$item_buque}','{$item_descripcion}','{$item_origen}','{$item_proveedor}','{$item_cotizacion}','{$item_factura}','{$item_fecha_rfq}','{$item_bl_awb}','{$item_container}','{$item_carrier}','{$item_status}');";
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
        header("Location: ../display_items_historicos.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_items_historicos.php");
    }

?>

