<?php
	$current=33;
	$page_category = "cuentas corrientes";
	$page_name = "resetear saldos";
	require_once('../../includes/cmi_common.php');

    $fecha = date("Y-m-d");

    $sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc order by entidad_codigo_b asc";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $data_cc = $stmt->fetchAll();

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
		if(empty($_POST['tr_fecha']))
        {
            die("Ingresar fecha.");
        }
        if(empty($_POST['tr_cc_id']))
        {
            die("Ingresar Cuenta Corriente.");
        }

        $tr_fecha = $_POST['tr_fecha'];
		$tr_cc_id = $_POST['tr_cc_id'];

        try
        {
            $sql = "call proc_reset_cc($tr_cc_id,'{$tr_fecha}');";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


        // This redirects the user back to the login page after they register
        header("Location: display_cartola_transacciones.php?tr_cc_id=$tr_cc_id&fecha_orden=1");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_cartola_transacciones.php");
    }

?>

<h4>Reset Saldos</h4>

<form action="reset_saldos.php" method="post">
    Fecha:
    <input type="text" name="tr_fecha" value="<?php echo $fecha?>" />
    <br />
    Cuenta Corriente:
    <select name="tr_cc_id" id="cuenta_corriente">
        <?php foreach ($data_cc as $row): ?>
            <option value=<?=$row['cc_id']?>><?=$row['entidad_codigo_b']?></option>
        <?php endforeach ?>
    </select><br />
	<button class="button-reset" type="submit" value="Submit">Reset</button>
</form>

<?php require_once($path_include."/cmifooter.php");
