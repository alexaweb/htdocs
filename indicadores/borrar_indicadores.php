<?php
	$current=35;
	$page_category = "indicadores";
	$page_name = "borrar indicadores";
	//$dbfile = "indicadoresDB.php";
	//$menufile = "indicadoresmenu.php";
        //require_once ('/var/www/includes/common.php');
	//require_once('/var/www/includes/cmiheader.php');
        require_once('../../includes/indicadores_common.php');


    $fecha = date("Y-m-d");


    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(!empty($_POST))
    {
		if(empty($_POST['tr_fecha']))
        {
            die("Ingresar fecha.");
        }

		//acá debo obtener los indicadores antes de insertarlos

        $tr_fecha = $_POST['tr_fecha'];

        try
        {
            $sql = "call proc_insert_indicadores_uf_tabuf360('{$tr_fecha}',$tr_uf,$tr_tabuf360);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }


        // This redirects the user back to the login page after they register
        header("Location: /audit/indicadores/display_indicadores_mes.php?");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to display_indicadores.php");
    }

?>
<h2>Borrar Indicadores</h2>

<form action="/audit/indicadores/carga/borrar_indicadores_dia.php" method="get">
    Fecha:
    <input type="text" name="fecha" value="<?php echo $fecha?>" />
    <br />
	<button class="button-reset" type="submit" value="Submit">Borrar</button>
</form>



<?php require_once($path_include."/cmifooter.php");
