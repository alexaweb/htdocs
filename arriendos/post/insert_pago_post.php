<?php

    	
	$dbfile = "arriendosDB.php";
    // First we execute our common code to connection to the database and start the session
    //$path = $_SERVER['DOCUMENT_ROOT'];
    require '../../../includes/common.php';
 
    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        $co_id = $_POST['co_id'];
		$fecha = $_POST['fecha'];
		$monto = $_POST['monto'];
		$monto_uf = $_POST['monto_uf'];
		$tipo = $_POST['tipo'];
		$complete_period = $_POST['completa_periodo'];
		$skip_period = $_POST['skip_period'];
		$periodo = $_POST['periodo'];
		$notas = $_POST['notas'];
		
        // Ensure that the user has entered a non-empty password
        if(empty($fecha))
        {
            die("Ingresar fecha abono.");
        }
        if(empty($monto_uf))
        {
            die("Ingresar monto.");
        }
        if(empty($co_id))
        {
            die("Ingresar Contrato.");
        }
        if(empty($periodo))
        {
            die("Ingresar periodo.");
        }
        if(isset($complete_period))
        {
           $complete_period=1;
        } else {
			$complete_period=0;
		}
        if(empty($skip_period))
        {
           $skip_period=0;
        }
       
       
        try
        {
            $sql = "call proc_insert_pago($co_id,'{$fecha}',$monto, $monto_uf,$tipo,$complete_period,$skip_period,'{$periodo}','{$notas}');";
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
        header("Location: ../display_contrato.php?co_id=$co_id");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_contrato.php");
    }

?>

