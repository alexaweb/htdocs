<?php

	$current=322;
	$page_category = "arriendos";
	$page_name = "resumen período";
	require_once('../../includes/arriendos_common.php');





    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$periodo = $_GET['periodo'];



        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_montos_pagados_periodo($periodo);";
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

?>



<h2>Resumen Arriendos Período: <?=$periodo;?></h2>


<div class="table">
	<div class="rowheader">
		<div class="textcell">Arrendatario</div>
		<div class="numbercell">Cobrado (UF)</div>
		<div class="numbercell">Pagado (UF)</div>
		<div class="numbercell">% Efectividad</div>
	</div>
    <?php foreach ($data as $row): ?>
		<?php $cobro = $row['suma_cobro'];
			$pago = $row['suma_pago'];
			$porcentaje = $pago / $cobro;
			if ($porcentaje < 0.5) {
				echo '<div class="row alert">';
			} else if ($porcentaje < 0.8) {
				echo '<div class="row warning">';
			} else{
				echo '<div class="row">';
			}
			?>
			<div class="textcell"><?=$row['nombre'];?></div>
			<div class="numbercell"><?=number_format($cobro,2);?></div>
			<div class="numbercell"><?=number_format($pago,2);?></div>
			<div class="numbercell"><?=number_format($porcentaje*100)." %";?></div>
		</div>
<?php
		$total_cobrado = $total_cobrado + $cobro;
		$total_pagado = $total_pagado + $pago;

?>
    <?php endforeach ?>
	    <div class="rowfooter">
			<div class="textcell">TOTAL</div>
			<div class="numbercell"><?=number_format($total_cobrado,2);?></div>
			<div class="numbercell"><?=number_format($total_pagado,2);?></div>
			<div class="numbercell"><?=number_format($total_pagado/$total_cobrado*100,1)." %";?></div>
		</div>
</div>

<br><br>
<button class="button-back" type="button" onclick="history.back();">volver</button>

<?php require_once($path_include."/cmifooter.php");
