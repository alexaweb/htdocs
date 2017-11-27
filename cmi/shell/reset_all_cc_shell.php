<?php
//$path = $_SERVER['DOCUMENT_ROOT'];
//require_once $path.'/includes/mindicadorDB.php';

// ejecución web
//$path = $_SERVER['DOCUMENT_ROOT'];
//$dbfile = "indicadoresDB.php";
//$menufile = "indicadoresmenu.php";
//require $path.'/includes/common.php';
// ejecución SHELL
require_once '/var/www/includes/cmiDB.php';
        

// DEFINE LA FECHA PARA BUSQUEDA DE INDICADORES
date_default_timezone_set('America/Santiago');

if(empty($_GET['fecha']))
{
        $fecha = date("Y-m-d");
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

//echo "$fecha-$ayer<br>";
          
try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", 'root', 'oSTI.sCA');
        
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $stmt = $conn->prepare("call proc_reset_all_cc");
        $stmt->execute();
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
        die();
}
          

$stmt = null;
$conn = null;


?>
