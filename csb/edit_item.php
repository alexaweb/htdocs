<?php



	$current="511";
	require_once('../../includes/csb_common.php');
	
	$fecha = date("Y-m-d", strtotime( '-0 days' ) );
	
	if(!empty($_GET))
    {
	$item_id = $_GET['item_id'];
	$item_buque = $_GET['item_buque'];
	$item_descripcion = $_GET['item_descripcion'];
	$item_origen = $_GET['item_origen'];
	$item_proveedor = $_GET['item_proveedor'];
	$item_cotizacion = $_GET['item_cotizacion'];
	$item_factura = $_GET['item_factura'];
	$item_fecha_rfq = $_GET['item_fecha_rfq'];
	$item_bl_awb = $_GET['item_bl_awb'];
	$item_container = $_GET['item_container'];
	$item_carrier = $_GET['item_carrier'];
	$item_status = $_GET['item_status'];
        $item_eta = $_GET['item_eta'];
        $item_thc = $_GET['item_thc'];
	}
	
	if(empty($item_fecha_rfq))
		{
			$item_fecha_rfq=$fecha;
		}
	if(empty($item_status))
		{
			$item_status=1;
		}
    	
		
?>


<h2>Item <?php echo $item_descripcion;?></h2>
<br>
Agregar Información:
<form action="edit_item_check.php" method="post">
  Buque: <input type="text" name="item_buque" value="<?php echo $item_buque?>"><br>
	Descripcion: <input type="text" size="100" name="item_descripcion" value="<?php echo $item_descripcion?>"><br>
	Origen: <input type="text" name="item_origen" value="<?php echo $item_origen?>"><br>
	Proveedor: <input type="text" name="item_proveedor" value="<?php echo $item_proveedor?>"><br>
	Cotizacion: <input type="text" name="item_cotizacion" value="<?php echo $item_cotizacion?>"><br>
	Factura: <input type="text" name="item_factura" value="<?php echo $item_factura?>"><br>
	Fecha RFQ: <input type="text" name="item_fecha_rfq" value="<?php echo $item_fecha_rfq?>"><br>
        THC <input type="text" name="item_thc" value="<?php echo $item_thc?>"><br>
	#BL o AWB: <input type="text" name="item_bl_awb" value="<?php echo $item_bl_awb?>"><br>
	Container: <input type="text" name="item_container" value="<?php echo $item_container?>"><br>
	Carrier: <input type="text" name="item_carrier" value="<?php echo $item_carrier?>"><br>
        ETA: <input type="text" name="item_eta" value="<?php echo $item_eta?>"><br>
	Status: <input type="text" name="item_status" value="<?php echo $item_status?>"><br>
  <input type="hidden" name="item_id" value="<?=$item_id?>">
  1: normal <br>
<div class="row warning">2: warning</div>
<div class="row alert">3: alert </div>
<div class="row sea">4: @ sea</div>
<div class="row cicarelli">5: @ cicarelli</div>
<div class="row puq">6: @ puq</style></div>
<br>
  <button class="button submit" type="submit" value="Submit">Submit</button>
</form>




<?php require_once($path_include."/cmifooter.php");

