<?php

	$current=322;
	$page_category = "arriendos";
	$page_name = "resumen flujo";
	require_once('../../includes/arriendos_common.php');





    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$periodo = date("Ym");



        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_montos_pagados();";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
			$stmt->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

		try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "select count(*) as vigentes from (select count(*) from propiedad p join contrato c on p.pr_id = c.pr_id where c.co_vigente = 1 group by p.pr_id) as temp;";
			//echo $sql;
            $stmt2 = $db->prepare($sql);
            $result = $stmt2->execute();
            $data2 = $stmt2->fetch();
			$vigentes = $data2['vigentes'];
			$stmt2->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

		try
		{
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "select count(*) as totales from propiedad;";
			//echo $sql;
            $stmt3 = $db->prepare($sql);
            $result = $stmt3->execute();
            $data3 = $stmt3->fetch();
			$totales = $data3['totales'];
			$stmt3->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

	$sinarrendar = $totales - $vigentes;
	$porcentaje_sinarrendar = $sinarrendar / $totales * 100;
?>





<div class="table">
	<div class="row">
		<div class="leftcell">Propiedades para Arriendo:</div>
		<div class="numbercell"><?= $totales;?></div>
	</div>
	<div class="row">
		<div class="leftcell">Propiedades sin arrendar:</div>
		<div class="numbercell"><?= $sinarrendar;?></div>
	</div>
	<div class="row">
		<div class="leftcell alert">% Propiedades sin arrendar:</div>
		<div class="numbercell alert"><?= number_format($porcentaje_sinarrendar);?> %</div>
	</div>
</div>
<br><br>

<div class="table">
	<div class="rowheader">
		<div class="cell">Periodo</div>
		<div class="numbercell">Cobrado (UF)</div>
		<div class="numbercell">Pagado (UF)</div>
		<div class="numbercell">% Efectividad</div>
		<div class="cell">...</div>
	</div>
    <?php foreach ($data as $row): ?>
	<?php $cobro = $row['suma_cobro'];
			$pago = $row['suma_pago'];
			$porcentaje = $pago / $cobro;
			if ($porcentaje < 0.75) {
				echo '<div class="row alert">';
			} else if ($porcentaje < 0.9) {
				echo '<div class="row warning">';
			} else{
				echo '<div class="row">';
			}
			?>
			<div class="cell"><?=$row['periodo'];?></div>
			<div class="numbercell"><?=number_format($cobro,2);?></div>
			<div class="numbercell"><?=number_format($pago,2);?></div>
			<div class="numbercell"><?=number_format($porcentaje*100)." %";?></div>
			<div class="cell"><a href="resumen_periodo.php?periodo=<?=$row['periodo']?>" class="button-report">...</a></div>
		</div>
    <?php endforeach ?>
</div>

<?php require_once($path_include."/cmifooter.php");
