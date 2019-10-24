<?php

	$current=321;
	$page_category = "arriendos";
	$page_name = "contratos históricos";
	require_once('../../includes/arriendos_common.php');





    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$p_orden = $_GET['p_orden'];

    if(empty($p_orden))
    {
            $p_orden = 0;
			//die("Ingresar ano.");
    }

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
            $sql = "call proc_display_arriendo($p_orden);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }



?>



<div class="table">
	<div class="rowheader">
		<div class="cell"><a href="/arriendos/display_contratos?p_orden=1">f. Inicio<?php if($p_orden==1) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell"><a href="display_contratos?p_orden=0">Propiedad<?php if($p_orden==0) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="textcell">Arrendatario</div>
		<div class="cell">Tipo</div>
		<div class="cell">[CLP/mes]</div>
		<div class="cell">[UF/mes]</div>
		<div class="cell">Dia de Pago</div>
		<div class="cell"><a href="display_contratos?p_orden=2">f. Aviso<?php if($p_orden==2) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell"><a href="display_contratos?p_orden=3">f. Termino<?php if($p_orden==3) {echo "<img src='images/arrow_down.png' style='height:12px'>";}?></a></div>
		<div class="cell">...</div>
	</div>
    <?php foreach ($data as $row): ?>
        <div class="row">
			<div class="cell"><?=$row['co_fecha_inicio'];?></div>
			<div class="cell"><?=$row['pr_codigo'];?></div>
			<div class="cell"><?=$row['ar_nombre'];?></div>
			<div class="cell"><?=($row['co_tipo']==1?"EE":($row['co_tipo']==2?"arriendo CLP":"arriendo"));?></div>
			<div class="numbercell">$ <?=number_format($row['co_monto_clp'],0);?></div>
			<div class="numbercell"><?=number_format($row['co_monto_uf'],2);?></div>
			<div class="cell"><?=$row['co_dia_de_pago'];?></div>
			<div class="cell"><?=$row['co_fecha_aviso'];?></div>
			<div class="cell"><?=$row['co_fecha_termino'];?></div>
			<div class="cell"><a href="display_contrato.php?co_id=<?=$row['co_id']?>" class="button-report">...</a></div>
		</div>
    <?php endforeach ?>
</div>

<?php require_once($path_include."/cmifooter.php");
