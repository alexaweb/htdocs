<?php

	$current=321;
	$page_category = "arriendos";
	$page_name = "detalle de contrato";
	//$dbfile = "indicadoresDB.php";
	//$menufile = "indicadoresmenu.php";
        //require_once ('/var/www/includes/common.php');
	//require_once('/var/www/includes/cmiheader.php');
        require_once('../../includes/arriendos_common.php');


    $fecha_hoy = date("Y-m-d");
    	try
        {
            $sql_uf = "call get_valor_uf('{$fecha_hoy}');";

            $stmt_uf = $db->prepare($sql_uf);
            $result_uf = $stmt_uf->execute();
            $data_uf = $stmt_uf->fetch();
			$stmt_uf->closeCursor();
			$valor_uf = $data_uf['valor'];

        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$co_id = $_GET['co_id'];

	if(empty($_GET['co_id']))
    {
			die("Ingresar contrato.");
    }



        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql_co = "call proc_display_arriendo_co($co_id);";
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

		        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql_pa = "call proc_display_cobro_pago($co_id);";
			//echo $sql;
            $stmt_pa = $db->prepare($sql_pa);
            $result_pa = $stmt_pa->execute();
            $data_pa = $stmt_pa->fetchAll();
			$stmt_pa->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


		try
        {
            $sql_3 = "call proc_display_cuotas_vencidas($co_id);";
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
            $sql_4 = "call proc_display_proximo_vencimiento($co_id);";
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

		try
        {
            $sql_5 = "call proc_display_pago_groupbyfecha($co_id);";
			//echo $sql;
            $stmt_5 = $db->prepare($sql_5);
            $result_5 = $stmt_5->execute();
            $data_5 = $stmt_5->fetchAll();
			$stmt_5->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }




?>



<h2>Contrato de <?=($data_co['co_tipo']==1?"Electricidad":"Arriendo");?></h2>
	<a href="display_contratos.php" class="button-back">volver</a>
<br><br>

<div class="table">
	<div class="rowheader">
		<div class="textcell">resumen</div>
		<div class="cell"></div>
		<div class="textcell"></div>
		<div class="cell"></div>
		<div class="cell"></div>

	</div>
<div class="row">
			<div class="textcell">Cuotas vencidas:</div>
			<?php if ($data_3['cantidad']>0) echo '<div class="numbercell alert">';
				else echo '<div class="numbercell">';?>
			<?=$data_3['cantidad'];?></div>
			<div class="cell"></div>
			<div class="textcell">Próximo Vencimiento:</div>
			<div class="numbercell"><?=$data_4['proximo_vencimiento'];?><br></div>
</div>
<div class="row">
			<div class="textcell">Monto vencido:</div>
			<?php if ($data_3['monto_uf']>0) echo '<div class="numbercell alert">';
				else echo '<div class="numbercell">';?>
			<?php
			 if($data_co['co_tipo']<>0)
				{
					echo "$ ".number_format($data_3['monto']);?></div>
				<?php } else {?>
				UF <?=number_format($data_3['monto_uf'],2);?><br>$ <?=number_format($data_3['monto_uf']*$valor_uf);?></div>
			<?php }?>

			<div class="cell"></div>
			<div class="textcell">Monto Proximo Vencimiento:</div>
			<div class="numbercell">
			<?php
			 if($data_co['co_tipo']==1)
				{?>
				 se cobra vencido</div>
				<?php } else {?>
				UF <?=number_format($data_4['monto_proximo_vencimiento'],2);?><br>$ <?=number_format($data_4['monto_proximo_vencimiento']*$valor_uf);?></div>
			<?php }?>
</div>
</div>


<h3>Contrato</h3>

<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha Inicio</div>
		<div class="cell">Propiedad</div>
		<div class="textcell">Arrendatario</div>
		<div class="cell">[ $ o UF /mes ]</div>
		<div class="cell">Dia de Pago</div>
		<div class="cell">Fecha Aviso</div>
		<div class="cell">Fecha Termino</div>
	</div>

        <div class="row">
			<div class="cell"><?=$data_co['co_fecha_inicio'];?></div>
			<div class="cell"><?=$data_co['pr_codigo'];?></div>
			<div class="textcell"><?=$data_co['ar_nombre'];?></div>
			<div class="numbercell"><?=($data_co['co_tipo']==1?"Electricidad":($data_co['co_tipo']==2?money_format('%.0n',$data_co['co_monto_clp']):number_format($data_co['co_monto_uf'],2)));?></div>
			<div class="cell"><?=$data_co['co_dia_de_pago'];?></div>
			<div class="cell"><?=$data_co['co_fecha_aviso'];?></div>
			<div class="cell"><?=$data_co['co_fecha_termino'];?></div>
		</div>

</div>



<h3>Status Pagos Contrato</h3>
<a href="insert_cobro.php?co_id=<?=$data_co['co_id']?>" class="button-submit">ingresar cobro</a>
<a href="insert_pago.php?co_id=<?=$data_co['co_id']?>" class="button-submit">ingresar pago</a>
<br><br>
<div class="table">
	<div class="rowheader">
		<div class="cell">Periodo</div>
		<div class="cell">Fecha Vencimiento</div>
		<div class="cell">Monto (UF/mes)</div>
		<div class="cell">Fecha de Pago</div>
		<div class="cell">Monto (UF)</div>
		<div class="cell">Atraso?</div>
	</div>
    <?php foreach ($data_pa as $row): ?>
        <?php  if ((strtotime($row['cb_fecha_vencimiento']) < strtotime($fecha_hoy)) or ($row['cb_pagado']==1))
		{
		?>
		 <?php if ($row['cb_pagado'] == 0) {echo '<div class="row alert">';} else echo '<div class="row">'; ?>
			<div class="cell"><?=$row['cb_periodo'];?></div>
			<div class="cell"><?=$row['cb_fecha_vencimiento'];?></div>
			<div class="numbercell"><?=($data_co['co_tipo']==1?number_format($row['cb_monto']):number_format($row['cb_monto_uf'],2));?></div>
			<div class="cell"><?=$row['pa_fecha'];?></div>
			<div class="numbercell"><?=($data_co['co_tipo']==1?number_format($row['pa_monto']):number_format($row['pa_monto_uf'],2));?></div>
			<div class="cell"><?php
			$atraso = (strtotime($row['pa_fecha']) - strtotime($row['cb_fecha_vencimiento']))/60/60/24;
			if ($atraso > 0) {
				echo number_format($atraso)." días";
			}
			?></div>
		</div>
		<?php } ?>
    <?php endforeach ?>
</div>

<h3>Pagos recibidos</h3>

<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha de Pago</div>
		<div class="cell">Monto Total (CLP)</div>
		<div class="cell">Monto Total (UF)</div>
		<div class="textcell">Notas</div>
	</div>
    <?php foreach ($data_5 as $row): ?>
		<div class="row">
			<div class="cell"><?=$row['pa_fecha'];?></div>
			<div class="numbercell"><?=number_format($row['pa_monto']);?></div>
			<div class="numbercell"><?=number_format($row['pa_monto_uf'],2);?></div>
			<div class="textcell"><?=$row['pa_notas'];?></div>
		</div>
    <?php endforeach ?>
</div>



<?php require_once($path_include."/cmifooter.php");
