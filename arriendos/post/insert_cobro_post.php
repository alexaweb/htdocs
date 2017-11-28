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
		$periodo = $_POST['periodo'];
		$fecha = $_POST['fecha'];
		$monto = $_POST['monto'];
		$monto_uf = $_POST['monto_uf'];
		$tipo = $_POST['tipo'];
		$descripcion = $_POST['descripcion'];
		
		
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
       
       
       
        try
        {
            $sql = "call proc_insert_cobro($co_id,'{$periodo}','{$fecha}',$tipo, $monto, $monto_uf,'{$descripcion}',0);";
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

