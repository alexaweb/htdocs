<?php
//$path = $_SERVER['DOCUMENT_ROOT'];
//require_once $path.'/includes/mindicadorDB.php';

// ejecución web
//$path = $_SERVER['DOCUMENT_ROOT'];
//$dbfile = "indicadoresDB.php";
//$menufile = "indicadoresmenu.php";
//require $path.'/includes/common.php';
// ejecución SHELL
require_once '/var/www/includes/indicadoresDB.php';
	
$apiUrl = 'http://api.sbif.cl/api-sbifv3/recursos_api/';
//$apiUrl = 'http://163.247.45.76/api-sbifv3/recursos_api/';
$formato = 'json';
$apikey = '98a8aff0a6634a111b164f6aa505b027f1b4f96a';

// DEFINE LA FECHA PARA BUSQUEDA DE INDICADORES
date_default_timezone_set('America/Santiago');
//$fecha = "2016-07-17";

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
$third_date = strtotime('-2 day', $first_date);
$ayer = date('Y-m-d', $second_date);
$ante_ayer = date('Y-m-d', $third_date);
$dia = date("d",$first_date);
$mes = date("m",$first_date);
$ano = date("Y",$first_date);

$dia_ayer = date("d",$second_date);
$mes_ayer = date("m",$second_date);
$ano_ayer = date("Y",$second_date);

//echo "$fecha-$ayer<br>";
	  
try {
    $conn = new PDO("mysql:host=$dbhost;dbname=$dbname", 'root', 'oSTI.sCA');
	
    // set the PDO error mode to exception
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // prepare sql and bind parameters
    $stmt = $conn->prepare("INSERT INTO indicadores.indicadores (fecha,ano,mes,dia,codigo,nombre, unidad_medida,valor) VALUES (:fecha, :ano, :mes, :dia, :codigo, :nombre, :unidad_medida, :valor);");
    $stmt->bindParam(':fecha', $fecha_insert);
    $stmt->bindParam(':ano', $ano_insert);
    $stmt->bindParam(':mes', $mes_insert);
    $stmt->bindParam(':dia', $dia_insert);
    $stmt->bindParam(':codigo', $codigo);
    $stmt->bindParam(':nombre', $nombre);
	$stmt->bindParam(':unidad_medida', $unidad_medida);
	$stmt->bindParam(':valor', $valor);
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
	die();
}
	  
	  	  
// TAB UF 360
$apiQuery = $apiUrl."tab/$ano_ayer/$mes_ayer/dias/$dia_ayer?formato=$formato&apikey=$apikey";
//Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
if ( ini_get('allow_url_fopen') ) {
    $json = @file_get_contents($apiQuery);
} else {
    //De otra forma utilizamos cURL
    $curl = curl_init($apiQuery);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);
}

$fecha_insert = $ayer;
$dia_insert = $dia_ayer;
$mes_insert = $mes_ayer;
$ano_insert = $ano_ayer;

$indicador = json_decode($json);
//echo $indicador->TABs[0]->Fecha.' a-a '.$indicador->TABs[0]->Valor.'<br>';
// insert a row
	if($indicador) {
		$fecha_insert = $indicador->TABs[0]->Fecha;
		$valor = str_replace(",",".",$indicador->TABs[0]->Valor);
		$unidad_medida = "Porcentaje";
		$codigo = "tabuf360";
		$nombre = "Tasa TAB UF 360d";
		$stmt->execute();
	} else { //traigo el valor del día anterior (antes de ayer)...
		$sql = "select valor from indicadores.indicadores where codigo='tabuf360' and fecha='{$ante_ayer}';";
		//echo $sql."<br><br>";
		$stmt_query = $conn->prepare($sql);
		$stmt_query->execute();
		$result = $stmt_query->fetchAll();

		// ... y lo inserto con fecha de ayer
		$valor=$result[0]['valor'];
		$unidad_medida = "Porcentaje";
		$codigo = "tabuf360";
		$nombre = "Tasa TAB UF 360d";
		$stmt->execute();
	}
echo "<br>Valor TAB UF 360 $ayer: $valor<br>\n";

// UF
$apiQuery = $apiUrl."uf/$ano/$mes/dias/$dia?formato=$formato&apikey=$apikey";
//Es necesario tener habilitada la directiva allow_url_fopen para usar file_get_contents
if ( ini_get('allow_url_fopen') ) {
    $json = file_get_contents($apiQuery);
} else {
    //De otra forma utilizamos cURL
    $curl = curl_init($apiQuery);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $json = curl_exec($curl);
    curl_close($curl);
}
$indicador = json_decode($json);


 // insert a row
	if($indicador) {
		$fecha_insert = $indicador->UFs[0]->Fecha;
		$ano_insert = $ano;
		$mes_insert = $mes;
		$dia_insert = $dia;
		$valor = str_replace(",",".",str_replace(".","",$indicador->UFs[0]->Valor));
		$unidad_medida = "Pesos";
		$codigo = "uf";
		$nombre = "Unidad de Fomento (UF)";
		$stmt->execute();
	}
	
echo "<br>Valor UF $fecha: $valor<br>\n";

$stmt = null;
$stmt_query = null;
$conn = null;


// ejecución web
//	header("Location: /indicadores/display_indicadores.php");
        
        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
//    die("Redirecting to /indicadores/display_indicadores.php");
?>
