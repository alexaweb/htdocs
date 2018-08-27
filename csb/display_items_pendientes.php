<?php
	//$tr_cc_id = $_GET['tr_cc_id'];
	$current="509";
	require_once('../../includes/csb_common.php');
	
        $p_orden = $_GET['p_orden'];
        if(empty($p_orden))
        {
            $p_orden = 8;
        }
        if(empty($p_orden))
        {
            
            try
            {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items_pendientes();";
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
        } else
        {
          try
            {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items_pendientes_order($p_orden);";
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
        }   
        
		
?>


<h2>Status Items Pendientes</h2>
<a href="export/export_items_pendientes_xls.php">EXPORT XLS</a>
<div class="table">
	<div class="rowheader">
		<div class="cell">ID</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=1">Buque</a></div>
		<div class="cell">Descripcion</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=8">Status Item</a></div>
                <div class="cell">Status Fecha</div>
		<div class="textcell">Status Detalle</div>
		<div class="cell">Status Docs Fecha</div>
		<div class="textcell">Status Docs Detalle</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=2">Proveedor</a></div>
		<div class="cell">Cotizacion</div>
		<div class="cell">Factura</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=7">Fecha RFQ</a></div>
                <div class="cell">THC</div>
                <div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=3">Container</a></div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=4">BL o AWB</a></div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=5">Carrier</a></div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=6">Origen</a></div>
	</div>
    <?php foreach ($data_tr as $row):
		?>
            <?php if ($row['item_status']==3) {
				echo '<div class="row alert">';
			} else if ($row['item_status']==2) {
				echo '<div class="row warning">';
			} else if ($row['item_status']==4) {
				echo '<div class="row sea">';
			} else if ($row['item_status']==5) {
				echo '<div class="row cicarelli">';
			} else if ($row['item_status']==6 ) {
				echo '<div class="row puq">';
			} else{
				echo '<div class="row">';
			}
		
    
                    if ($row['item_carrier']=="Hapag Lloyd") {
                        $url_carrier = "https://www.hapag-lloyd.com/en/online-business/tracing/tracing-by-booking.html?blno=";
                        } else if ($row['item_carrier']=="Hamburg Sud") {
                        $url_carrier = "https://www.hamburgsud-line.com/liner/en/liner_services/ecommerce/track_trace/index.html?query=";
                    }
                    ?>
    
			<div class="cell"><a class="row" href="edit_item.php?item_id=<?=$row['item_id']?>&item_buque=<?=$row['item_buque']?>&item_descripcion=
			<?=$row['item_descripcion']?>&item_origen=<?=$row['item_origen']?>&item_proveedor=<?=$row['item_proveedor']?>&item_cotizacion=<?=$row['item_cotizacion']?>&item_factura=
			<?=$row['item_factura']?>&item_fecha_rfq=<?=$row['item_fecha_rfq']?>&item_bl_awb=<?=$row['item_bl_awb']?>&item_container=
			<?=$row['item_container']?>&item_carrier=<?=$row['item_carrier']?>&item_status=<?=$row['item_status']?>&item_eta=<?=$row['item_eta']?>&item_thc=<?=$row['item_thc']?>"><?=$row['item_id']?></a></div>
			<div class="cell"><?=$row['item_buque']?></div>
			<div class="textcell"><?=$row['item_descripcion']?></div>
                        <div class="cell"><?=$row['item_status']?></div>
			<div class="cell"><a href="display_status_item.php?item_id=<?=$row['item_id']?>&item_descripcion=<?=$row['item_descripcion']?>">S</a>&nbsp;<?=$row['si_fecha']?></div>
			<div class="textcell"><?=$row['si_status']?></div>
			<div class="cell"><a href="display_status_docs.php?item_id=<?=$row['item_id']?>&item_descripcion=<?=$row['item_descripcion']?>">D</a>&nbsp;<?=$row['sd_fecha']?></div>
			<div class="textcell"><?=$row['sd_status']?></div>
			<div class="cell"><?=$row['item_proveedor']?></div>
			<div class="cell"><?=$row['item_cotizacion']?></div>
			<div class="cell"><?=$row['item_factura']?></div>
			<div class="cell"><?=$row['item_fecha_rfq']?></div>
                        <div class="cell"><?=$row['item_thc']?></div>
			<div class="cell"><?=$row['item_container']?></div>
                        <div class="cell"><a href="<?=$url_carrier?><?=$row['item_bl_awb']?>"><?=$row['item_bl_awb']?></a></div>
			<div class="cell"><?=$row['item_carrier']?></div>
			<div class="cell"><?=$row['item_origen']?></div>
		</div>
    <?php endforeach ?>
</div>
1: normal <br>
<div class="row warning">2: warning</div>
<div class="row alert">3: alert </div>
<div class="row sea">4: @ sea</div>
<div class="row cicarelli">5: @ cicarelli</div>
<div class="row puq">6: @ puq</style></div>

<?php require_once($path_include."/cmifooter.php");

