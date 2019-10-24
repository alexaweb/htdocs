<?php
	$tr_cc_id = $_GET['tr_cc_id'];
	$current="10".$tr_cc_id;
	$page_category = "cuentas corrientes";
	$page_name = "cartolas";
	require_once('../../includes/cmi_common.php');






    // DEFINE LA FECHA PARA BUSQUEDA DE INDICADORES

    //$fecha = "2016-07-17";
    $fecha = date("Y-m-d");

    //Obtengo el valor de la UF para desplegar antes de hacer cualquier cosa
    $sql = "select valor from indicadores where codigo='uf' and fecha = '{$fecha}'";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $row = $stmt->fetch();
    $valor_uf = $row['valor'];
    //echo "El valor de la UF el día de hoy $fecha es: $valor_uf<br><br>";


    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_GET))
    {
        echo "Favor indicar cuenta corriente y fecha en GET";
        die;
    }

    if(empty($_GET['tr_cc_id']))
    {
            die("Ingresar CC_ID.");
    }
	  //if(empty($_GET['fecha_orden']))
    //{
    //        die("Ingresar 'fecha_orden':<br> 1: ordenar por fecha_abono <br>2: ordenar por fecha_valor.");
    //}

       // $tr_fecha = $_POST['tr_fecha'];
        $tr_cc_id = $_GET['tr_cc_id'];

          //Obtengo el valor de la UF para desplegar antes de hacer cualquier cosa
        $sql = "select entidad_codigo_b, cc_spread,cc_fecha_inicio from cc where cc_id=$tr_cc_id";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetch();
        $cc_entidad_codigo_b = $row['entidad_codigo_b'];
        $cc_spread = $row['cc_spread'];
        $cc_fecha_inicio = $row['cc_fecha_inicio'];

		$fecha_orden = $_GET['fecha_orden'];


        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_transacciones_cc($tr_cc_id,2);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
			$stmt->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


				try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql2 = "call proc_display_cartola($tr_cc_id);";
			//echo $sql;
            $stmt2 = $db->prepare($sql2);
            $result2 = $stmt2->execute();
            $data2 = $stmt2->fetchAll();
						$stmt2->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


require_once('includes/display_cartola_navigation.inc');
?>

<div class="table">
	<div class="rowheader">
		<div class="textcell">Fecha Abono</div>
		<div class="cell">Fecha Valor</div>
		<div class="rightcell">Monto (CLP)</div>
		<div class="rightcell">Monto (UF)</div>
		<div class="cell">Tipo</div>
		<div class="textcell">Descripción</div>
	</div>
    <?php foreach ($data_tr as $row):
		if ($row['tr_tipo_transaccion']==0)
		{ ?>
        <div class="row">
			<div class="textcell"><?=$row['tr_fecha_abono']?></div>
			<div class="cell"><?=$row['tr_fecha_valor']?></div>
			<div class="numbercell"><?=number_format($row['tr_monto'],0,",",".")?></div>
			<div class="numbercell"><?=number_format($row['tr_monto_uf'],2,",",".")?></div>
			<div class="cell"><?=$row['tr_tipo_transaccion']?></div>
			<div class="textcell"><?=$row['tr_descripcion']?></div>
		</div>
		<?php }?>
    <?php endforeach ?>
</div>
<div>
	<h2>Download links:</h2>
<a href="export/export_transacciones_csv.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=<?=$fecha_orden?>"><img src="/audit/images/csv-icon.png"></a>
<a href="export/export_transacciones_xls.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=<?=$fecha_orden?>"><img src="/audit/images/xls-icon.png"></a>
</div>



<?php require_once($path_include."/cmifooter.php");
