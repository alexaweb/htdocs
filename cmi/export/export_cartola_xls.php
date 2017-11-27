<?php
if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');

	$dbfile = "cmiDB.php";
	$path = "/var/www/html/cmi";
	require_once('/var/www/includes/common.php');
	require_once('/var/www/includes/PHPExcel.php');




// Create new PHPExcel object
$objPHPExcel = new PHPExcel();


    if(empty($_GET['tr_cc_id']))
    {
            die("Ingresar CC_ID.");
    }
	    if(empty($_GET['fecha_orden']))
    {
            die("Ingresar 'fecha_orden':<br> 1: ordenar por fecha_abono <br>2: ordenar por fecha_valor.");
    }

       // $tr_fecha = $_POST['tr_fecha'];
        $tr_cc_id = $_GET['tr_cc_id'];

          //Obtengo el valor de la UF para desplegar antes de hacer cualquier cosa
        $sql = "select entidad_codigo_b, cc_spread,cc_fecha_inicio from cc where cc_id=$tr_cc_id";
        $stmt = $db->prepare($sql);
        $result = $stmt->execute();
        $row = $stmt->fetch();
        $cc_entidad_codigo_b = $row['entidad_codigo_b'];
        $cc_spread = $row['cc_spread'];
        $cc_fecha_inicio = $row['cc_fecha_inicio'];

		$fecha_orden = $_GET['fecha_orden'];

// Set document properties
$objPHPExcel->getProperties()->setCreator("CMI Inversiones S.A.")
							 ->setLastModifiedBy("CMI Inversiones S.A.")
							 ->setTitle($cc_entidad_codigo_b)
							 ->setSubject($cc_entidad_codigo_b)
							 ->setDescription($cc_entidad_codigo_b)
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("test");

 // Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);

// Initialise the Excel row number
$rowCount = 1;

        try
        {
            //$sql = "select tr_fecha_abono as Fecha, tr_moneda as Moneda, tr_monto as Monto, tr_descripcion as Descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' and tr_tipo_transaccion = 0 order by tr_fecha_abono;";

						$sql = "select tr_fecha_valor as fecha, tr_tipo_transaccion as tipo, count(*) as cantidad, sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = '{$tr_cc_id}' and tr_tipo_transaccion=0 group by  fecha
						union
						select tr_fecha_valor as fecha, tr_tipo_transaccion as tipo, count(*) as cantidad, sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = '{$tr_cc_id}' and tr_tipo_transaccion=1 group by  fecha
						union
						select tr_fecha_valor as fecha, tr_tipo_transaccion as tipo, count(*) as cantidad, sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = '{$tr_cc_id}' and tr_tipo_transaccion=2 group by  fecha
						order by fecha asc, tipo;";
						//$sql = "call proc_display_transacciones_cc($tr_cc_id,$fecha_orden);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
			$stmt->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

 // Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);

// Initialise the Excel row number
$rowCount = 1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'fecha');
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'cantidad');
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'monto');
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'monto_uf');
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'tipo');
$rowCount = 2;

foreach ($data_tr as $row) {
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['fecha']);
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['cantidad']);
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, number_format($row['monto']));
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, number_format($row['monto_uf'],6));
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['tipo']);

	$objPHPExcel->getActiveSheet()
    ->getStyle('C'.$rowCount)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);

	/*$objPHPExcel->getActiveSheet()
    ->getStyle('C'.$rowCount)
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/

	$rowCount++;
}

$objPHPExcel->getActiveSheet()
    ->getColumnDimension("A")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()
		    ->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()
    ->getColumnDimension("C")->setAutoSize(true);
	$objPHPExcel->getActiveSheet()
    ->getColumnDimension("D")->setAutoSize(true);
		$objPHPExcel->getActiveSheet()
	    ->getColumnDimension("E")->setAutoSize(true);

$filename = "CARTOLA-CMI-".$cc_entidad_codigo_b."-". date("Y-m-d") . ".xlsx";

 header('Content-Type: application/vnd.ms-excel');
 header('Content-Disposition: attachment;filename='.$filename);
 header('Cache-Control: max-age=0');

 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');

 die();



?>
