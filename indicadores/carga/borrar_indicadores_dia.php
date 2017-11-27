<?php
//$path = $_SERVER['DOCUMENT_ROOT'];
//require_once $path.'/includes/mindicadorDB.php';

// ejecución web
	$dbfile = "indicadoresDB.php";
	$menufile = "indicadoresmenu.php";
    require_once ('../../../includes/common.php');
	//require_once('/var/www/includes/cmiheader.php');
// ejecución SHELL
//require_once '/var/www/html/includes/cmiDB.php';
	

// DEFINE LA FECHA PARA BUSQUEDA DE INDICADORES
date_default_timezone_set('America/Santiago');
//$fecha = "2016-07-17";

if(empty($_GET['fecha']))
{
	die("Debe ingresar fecha.");
} else {
	$fecha = $_GET['fecha'];
}

// falta hacer loop para completar rangos de fechas
// luego dejar archivo que se ejecute de forma diaria (o mensual y solo traigo la TAB?)
$first_date = strtotime($fecha);
$second_date = strtotime('-1 day', $first_date);
$ayer = date('Y-m-d', $second_date);
$dia = date("d",$first_date);
$mes = date("m",$first_date);
$ano = date("Y",$first_date);

		try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "delete from indicadores.indicadores where fecha='{$fecha}';";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            //$data = $stmt->fetchAll();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

$stmt = null;
$stmt_query = null;
$conn = null;


// ejecución web
	header("Location: /audit/indicadores/display_indicadores.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
    die("Redirecting to /audit/indicadores/display_indicadores.php");
?>