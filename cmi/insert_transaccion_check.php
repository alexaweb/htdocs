<?php
	$current=31;
	$page_category = "cuentas corrientes";
	$page_name = "giro de cmi";
	require_once('../../includes/cmi_common.php');


    $fecha = date("Y-m-d");

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {

        // Ensure that the user has entered a non-empty password
        if(empty($_POST['tr_fecha_valor']))
        {
            die("Ingresar fecha.");
        }

        if(empty($_POST['tr_cc_id']))
        {
            die("Ingresar Cuenta Corriente.");
        }
        if(empty($_POST['tr_monto']))
        {
            die("Ingresar Monto.");
        }

        $tr_fecha_valor = $_POST['tr_fecha_valor'];
		$tr_fecha_abono = $_POST['tr_fecha_abono'];
        $tr_monto = str_replace(".","",$_POST['tr_monto']);
		$tr_cc_id = $_POST['tr_cc_id'];
        $tr_moneda = $_POST['tr_moneda'];
        $tr_descripcion = $_POST['tr_descripcion'];

		$sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc where cc_id = $tr_cc_id";
		$stmt = $db->prepare($sql);
		$result = $stmt->execute();
		$row = $stmt->fetch();



    }
	else {
	// This redirects the user back to the login page after they register
        header("Location: insert_transacciones.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to insert_transacciones.php");
	}
?>



<h4>Confirmar giro DE cmi</h4>

<form action="post/insert_transaccion_post.php" method="post">
	<div class="wrapper">
	<div class="table">
	<div class="row"><div class="cell formtext">
    Fecha Abono:</div><div class="cell"><?php echo $tr_fecha_abono;?>
    <input type="hidden" name="tr_fecha_abono" value="<?php echo $tr_fecha_abono;?>" />
    </div></div><div class="row"><div class="cell formtext">
    Fecha Valor:</div><div class="cell"><?php echo $tr_fecha_valor;?>
    <input type="hidden" name="tr_fecha_valor" value="<?php echo $tr_fecha_valor;?>" />
    </div></div><div class="row"><div class="cell formtext">
    Cuenta Corriente:</div><div class="cell"> <?php echo $row['entidad_codigo_b']?>
	<input type="hidden" name="tr_cc_id" value="<?php echo $tr_cc_id;?>"  />
    </div></div><div class="row"><div class="cell formtext">
    Moneda:</div><div class="cell"><?php echo $tr_moneda;?>
    <input type="hidden" name="tr_moneda" value="<?php echo $tr_moneda;?>"  />
    </div></div><div class="row"><div class="cell formtext">
    Monto:</div><div class="cell"> <?php echo "$ ".number_format($tr_monto);?>
    </div></div><div class="row"><div class="cell formtext">
	<input type="hidden" name="tr_monto" value="<?php echo $tr_monto;?>" />
    Descripción:</div><div class="cell"> <input type="text" name="tr_descripcion" value="<?php echo $tr_descripcion;?>" />
	</div></div><div class="row"><div class="cell">
     <button class="button-back" type="button" onclick="history.back();">BACK</button></div><div class="cell"><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>

</form>

<?php require_once($path_include."/cmifooter.php");
