<?php
	//$tr_cc_id = $_GET['tr_cc_id'];
	$current="509";
	$page_category = "abastecimiento CSB";
	$page_name = "items pendientes";

	$checkmark = "<img src=\"/audit/images/checkmark-14.png\">";
	$track = "<img src=\"/audit/images/track-icon.png\"  alt=\"Track & Trace\" title=\"Track & Trace\" height=\"15\" width=\"15\">";

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


<form name="formUpdate" method="post" action="update_items.php">
<div class="table">
	<div class="rowheader">
		<div class="centercell"></div>
		<div class="centercell"><a class="rowheader" href="display_items_pendientes.php?p_orden=8">Status Item</a></div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=1">Buque</a></div>
		<div class="descriptioncell">Descripcion</div>
		<div class="descriptioncell">Status Detalle</div>
		<div class="descriptioncell">Status Docs Detalle</div>
		<div class="cell"><a class="rowheader" href="display_items_pendientes.php?p_orden=2">Proveedor</a></div>
		<div class="centercell">Cotizacion</div>
		<div class="centercell">Factura</div>
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
                    } else if ($row['item_carrier']=="DHL") {
                        $url_carrier = "http://www.dhl.com/en/express/tracking.html?brand=DHL&AWB=";
                    } else if ($row['item_carrier']=="FEDEX") {
                        $url_carrier = "https://www.fedex.com/apps/fedextrack/?action=track&trackingnumber=";
                    } else if ($row['item_carrier']=="TNT") {
                        $url_carrier = "https://www.tnt.com/express/es_cl/site/herramientas-envio/seguimiento.html?respCountry=cl&respLang=es&cons=";
                    }


                    ?>
      <div class="centercell"><input type="checkbox" name="items[]" value="<?php echo $row['item_id'];?>"></div>
			<div class="centercell"><a class="button-report" href="edit_item.php?item_id=<?=$row['item_id']?>&item_buque=<?=$row['item_buque']?>&item_descripcion=
			<?=$row['item_descripcion']?>&item_origen=<?=$row['item_origen']?>&item_proveedor=<?=$row['item_proveedor']?>&item_cotizacion=<?=$row['item_cotizacion']?>&item_factura=
			<?=$row['item_factura']?>&item_fecha_rfq=<?=$row['item_fecha_rfq']?>&item_bl_awb=<?=$row['item_bl_awb']?>&item_container=
			<?=$row['item_container']?>&item_carrier=<?=$row['item_carrier']?>&item_status=<?=$row['item_status']?>&item_eta=<?=$row['item_eta']?>&item_thc=<?=$row['item_thc']?>"><?=$row['item_status']?></a></div>
			<div class="cell"><?=$row['item_buque']?></div>
			<div class="descriptioncell"><?=$row['item_descripcion']?></div>
			<div class="descriptioncell"><a class="button-report"  href="display_status_item.php?item_id=<?=$row['item_id']?>&item_descripcion=<?=$row['item_descripcion']?>">S</a><?=$row['si_status']?></div>
			<div class="descriptioncell"><a class="button-report"  href="display_status_docs.php?item_id=<?=$row['item_id']?>&item_descripcion=<?=$row['item_descripcion']?>">D</a><?=$row['sd_status']?></div>
			<div class="cell"><?=$row['item_proveedor']?></div>
			<div class="centercell"><?php echo ($row['item_cotizacion'] <> "") ? showCheckmark($row['item_cotizacion']) : '';?></div>
			<div class="centercell"><?php echo ($row['item_factura'] <> "") ? showCheckmark($row['item_factura']) : '';?></div>
			<div class="cell"><?=$row['item_fecha_rfq']?></div>
                        <div class="cell"><?=$row['item_thc']?></div>
			<div class="cell"><?=$row['item_container']?></div>
      <div class="cell"><a target="_blank" href="<?=$url_carrier?><?=$row['item_bl_awb']?>"><?php echo ($row['item_bl_awb'] <> "") ? $track : '';?></a><a href="display_items_bl_pendientes.php?p_bl=<?=$row['item_bl_awb']?>"><?=$row['item_bl_awb']?></a></div>
			<div class="cell"><?=$row['item_carrier']?></div>
			<div class="cell"><?=$row['item_origen']?></div>
		</div>
    <?php endforeach ?>
</div>
<button class="button-submit" type="submit" value="Update">Update</button>
</form>
<div>
	<h2>Download links:</h2>
<a href="export/export_items_pendientes_xls.php"><img src="/audit/images/xls-icon.png"></a>
</div>
<?php require_once($path_include."/cmifooter.php");
