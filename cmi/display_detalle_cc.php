<?php

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
		if(empty($_GET['fecha_orden']))
	{
					die("Ingresar 'fecha_orden':<br> 1: ordenar por fecha_abono <br>2: ordenar por fecha_valor.");
	}

		 // $tr_fecha = $_POST['tr_fecha'];
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
            $sql = "call proc_display_transacciones_cc($tr_cc_id,$fecha_orden);";
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
            $sql2 = "call proc_display_transacciones_cartola_cc($tr_cc_id,$fecha_orden);";
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

		try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql3 = "call proc_display_saldos_mes($tr_cc_id);";
			//echo $sql;
            $stmt3 = $db->prepare($sql3);
            $result3 = $stmt3->execute();
            $data3 = $stmt3->fetchAll();
			$stmt3->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

require_once('includes/display_cartola_navigation.inc');
?>


<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha</div>
		<div class="cell">Días</div>
		<div class="rightcell">Saldo Promedio (UF)</div>
		<div class="rightcell">Interés Devengado (UF)</div>
		<div class="rightcell">Interés Anual</div>
		<div class="cell">Reporte</div>
	</div>
    <?php foreach ($data3 as $row):?>
    <div class="row">
			<div class="cell"><?=$row['fecha']?></div>
			<div class="cell"><?=$row['dias']?></div>
			<div class="numbercell"><?=number_format($row['saldo_promedio_uf'],2,",",".")?></div>
			<div class="numbercell"><?=number_format($row['interes_devengado_uf'],2,",",".")?></div>
			<div class="numbercell"><?=number_format($row['tasa_anual_uf'],2)?> %</div>
			<div class="cell"><a href="display_interes_devengado_cc_detalle.php?cc_id=<?=$tr_cc_id?>&ano=<?=$row['ano']?>&mes=<?=$row['mes']?>" class="button-report">...</a></div>
		</div>
    <?php endforeach ?>
</div>


<?php require_once($path_include."/cmifooter.php");
