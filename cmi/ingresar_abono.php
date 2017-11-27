<?php
	$current=22;
	require_once('../../includes/cmi_common.php'); 
    
   
    
	if(empty($_GET))
    {
		// This redirects the user back to the login page after they register
        header("Location: display_interes_devengado.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_transacciones.php");
    }
	
	
	$fecha_valor = date("Y-m-d");
	$fecha_abono = $fecha_valor;


    $cc_id = $_GET['cc_id'];
	$sql = "select cc_id, entidad_codigo_b from cc where cc_id = $cc_id";
	$stmt = $db->prepare($sql);
	$result = $stmt->execute();
	$row = $stmt->fetch();
    $entidad_codigo = $row['entidad_codigo_b'];
    
    $fecha_valor = date("Y-m-t", strtotime( '-1 month' ) );
    $fecha_abono = date("Y-m-d");	
    $monto = -$_GET['monto'];
    

?>
		
				
<h2>Ingresar abono de interés</h2>

<form action="ingresar_abono_check.php" method="post">
	<div class="wrapper">
	<div class="table">
	<div class="row"><div class="cell formtext">
	Fecha Abono:</div><div class="cell">
    <input type="text" name="tr_fecha_abono" value="<?php echo $fecha_abono?>" /> 
    </div></div><div class="row"><div class="cell formtext">
	Fecha Valor:</div><div class="cell">
    <input type="text" name="tr_fecha_valor" value="<?php echo $fecha_valor?>" /> 
    </div></div><div class="row"><div class="cell formtext">
    Cuenta Corriente:</div><div class="cell"><?php echo $entidad_codigo;?>
	<input type="text" name="tr_cc_id" value="<?php echo $cc_id;?>" /> 
	    </div></div><div class="row"><div class="cell formtext">
    Moneda:</div><div class="cell">
    <select name="tr_moneda" id="">
            <option value="clp">CLP</option>
            <option value="uf">UF</option>            
    </select>
	    </div></div><div class="row"><div class="cell formtext">
    Monto: </div><div class="cell">
		<input type="text" name="tr_monto" value="<?php echo $monto;?>" />
	    </div></div><div class="row"><div class="cell formtext">
    Descripción: </div><div class="cell"><input type="text" name="tr_descripcion" value="" />
	    </div></div><div class="row"><div class="cell formtext">
    </div><div><button class="button submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
</form>

<?php require_once($path_include."/cmifooter.php");

