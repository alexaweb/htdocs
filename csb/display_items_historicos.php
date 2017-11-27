<?php
	//$tr_cc_id = $_GET['tr_cc_id'];
	$current="510";
	require_once('../csb_common.php');
	
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


<h2>Status Items Pendientes</h2>
<a href="export/export_items_pendientes_xls.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=<?=$fecha_orden?>">EXPORT XLS</a>
<div class="table">
	<div class="rowheader">
		<div class="cell">ID</div>
		<div class="textcell"><a class="rowheader" href="display_items_pendientes.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Buque<?php if($fecha_orden==1) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="textcell"><a class="rowheader" href="display_items_pendientes.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=2">Descripcion<?php if($fecha_orden==2) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell">Status</div>
		<div class="cell">Status Fecha</div>
		<div class="cell">Status Detalle</div>
		<div class="cell">Proveedor</div>
		<div class="textcell">Cotizacion</div>
		<div class="textcell">Factura</div>
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
			<div class="cell"><?=$row['item_descripcion']?></div>
			<div class="cell"><?=$row['item_status']?></div>
			<div class="cell"><?=$row['si_fecha']?></div>
			<div class="textcell"><?=$row['si_status']?></div>
			<div class="textcell"><?=$row['item_proveedor']?></div>
			<div class="textcell"><?=$row['item_cotizacion']?></div>
			<div class="textcell"><?=$row['item_factura']?></div>
			<div class="cell"><?=$row['item_fecha_rfq']?></div>
			<div class="textcell"><?=$row['item_container']?></div>
			<div class="textcell"><?=$row['item_bl_awb']?></div>
			<div class="textcell"><?=$row['item_carrier']?></div>
			<div class="textcell"><?=$row['item_origen']?></div>
		</div>
    <?php endforeach ?>
</div>


<?php require_once($path_include."/cmifooter.php");

