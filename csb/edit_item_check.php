<?php
	$current=511;
	require_once('../csb_common.php');
    
   
    
    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_POST)){
	// This redirects the user back to the login page after they register
        header("Location: edit_item.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to edit_item.php");
	} else
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
	
       
    }
	
	

?>

		
				
<h2>Confirmar</h2>
<form action="post/edit_item_post.php" method="post">

	<div class="wrapper">
	<div class="table">
			<div class="rowheader">
		<div class="cell formtext"></div><div size="100" class="textcell"></div>
	</div>
	<div class="row">
		<div class="cell formtext">ID:</div><div><input type="hidden" name="item_id" value="<?=$item_id?>" /><?=$item_id?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Buque:</div><div><input type="hidden" name="item_buque" value="<?=$item_buque?>" /><?=$item_buque?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Descripción:</div><div><input type="hidden" name="item_descripcion" value="<?=$item_descripcion?>" /><?=$item_descripcion?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Origen:</div><div><input type="hidden" name="item_origen" value="<?=$item_origen?>" /><?=$item_origen?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Proveedor:</div><div><input type="hidden" name="item_proveedor" value="<?=$item_proveedor?>" /><?=$item_proveedor?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Cotización:</div><div><input type="hidden" name="item_cotizacion" value="<?=$item_cotizacion?>" /><?=$item_cotizacion?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Factura:</div><div><input type="hidden" name="item_factura" value="<?=$item_factura?>" /><?=$item_factura?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Fecha RFQ:</div><div><input type="hidden" name="item_fecha_rfq" value="<?=$item_fecha_rfq?>" /><?=$item_fecha_rfq?></div>
	</div>
	<div class="row">
		<div class="cell formtext"># BL o AWB:</div><div><input type="hidden" name="item_bl_awb" value="<?=$item_bl_awb?>" /><?=$item_bl_awb?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Container:</div><div><input type="hidden" name="item_container" value="<?=$item_container?>" /><?=$item_container?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Carrier:</div><div><input type="hidden" name="item_carrier" value="<?=$item_carrier?>" /><?=$item_carrier?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Status:</div><div><input type="hidden" name="item_status" value="<?=$item_status?>" /><?=$item_status?> <br>0: entregado; <br>1: normal; <br>2: con atraso; <br>3: URGENTE</div>
	</div>


	<div class="row"><div class="cell">
     <button class="button back" type="button" onclick="history.back();">BACK</button></div>
    <div><button class="button submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>

	</form>
<?php require_once($path_include."/cmifooter.php");