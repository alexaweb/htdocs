<?php
	//$tr_cc_id = $_GET['tr_cc_id'];
	$current="510";
	$page_category = "abastecimiento CSB";
	$page_name = "items históricos";
	require_once('../../includes/csb_common.php');

        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items;";
		//	echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
			$stmt->closeCursor();
        }
        catch(PDOException $ex)
        {
           die("Failed to run query: " . $ex->getMessage());
        }


?>

<div class="table">
	<div class="rowheader">
		<div class="cell">ID</div>
		<div class="cell">Buque</div>
		<div class="cell">Descripcion</div>
		<div class="cell">Status Item</div>
		<div class="cell">Status Fecha</div>
		<div class="textcell">Status Detalle</div>
		<div class="cell">Status Docs Fecha</div>
		<div class="textcell">Status Docs Detalle</div>
		<div class="cell">Proveedor</div>
		<div class="cell">Cotizacion</div>
		<div class="cell">Factura</div>
		<div class="cell">Fecha RFQ</div>
		<div class="cell">Container</div>
		<div class="cell">BL o AWB</div>
		<div class="cell">Carrier</div>
		<div class="cell">Origen</div>
	</div>
    <?php foreach ($data_tr as $row):
		?>
        <div class="row">
			<div class="cell"><?=$row['item_id']?></div>
			<div class="cell"><?=$row['item_buque']?></div>
			<div class="textcell"><?=$row['item_descripcion']?></div>
			<div class="cell"><a class="row" href="display_status_item.php?item_id=<?=$row['item_id']?>&item_descripcion=<?=$row['item_descripcion']?>"><?=$row['item_status']?></a></div>
			<div class="cell"><?=$row['si_fecha']?></div>
			<div class="textcell"><?=$row['si_status']?></div>
			<div class="cell"><?=$row['sd_fecha']?></div>
			<div class="textcell"><?=$row['sd_status']?></div>
			<div class="cell"><?=$row['item_proveedor']?></div>
			<div class="cell"><?=$row['item_cotizacion']?></div>
			<div class="cell"><?=$row['item_factura']?></div>
			<div class="cell"><?=$row['item_fecha_rfq']?></div>
			<div class="cell"><?=$row['item_container']?></div>
			<div class="cell"><?=$row['item_bl_awb']?></div>
			<div class="cell"><?=$row['item_carrier']?></div>
			<div class="cell"><?=$row['item_origen']?></div>
		</div>
    <?php endforeach ?>
</div>
<div>
	<h2>Download links:</h2>
<a href="export/export_items_historicos_xls.php"><img src="/audit/images/xls-icon.png"></a>
</div>


<?php require_once($path_include."/cmifooter.php");
