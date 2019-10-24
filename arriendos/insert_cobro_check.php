<?php
	$current=331;
	$page_category = "arriendos";
	$page_name = "ingresar cobro";
	require_once('../../includes/arriendos_common.php');



    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_GET)){
	// This redirects the user back to the login page after they register
        header("Location: insert_cobro.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to insert_cobro.php");
	} else
    {

        // Ensure that the user has entered a non-empty password
        if(empty($_GET['fecha']))
        {
            die("Ingresar fecha.");
        }

		if(empty($_GET['periodo']))
        {
            die("Ingresar Periodo.");
        }
		/*if(empty($_GET['tipo']))
        {
           die("Ingresar si es arriendo o electricidad.");
        }*/
        if(empty($_GET['monto']) and empty($_GET['monto_uf']))
        {
            die("Ingresar Monto.");
        }

		//$ano = (int) date("Y", strtotime( '-1 month' ) );
		$fecha = $_GET['fecha'];
        $monto = str_replace('$','',str_replace('.','',$_GET['monto']));
		$monto_uf = $_GET['monto_uf'];
		$periodo = $_GET['periodo'];
		$tipo = $_GET['tipo'];
        $descripcion = $_GET['descripcion'];
		$co_id = $_GET['co_id'];

		//echo $co_id.'.'.$_GET['co_id'].'ww';
		//echo $tipo . "afda";
		if(empty($_GET['monto_uf']))
		{
			$sql = "call arriendos.get_valor_uf('{$fecha}');";
			$stmt = $db->prepare($sql);
			$result = $stmt->execute();
			$row = $stmt->fetch();
			$valor_uf = $row['valor'];
			$monto_uf = $monto / $valor_uf;
		}

		if(empty($_GET['monto']))
		{
			$sql = "call arriendos.get_valor_uf('{$fecha}');";
			$stmt = $db->prepare($sql);
			$result = $stmt->execute();
			$row = $stmt->fetch();
			$valor_uf = $row['valor'];
			$monto = $monto_uf * $valor_uf;
		}

		/*
		$sql = "select cb_monto_uf, cb_fecha_vencimiento from cobro where co_id = $co_id and cb_periodo = '{$periodo}';";
		//echo $sql;
	    $stmt = $db->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetch();
        $monto_uf_xpagar = $row['cb_monto_uf'];
		$fecha_vencimiento = $row['cb_fecha_vencimiento'];
		*/
		//echo $fecha."-".$monto."-".$monto_uf."-".$notas."-".$tipo."-".$periodo."-".$monto_uf_xpagar;
		$sql = "select c.co_id, a.ar_id, a.ar_nombre, p.pr_codigo, c.co_tipo from contrato c join arrendatario a on c.ar_id = a.ar_id join propiedad p on c.pr_id = p.pr_id where c.co_id = $co_id;";
		$stmt = $db->prepare($sql);
		$result = $stmt->execute();
		$data = $stmt->fetch();
    }



?>



<h2>Confirmar cobro</h2>

<form action="post/insert_cobro_post.php" method="post">

	<div class="wrapper">
	<div class="table">
	<div class="row">
		<div class="cell formtext">Contrato: </div><div class="textcell"><input type="hidden" name="co_id" value="<?=$co_id;?>" /><?=$data['pr_codigo']." - ".$data['ar_nombre'].($data['co_tipo']=='1'?" - EE":"")?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Periodo: </div><div class="cell"><input type="hidden" name="periodo" value="<?=$periodo;?>" /><?=$periodo;?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Fecha Vencimiento:</div><div class="cell"><input type="hidden" name="fecha" value="<?=$fecha?>" /><?=$fecha?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (CLP): </div><div class="numbercell"><input type="hidden" name="monto" value="<?= $monto?>" />$ <?=number_format($monto);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (UF): </div><div class="numbercell"><input type="hidden" name="monto_uf" value="<?= $monto_uf;?>" /><?php echo number_format($monto_uf,2);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Tipo de pago: </div><div class="cell"><input type="hidden" name="tipo" value="<?= $tipo?>" /><? if($tipo==0)
		{echo 'arriendo mensual';} elseif ($tipo==1){echo 'electricidad mensual';};?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Descripción: </div><div class="cell"><input type="text" name="descripcion" value="<?=$descripcion;?>" /></div>
	</div>

	<div class="row"><div class="cell">
     <button class="button-back" type="button" onclick="history.back();">BACK</button></div>
    <div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
	<input type="hidden" name="co_id" value="<?=$co_id;?>">
	</form>

</form>

<?php require_once($path_include."/cmifooter.php");
