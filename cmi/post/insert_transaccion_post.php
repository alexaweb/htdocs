<?php
	$current=32;
	require_once('../../../includes/cmi_common.php');
	        
    
    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
        
        // Ensure that the user has entered a non-empty password
        if(empty($_POST['tr_fecha_abono']))
        {
            die("Ingresar fecha abono.");
        }
        if(empty($_POST['tr_fecha_valor']))
        {
            die("Ingresar fecha valor.");
        }
        if(empty($_POST['tr_cc_id']))
        {
            die("Ingresar Cuenta Corriente.");
        }
        if(empty($_POST['tr_monto']))
        {
            die("Ingresar Monto.");
        }
        
        $tr_fecha_valor = $_POST['tr_fecha_valor'];
	$tr_fecha_abono = $_POST['tr_fecha_abono'];
        $tr_monto = $_POST['tr_monto'];
	$tr_cc_id = $_POST['tr_cc_id'];
        $tr_moneda = $_POST['tr_moneda'];
        $tr_descripcion = $_POST['tr_descripcion'];
	$tr_tipo_transaccion = 0;
       
        try
        {
            $sql = "call proc_insert_transaccion('{$tr_fecha_abono}','{$tr_fecha_valor}',$tr_cc_id,$tr_tipo_transaccion,'{$tr_moneda}', $tr_monto,'{$tr_descripcion}',0);";
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
        header("Location: ../display_cartola_transacciones.php?tr_cc_id=$tr_cc_id&fecha_orden=2");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_cartola_transacciones.php");
    }

?>

