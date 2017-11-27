<?php
	//$tr_cc_id = $_GET['tr_cc_id'];
	$current="500";
	require_once('../csb_common.php');
	
        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items_status(0);";
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


<h2>Status Items Entregados</h2>
<a href="export/export_items_pendientes_xls.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=<?=$fecha_orden?>">EXPORT XLS</a>
<div class="table">
	<div class="rowheader">
		<div class="cell">ID</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Buque<?php if($fecha_orden==1) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=2">Descripcion<?php if($fecha_orden==2) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell">Status</div>
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


<?php require_once($path_include."/cmifooter.php");

