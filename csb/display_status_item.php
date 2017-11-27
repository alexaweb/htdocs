<?php
	$item_id = $_GET['item_id'];
	$item_descripcion = $_GET['item_descripcion'];
	$current="511";
	require_once('../csb_common.php');
	
	$fecha = date("Y-m-d", strtotime( '-0 days' ) );
	
        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_status_item($item_id);";
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


<h2>Status Item <?php echo $item_descripcion;?></h2>
<a href="export/export_status_item_xls.php?item_id=<?=$item_id?>">EXPORT XLS</a>
<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha</div>
		<div class="textcell">Detalle</div>
	</div>
    <?php foreach ($data_tr as $row):
		?>
        <div class="row">
			<div class="cell"><?=$row['si_fecha']?></div>
			<div class="textcell"><?=$row['si_status']?></div>
		</div>
    <?php endforeach ?>
</div>

<br>
<button class="button" onclick="history.go(-1);">Volver </button>
<br><br><br>
Agregar Información:
<form action="post/insert_status_post.php" method="post">
  Fecha: <input type="text" name="fecha" value="<?php echo $fecha?>"><br>
  Detalle: <input type="text" size="100" name="detalle" value="">
  <input type="hidden" name="item_id" value="<?=$item_id?>">
  <input type="hidden" name="item_descripcion" value="<?=$item_descripcion?>"><br><br>
  <button class="button submit" type="submit" value="Submit">Submit</button>
</form>





<?php require_once($path_include."/cmifooter.php");

