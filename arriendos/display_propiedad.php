<?php

	$current=903;
	$page_category = "arriendos";
	$page_name = "detalle de propiedad" ;
	// This if statement checks to determine whether the transaction has been submitted
	// If it has, then the transaction insert code is run, otherwise the form is displayed
$pr_id = $_GET['pr_id'];

if(empty($_GET['pr_id']))
	{
		die("Ingresar propiedad.");
	}
$current="90"."$pr_id";

	require_once('../../includes/arriendos_common.php');







    /*
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
        */

        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql_co = "call proc_display_propiedad($pr_id);";
			//echo $sql;
            $stmt_co = $db->prepare($sql_co);
            $result_co = $stmt_co->execute();
            $data_co = $stmt_co->fetch();
			$stmt_co->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

	$page_name = 	$page_name ."x ".$data_co['pr_codigo'];

		try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql_pr = "call proc_display_contratos_propiedad($pr_id);";
			//echo $sql;
            $stmt_pr = $db->prepare($sql_pr);
            $result_pr = $stmt_pr->execute();
            $data_pr = $stmt_pr->fetchAll();
			$stmt_pr->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

		try
        {
            $sql_3 = "call proc_display_cuotas_vencidas_pr($pr_id);";
			//echo $sql;
            $stmt_3 = $db->prepare($sql_3);
            $result_3 = $stmt_3->execute();
            $data_3 = $stmt_3->fetch();
			$stmt_3->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

		try
        {
            $sql_4 = "call proc_display_proximo_vencimiento_pr($pr_id);";
			//echo $sql;
            $stmt_4 = $db->prepare($sql_4);
            $result_4 = $stmt_4->execute();
            $data_4 = $stmt_4->fetch();
			$stmt_4->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }



?>




<div class="table">
	        <div class="row">
			<div class="leftcell">Código</div>
			<div class="textcell" style="font-weight: bold;color:red;"><?=$data_co['pr_codigo'];?></div>
		</div>
        <div class="row">
			<div class="leftcell">Dirección</div>
			<div class="textcell"><?=$data_co['pr_direccion'];?></div>
		</div>
	        <div class="row">
			<div class="leftcell">Comuna</div>
			<div class="textcell"><?=$data_co['pr_comuna'];?></div>
		</div>
		        <div class="row">
			<div class="leftcell">Propietario</div>
			<div class="textcell"><?=$data_co['pr_propietario'];?></div>
		</div>
		<div class="row">
			<div class="leftcell">Notas</div>
			<div class="textcell"><?=$data_co['pr_notas'];?></div>
		</div>
</div>
<br>
<div class="table">
<div class="row">
			<div class="textcell">Cuotas vencidas:</div>
			<?php if ($data_3['cantidad']>0) echo '<div class="numbercell alert">';
				else echo '<div class="numbercell">';?><?=$data_3['cantidad'];?><br></div>
			<div class="cell"></div>
			<div class="textcell">Próximo Vencimiento:</div>
			<div class="cell"><?=$data_4['proximo_vencimiento'];?><br></div>
</div>
<div class="row">
			<div class="textcell">Monto vencido:</div>
			<?php if ($data_3['monto_uf']>0) echo '<div class="numbercell alert">';
				else echo '<div class="numbercell">';?>UF <?=number_format($data_3['monto_uf'],2);?><br></div>
			<div class="cell"></div>
			<div class="textcell">Monto Proximo Vencimiento:</div>
			<div class="cell">UF <?=number_format($data_4['monto_proximo_vencimiento'],2);?><br></div>
</div>
</div>




<h3>Contratos de Arriendo</h3>

<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha Inicio</div>
		<div class="textcell">Arrendatario</div>
		<div class="cell">Monto (UF/mes)</div>
		<div class="textcell">Notas</div>
		<div class="cell">Ingresar Pago</div>

	</div>
    <?php foreach ($data_pr as $row): ?>
        <div class="row">
			<div class="cell"><?=$row['co_fecha_inicio'];?></div>
			<div class="textcell"><?=$row['ar_nombre'];?></div>
			<div class="cell"><?=($row['tipo']==1?"Electricidad":number_format($row['co_monto_uf'],2))?></div>
			<div class="textcell"><?=$row['co_inventario'];?></div>
			<div class="textcell"><a href="insert_pago.php?co_id=<?=$row['co_id']?>" class="button-report">...</a></div>
		</div>
    <?php endforeach ?>

</div>


	    <?php require_once($path_include."/cmifooter.php");
