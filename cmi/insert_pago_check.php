<?php
	$current=32;
	$page_category = "cuentas corrientes";
	$page_name = "pago a cmi";
	require_once('../../includes/cmi_common.php');



    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_POST)){
	// This redirects the user back to the login page after they register
        header("Location: insert_transacciones.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to insert_transacciones.php");
	} else
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

		//$ano = (int) date("Y", strtotime( '-1 month' ) );
		$fecha_valor = $_POST['tr_fecha_valor'];
        $fecha_abono = $_POST['tr_fecha_abono'];

        $tr_monto = -str_replace(",","",str_replace(".","",$_POST['tr_monto']));
		$tr_cc_id = $_POST['tr_cc_id'];
        $tr_moneda = $_POST['tr_moneda'];
        $tr_descripcion = $_POST['tr_descripcion'];

		$sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc where cc_id = $tr_cc_id";
		$stmt = $db->prepare($sql);
		$result = $stmt->execute();
		$row = $stmt->fetch();
        $entidad_codigo = $row['entidad_codigo_b'];

    }
?>



<h4>Confirmar pago A cmi</h4>

<form action="post/insert_transaccion_post.php" method="post">
	<div class="wrapper">
	<div class="table">
	<div class="row"><div class="cell formtext">
	Fecha Abono:</div><div class="cell"><input type="hidden" name="tr_fecha_abono" value="<?php echo $fecha_abono;?>" /> <?php echo $fecha_abono;?>
    </div></div><div class="row"><div class="cell formtext">
	Fecha Valor:</div><div class="cell"><input type="hidden" name="tr_fecha_valor" value="<?php echo $fecha_valor;?>" /> <?php echo $fecha_valor;?>
    </div></div><div class="row"><div class="cell formtext">
    Cuenta Corriente:</div><div class="cell"> <?php echo $entidad_codigo;?>
	<input type="hidden" name="tr_cc_id" value="<?php echo $tr_cc_id;?>" readonly />
    </div></div><div class="row"><div class="cell formtext">
    Moneda:</div><div class="cell"><input type="hidden" name="tr_moneda" value="<?php echo $tr_moneda;?>"  /><?php echo $tr_moneda;?>
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
