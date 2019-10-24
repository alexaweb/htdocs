<?php
	$current=332;
	$page_category = "arriendos";
	$page_name = "ingresar pago";
	require_once('../../includes/arriendos_common.php');



    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_POST)){
	// This redirects the user back to the login page after they register
        header("Location: insert_pago.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to insert_pago.php");
	} else
    {

        // Ensure that the user has entered a non-empty password
        if(empty($_POST['fecha']))
        {
            die("Ingresar fecha.");
        }

        if(empty($_POST['co_id']))
        {
            die("Ingresar contrato .");
        }
        if(empty($_POST['monto']))
        {
            die("Ingresar Monto.");
        }

		//$ano = (int) date("Y", strtotime( '-1 month' ) );
		$fecha = $_POST['fecha'];
        $monto = str_replace('$','',str_replace('.','',$_POST['monto']));
		$periodo = $_POST['periodo'];
		$tipo = $_POST['tipo'];
        $notas = $_POST['notas'];
		$co_id = $_POST['co_id'];

		//$sql = "select valor from indicadores.indicadores where codigo='uf' and fecha = '{$fecha}';";
                $sql = "call arriendos.get_valor_uf('{$fecha}');";

        $stmt = $db->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetch();
        $valor_uf = $row['valor'];
		$monto_uf = $monto / $valor_uf;


		$sql = "select cb_monto_uf, cb_fecha_vencimiento from cobro where co_id = $co_id and cb_periodo = '{$periodo}';";
		//echo $sql;
	    $stmt = $db->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetch();
        $monto_uf_xpagar = $row['cb_monto_uf'];
		$fecha_vencimiento = $row['cb_fecha_vencimiento'];

		//echo $fecha."-".$monto."-".$monto_uf."-".$notas."-".$tipo."-".$periodo."-".$monto_uf_xpagar;

    }



?>



<h2>Confirmar pago</h2>
<form action="post/insert_pago_post.php" method="post">

	<div class="wrapper">
	<div class="table">
			<div class="row">
		<div class="cell formtext"></div><div class="cell">COBRO</div><div>PAGO</div>
	</div>
	<div class="row">
		<div class="cell formtext">Fecha:</div><div class="cell"><?=$fecha_vencimiento;?></div><div><input type="hidden" name="fecha" value="<?=$fecha?>" /><?=$fecha?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (CLP): </div><div></div><div class="numbercell"><input type="hidden" name="monto" value="<?= $monto?>" />$ <?=number_format($monto);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (UF): </div><div class="numbercell"><?=number_format($monto_uf_xpagar,2);?></div><div class="numbercell"><input type="hidden" name="monto_uf" value="<?= $monto_uf;?>" /><?php echo number_format($monto_uf,2);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Tipo de pago: </div><div></div><div class="cell"><input type="hidden" name="tipo" value="<?= $tipo?>" /><? if($tipo==0)
		{echo 'arriendo mensual';} elseif ($tipo==1){echo 'ee mensual';};?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Periodo: </div><div></div><div class="cell"><input type="hidden" name="periodo" value="<?=$periodo;?>" /><?=$periodo;?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Completa Periodo: </div><div></div><div class="cell"><input type="checkbox" name="completa_periodo" checked/></div>
	</div>
	<div class="row">
		<div class="cell formtext">Notas: </div><div></div><div class="cell"><input type="notas" name="notas" value="<?=$notas;?>" /></div>
	</div>

	<div class="row"><div class="cell formtext"></div><div class="cell">
     <button class="button-back" type="button" onclick="history.back();">BACK</button></div>
    <div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
	<input type="hidden" name="co_id" value="<?=$co_id;?>">
	</form>

</form>
<?php require_once($path_include."/cmifooter.php");
