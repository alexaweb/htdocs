<?php



	$current="511";
	$page_category = "abastecimiento CSB";
	$page_name = "nuevo item / modificar item";
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
	<!--Status: <input type="text" name="item_status" value="<?php echo $item_status?>"><br>-->
	<span style="display:inline">Status:
	<input type="radio" name="item_status" value="00" ><p class="row"> 0: terminado</p>  &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="1" <?php echo ($item_status==1) ?  "checked" : "" ;  ?>><p class="row"> 1: normal</p>  &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="2" <?php echo ($item_status==2) ?  "checked" : "" ;  ?>><p class="row warning">2: warning</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="3" <?php echo ($item_status==3) ?  "checked" : "" ;  ?>><p class="row alert" >3: alert</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="4" <?php echo ($item_status==4) ?  "checked" : "" ;  ?>><p class="row sea" >4: @ sea</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="5" <?php echo ($item_status==5) ?  "checked" : "" ;  ?>><p class="row cicarelli">5: @ cicarelli</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="6" <?php echo ($item_status==6) ?  "checked" : "" ;  ?>><p class="row puq" >6: @ puq</p> &nbsp;&nbsp;&nbsp;
</span><br><br>
  <input type="hidden" name="item_id" value="<?=$item_id?>">
  <button class="button-submit" type="submit" value="Submit">Submit</button>
</form>




<?php require_once($path_include."/cmifooter.php");
