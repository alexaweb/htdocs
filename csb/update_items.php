<?php



	$current="511";
	$page_category = "abastecimiento CSB";
	$page_name = "modificar items";
	require_once('../../includes/csb_common.php');

	$fecha = date("Y-m-d", strtotime( '-0 days' ) );

	if(!empty($_POST))
    {
	$items = $_POST['items'];
} else {
	die("falta parámetro de items a modificar");
}

	//echo(count($items));
	echo "Items a modificar: ";
	print_r($items);


 $set_string = "";
 $where_string = "where item_id in (".implode(",",$items).")";
 echo $where_string;



?>

<br>
Agregar Información:
<form action="update_items_check.php" method="post">
  <!--Buque: <input type="text" name="item_buque" value="<?php echo $item_buque?>"><br>
	Descripcion: <input type="text" size="100" name="item_descripcion" value="<?php echo $item_descripcion?>"><br>
-->
	Origen: <input type="text" name="item_origen" value="<?php echo $item_origen?>"><br>
	Proveedor: <input type="text" name="item_proveedor" value="<?php echo $item_proveedor?>"><br>
	<!--Cotizacion: <input type="text" name="item_cotizacion" value="<?php echo $item_cotizacion?>"><br>
	Factura: <input type="text" name="item_factura" value="<?php echo $item_factura?>"><br>
	Fecha RFQ: <input type="text" name="item_fecha_rfq" value="<?php echo $item_fecha_rfq?>"><br>-->
        THC <input type="text" name="item_thc" value="<?php echo $item_thc?>"><br>
  Carrier: <input type="text" name="item_carrier" value="<?php echo $item_carrier?>"><br>
	#BL o AWB: <input type="text" name="item_bl_awb" value="<?php echo $item_bl_awb?>"><br>
	Container: <input type="text" name="item_container" value="<?php echo $item_container?>"><br>

	<!--Status: <input type="text" name="item_status" value="<?php echo $item_status?>"><br>-->
	<span style="display:inline">Status:
  <input type="radio" name="item_status" value="00" ><p class="row"> 0: terminado</p>  &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="1" ><p class="row"> 1: normal</p>  &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="2" ><p class="row warning">2: warning</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="3" ><p class="row alert" >3: alert</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="4" ><p class="row sea" >4: @ sea</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="5" ><p class="row cicarelli">5: @ cicarelli</p> &nbsp;&nbsp;&nbsp;
	<input type="radio" name="item_status" value="6" ><p class="row puq" >6: @ puq</p> &nbsp;&nbsp;&nbsp;
</span><br><br>
  <input type="hidden" name="w_string" value="<?=$where_string;?>">
  <button class="button-submit" type="submit" value="Submit">Submit</button>
</form>




<?php require_once($path_include."/cmifooter.php");
