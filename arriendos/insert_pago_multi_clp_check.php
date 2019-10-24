<?php
	$current=333;
	$page_category = "arriendos";
	$page_name = "ingresar pago electricidad (múltiple)";
	require_once('../../includes/arriendos_common.php');



    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_GET)){
	// This redirects the user back to the login page after they register
        header("Location: insert_pago_multi_clp.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to insert_pago_multi_clp.php");
	} else
    {

        // Ensure that the user has entered a non-empty password
        if(empty($_GET['fecha']))
        {
            die("Ingresar fecha.");
        }

        if(empty($_GET['co_id']))
        {
            die("Ingresar contrato .");
        }
        if(empty($_GET['monto']))
        {
            die("Ingresar Monto.");
        }
		if(empty($_GET['periodo']))
        {
            die("Ingresar Periodo.");
        }
		if(empty($_GET['tipo']))
        {
            die("Ingresar Periodo.");
        }

		//$ano = (int) date("Y", strtotime( '-1 month' ) );
		$fecha = $_GET['fecha'];
        $monto = str_replace('$','',str_replace('.','',$_GET['monto']));
		$periodo = $_GET['periodo'];
		$tipo = $_GET['tipo'];
        $notas = $_GET['notas'];
		$co_id = $_GET['co_id'];



    }



?>


<h2>Confirmar pago de Electricidad</h2>

<form action="post/insert_pago_multi_clp_post.php" method="post">

	<div class="wrapper">
	<div class="table">

	<div class="row">
		<div class="cell formtext">Contrato:</div><div><input type="hidden" name="co_id" value="<?=$co_id?>" /><?=$co_id?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Fecha:</div><div><input type="hidden" name="fecha" value="<?=$fecha?>" /><?=$fecha?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (CLP): </div><div class="numbercell"><input type="hidden" name="monto" value="<?= $monto?>" />$ <?=number_format($monto);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto (UF): </div><div class="numbercell"><input type="hidden" name="monto_uf" value="<?= $monto_uf;?>" /><?php echo number_format($monto_uf,2);?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Tipo de pago: </div><div class="cell"><input type="hidden" name="tipo" value="<?= $tipo?>" /><? if($tipo==0)
		{echo 'Arriendo';} elseif ($tipo==1){echo 'Electricidad';};?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Periodo: </div><div class="cell"><input type="hidden" name="periodo" value="<?=$periodo;?>" /><?=$periodo;?></div>
	</div>
	<div class="row">
		<div class="cell formtext">Notas: </div><div class="cell"><input type="notas" name="notas" value="<?=$notas;?>" /></div>
	</div>

	<div class="row"><div class="cell">
     <button class="button-back" type="button" onclick="history.back();">BACK</button></div>
    <div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>

	</form>

</form>
<?php require_once($path_include."/cmifooter.php");
