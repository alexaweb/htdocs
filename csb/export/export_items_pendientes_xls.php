<?php


if (PHP_SAPI == 'cli')
	die('This example should only be run from a Web Browser');


//require_once('../../../includes/csb_common.php');
	$dbfile = "csbDB.php";
	//$path = "/var/www/html/cmi";
	require_once('../../../includes/common.php');
	require_once('../../../includes/PHPExcel.php');




// Create new PHPExcel object
$objPHPExcel = new PHPExcel();



// Set document properties
$objPHPExcel->getProperties()->setCreator("CMI Inversiones S.A.")
							 ->setLastModifiedBy("CMI Inversiones S.A.")
							 ->setTitle("CSB")
							 ->setSubject("Procurement")
							 ->setDescription("Status Compras y Envios")
							 ->setKeywords("office 2007 openxml php")
							 ->setCategory("test");

 // Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);

// Initialise the Excel row number
$rowCount = 1;

       /* $p_orden = $_GET['p_orden'];
        if(empty($p_orden))
        {
            
            try
            {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items_pendientes();";
		//	echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
			$stmt->closeCursor();
            }
            catch(PDOException $ex)
            {
               die("Failed to run query: " . $ex->getMessage());
            }
        } else
        { 
        */
        
          try
            {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_items_pendientes_order($p_orden);";
		//	echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
			$stmt->closeCursor();
            }
            catch(PDOException $ex)
            {
               die("Failed to run query: " . $ex->getMessage());
            }   
        //}   

 // Set the active Excel worksheet to sheet 0
$objPHPExcel->setActiveSheetIndex(0);
//echo "llegue1";
// Initialise the Excel row number
$rowCount = 1;
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, 'ID');
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, 'Buque');
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, 'Descripcion');
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, 'Status Item');
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, 'Status Fecha');
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, 'Status Detalle');
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, 'Status Docs');
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, 'Status Docs Fecha');
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, 'Status Docs Detalle');
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, 'Proveedor');
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, 'Cotizacion');
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, 'Factura');
        $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, 'Fecha RFQ');
        $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, 'Container');
        $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, 'BL o AWB');
        $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, 'Carrier');
        $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, 'Origen');
$rowCount = 2;
//echo "llegue2";
foreach ($data_tr as $row) {
	$objPHPExcel->getActiveSheet()->SetCellValue('A'.$rowCount, $row['item_id']);
	$objPHPExcel->getActiveSheet()->SetCellValue('B'.$rowCount, $row['item_buque']);
	$objPHPExcel->getActiveSheet()->SetCellValue('C'.$rowCount, $row['item_descripcion']);
	$objPHPExcel->getActiveSheet()->SetCellValue('D'.$rowCount, $row['item_status']);
	$objPHPExcel->getActiveSheet()->SetCellValue('E'.$rowCount, $row['si_fecha']);
        $objPHPExcel->getActiveSheet()->SetCellValue('F'.$rowCount, $row['si_status']);
        $objPHPExcel->getActiveSheet()->SetCellValue('G'.$rowCount, $row['sd_status']);
        $objPHPExcel->getActiveSheet()->SetCellValue('H'.$rowCount, $row['sd_fecha']);
        $objPHPExcel->getActiveSheet()->SetCellValue('I'.$rowCount, $row['sd_fecha']);
        $objPHPExcel->getActiveSheet()->SetCellValue('J'.$rowCount, $row['item_proveedor']);
        $objPHPExcel->getActiveSheet()->SetCellValue('K'.$rowCount, $row['item_cotizacion']);
        $objPHPExcel->getActiveSheet()->SetCellValue('L'.$rowCount, $row['item_factura']);
        $objPHPExcel->getActiveSheet()->SetCellValue('M'.$rowCount, $row['item_fecha_rfq']);
        $objPHPExcel->getActiveSheet()->SetCellValue('N'.$rowCount, $row['item_container']);
        $objPHPExcel->getActiveSheet()->SetCellValue('O'.$rowCount, $row['item_bl_awb']);
        $objPHPExcel->getActiveSheet()->SetCellValue('P'.$rowCount, $row['item_carrier']);
        $objPHPExcel->getActiveSheet()->SetCellValue('Q'.$rowCount, $row['item_origen']);

	$objPHPExcel->getActiveSheet()
    ->getStyle('C'.$rowCount)
    ->getAlignment()
    ->setHorizontal(PHPExcel_Style_Alignment::HORIZONTAL_RIGHT);
        
        //echo "llegue".$rowCount.$row['item_descripcion'];

	/*$objPHPExcel->getActiveSheet()
    ->getStyle('C'.$rowCount)
    ->getNumberFormat()
    ->setFormatCode(PHPExcel_Style_NumberFormat::FORMAT_NUMBER_COMMA_SEPARATED1);*/

	$rowCount++;
}

$objPHPExcel->getActiveSheet()->getColumnDimension("A")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("B")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("C")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("D")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("E")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("F")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("G")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("H")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("I")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("J")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("K")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("L")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("M")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("N")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("O")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("P")->setAutoSize(true);
$objPHPExcel->getActiveSheet()->getColumnDimension("Q")->setAutoSize(true);

$filename = "CSB_PROCUREMENT-". date("Y-m-d") . ".xlsx";

 header('Content-Type: application/vnd.ms-excel');
 header('Content-Disposition: attachment;filename='.$filename);
 header('Cache-Control: max-age=0');

 $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
 $objWriter->save('php://output');

 die();



?>
