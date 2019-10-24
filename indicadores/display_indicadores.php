<?php

	$current=41;
	$page_category = "indicadores";
	$page_name = "todos";
	//$dbfile = "indicadoresDB.php";
	//$menufile = "indicadoresmenu.php";
        //require_once ('/var/www/includes/common.php');
	//require_once('/var/www/includes/cmiheader.php');
        require_once('../../includes/indicadores_common.php');


    // DEFINE LA FECHA PARA BUSQUEDA DE INDICADORES
    //date_default_timezone_set('America/Santiago');
    //$fecha = "2016-07-17";
    /*$fecha = date("Y-m-d");

    //Obtengo el valor de la UF para desplegar antes de hacer cualquier cosa
    $sql = "select valor from indicadores where codigo='uf' and fecha = '{$fecha}'";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $row = $stmt->fetch();
    $valor_uf = $row['valor'];*/
    //echo "El valor de la UF el día de hoy $fecha es: $valor_uf<br><br>";


    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$ano = $_GET['ano'];
	$mes = $_GET['mes'];

    if(empty($_GET['ano']))
    {
            $ano = date("Y");
			//die("Ingresar ano.");
    }
	if(empty($_GET['mes']))
    {
			$mes = date("m");
            //die("Ingresar mes.");
    }


        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_indicadores($ano,$mes);";
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



<h2>Indicadores <?php echo $cc_entidad_codigo_b;?></h2>

	<form action="display_indicadores.php">
		<input type="text" name="ano" value="<?=$ano;?>">
		<input type="text" name="mes" value="<?=$mes;?>">
		<button class="button submit" type="submit" value="Submit">submit</button>
	</form>
<div class="table">
	<div class="rowheader">
		<div class="cell">Fecha</div>
		<div class="cell">Mes</div>
		<div class="cell">Día</div>
		<div class="cell">Indicador</div>
		<div class="cell">Valor</div>
	</div>
    <?php foreach ($data as $row): ?>
        <div class="row">
			<div class="cell"><?=$row['fecha'];?></div>
			<div class="cell"><?=$row['mes'];?></div>
			<div class="cell"><?=$row['dia'];?></div>
			<div class="cell"><?=$row['codigo'];?></div>
			<div class="numbercelle"><?=number_format($row['valor'],2);?></div>
		</div>
    <?php endforeach ?>
</div>



<?php require_once($path_include."/cmifooter.php");
