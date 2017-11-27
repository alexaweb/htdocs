<?php
	$current=22;
	//require_once('../../includes/cmi_common.php');
		$dbfile = "cmiDB.php";
		//$menufile = "cmimenu.php";
		//$path = "/var/www/html/cmi";
		require_once('/var/www/includes/common.php');
		//require_once('/var/www/includes/cmiheader.php');
		setlocale(LC_TIME, 'es_ES.UTF-8');
		date_default_timezone_set('America/Santiago');


			require_once('/var/www/includes/fpdf/fpdf.php');
			require_once('/var/www/includes/phpmailer/PHPMailerAutoload.php');





    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
		if (defined('STDIN')){
			$ano=$argv[1];
			$mes=$argv[2];
			$cc_id=$argv[3];
		} else {
			$ano = $_GET['ano'];
			$mes = $_GET['mes'];
			$cc_id = $_GET['cc_id'];
		}

		$fecha = date("Y-m-d", strtotime( '-1 days' ) );
	  if(empty($cc_id))
    {
            //por defecto seleccionamos la fecha de ayer
			$cc_id=6;
    }
	 if(empty($ano) or empty($mes))
    {
            //por defecto seleccionamos mes pasado
			$ano = (int) date("Y", strtotime( '-1 month' ) );
			$mes = (int) date("m", strtotime( '-1 month' ) );
		}


    try
    {
            $sql = "call proc_display_interes_devengado_cc_ano_mes($cc_id, $ano, $mes);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $row = $stmt->fetch();
						$interes_devengado = $row['interes'];
						$interes_devengado_uf = $row['interes_uf'];
						$entidad_codigo = $row['entidad_codigo_b'];
						$entidad_nombre = $row['entidad_nombre'];
						$entidad_rut = $row['entidad_rut'];
						$cc_fecha_inicio =  $row['cc_fecha_inicio'];
						$ultimo_dia_mes =  $row['ultimo_dia_mes'];
    }
    catch(PDOException $ex)
    {
            die("Failed to run query: " . $ex->getMessage());
    }

    $sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc order by entidad_codigo_b asc";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $data_cc = $stmt->fetchAll();

	//	$nombremes = date('F', mktime(0, 0, 0, $mes, 10));
	//$string = "1/".$mes."/".$ano;
	$date_ultimo_dia_mes = DateTime::createFromFormat("Y-m-d", $ultimo_dia_mes);
	$date_fecha_inicio = DateTime::createFromFormat("Y-m-d", $cc_fecha_inicio);


	$dias_en_el_mes = substr($ultimo_dia_mes,-2);
	$datesss = strtotime($ultimo_dia_mes .' -'.$dias_en_el_mes.' days');
  $fecha1=date('Y-m-d', $datesss);
	$fecha2 = $ultimo_dia_mes;
	//Obtener saldos de fin de mes y fin del mes anterior
	//Con eso se calcula el efecto UF restando a la diferencia en pesos el monto de $interes_devengado
	try
  {
            $sql = "call proc_display_saldos_cc('{$fecha2}','{$cc_id}');";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $row = $stmt->fetch();
						$stmt->closeCursor();
  }
  catch(PDOException $ex)
  {
      die("Failed to run query: " . $ex->getMessage());
  }
					$k_acum_clp=$row['capital_clp'];
					$k_acum_uf=$row['capital'];
					$i_acum_clp=$row['interes_clp'];
					$i_acum_uf=$row['interes'];


				try
				{
						$sql2 = "call proc_display_saldos_cc('{$fecha1}','{$cc_id}');";
			//echo $sql;
						$stmt2 = $db->prepare($sql2);
						$result2 = $stmt2->execute();
						$row2 = $stmt2->fetch();
						$stmt2->closeCursor();
				}
				catch(PDOException $ex)
				{
						die("Failed to run query: " . $ex->getMessage());
				}
				$k_acum_clp_mespasado=$row2['capital_clp'];
				$k_acum_uf_mespasado=$row2['capital'];
				$i_acum_clp_mespasado=$row2['interes_clp'];
				$i_acum_uf_mespasado=$row2['interes'];

				try
				{
					$sql3 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = $cc_id and tr_fecha_valor > '{$fecha1}' and tr_fecha_valor <= '{$fecha2}' and tr_tipo_transaccion = 1;";
					$stmt3 = $db->prepare($sql3);
					$result3 = $stmt3->execute();
					$row3 = $stmt3->fetch();
					$stmt3->closeCursor();
				}
				catch(PDOException $ex)
				{
						die("Failed to run query: " . $ex->getMessage());
				}
				$suma_transacciones_k_clp = $row3['monto'];
				$suma_transacciones_k_uf = $row3['monto_uf'];
				$efecto_uf_mes = $k_acum_clp-$k_acum_clp_mespasado -$suma_transacciones_k_clp;

				try
				{
					$sql4 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = $cc_id and tr_fecha_valor > '{$fecha1}' and tr_fecha_valor <= '{$fecha2}' and tr_tipo_transaccion = 2;";
					$stmt4 = $db->prepare($sql4);
					$result4 = $stmt4->execute();
					$row4 = $stmt4->fetch();
					$stmt4->closeCursor();
				}
				catch(PDOException $ex)
				{
						die("Failed to run query: " . $ex->getMessage());
				}
				$suma_transacciones_i_clp = $row4['monto'];
				$suma_transacciones_i_uf = $row4['monto_uf'];
				
				try
				{
					//$sql5 = "select cc_email_list from cc where cc_id = $cc_id;";
					$sql5 = "select c.co_nombre, c.co_email from cc a join contacto_cc b on a.cc_id=b.cc_id join contacto c on b.co_id = c.co_id where a.cc_id =$cc_id";
					$stmt5 = $db->prepare($sql5);
					$result5 = $stmt5->execute();
					$row5 = $stmt5->fetchall();
					$stmt5->closeCursor();
				}
				catch(PDOException $ex)
				{
						die("Failed to run query: " . $ex->getMessage());
				}
				

		class PDF extends FPDF
		{
				function Header()
				{
				    global $title;

				    // Arial bold 15
				    $this->SetFont('Arial','B',15);
				    // Calculamos ancho y posición del título.
				    $w = $this->GetStringWidth($title)+6;
				    $this->SetX((210-$w)/2);
				    // Colores de los bordes, fondo y texto
				    $this->SetDrawColor(0,80,180);
				    $this->SetFillColor(255,255,255);
				    $this->SetTextColor(0,0,0);
				    // Ancho del borde (1 mm)
				    $this->SetLineWidth(1);
				    // Título
				   // $this->Cell($w,9,$title,1,1,'C',true);
				    // Salto de línea
				    $this->Ln(10);
				}

				function Footer()
				{
				    // Posición a 1,5 cm del final
				    $this->SetY(-15);
				    // Arial itálica 8
				    $this->SetFont('Arial','I',8);
				    // Color del texto en gris
				    $this->SetTextColor(128);
				    // Número de página
				    $this->Cell(0,10,$this->PageNo(),0,0,'R');
				}

				function ChapterTitle($num, $label)
				{
				    // Arial 12
				    $this->SetFont('Arial','',12);
				    // Color de fondo
				    $this->SetFillColor(200,220,255);
				    // Título
				    $this->Cell(0,6,"Capítulo $num : $label",0,1,'L',true);
				    // Salto de línea
				    $this->Ln(4);
				}

				function ChapterBody($file)
				{
				    // Leemos el fichero
				    $txt = "ejemplo";
				    //file_get_contents($file);
				    // Times 12
				    $this->SetFont('Times','',12);
				    // Imprimimos el texto justificado
				    $this->MultiCell(0,5,$txt);
				    // Salto de línea
				    $this->Ln();
				    // Cita en itálica
				    $this->SetFont('','I');
				    $this->Cell(0,5,'(fin del extracto)');
				}

				function NotaDeCobro($p_fecha,$p_entidad,$p_rut,$p_interes_devengado,$p_interes_devengado_uf,$p_fecha_inicio,$c11,$c12,$c13,$c14,$c21,$c22,$c23,$c24,$c31,$c41,$c42,$c43,$c44)
				{
				    // Leemos el fichero
				    $this->SetMargins(25,25,25);
				    $this->AddPage();
				    $this->SetFont('arial','B',16);

				    $txt = "Nota de Cobro - Cuenta Corriente Mercantil";
				    $this->Cell(0,0,"$txt",0,0,'C');
				    $this->Ln();
				    $this->Ln();
				    $this->SetFont('arial','',14);
				    $txt = "CMI Inversiones S.A. - $p_entidad";
				    $this->Cell(0,15,"$txt",0,0,'C');
				    $this->Ln();

				    $date_ultimo_dia_mes = DateTime::createFromFormat("Y-m-d",$p_fecha);
				    $fecha1 = strftime("%d de %B de %Y",$date_ultimo_dia_mes->getTimestamp());
				    $date_fecha_inicio = DateTime::createFromFormat("Y-m-d",$p_fecha_inicio);
				    $txt2 = strftime("%d de %B de %Y",$date_fecha_inicio->getTimestamp());
				    $this->SetFont('arial','',12);
				    $this->Cell(0,8,"Santiago, $fecha1",0,0,'R');
				    $this->Ln();
				    $this->SetFont('arial','I',12);
				    $this->Cell(0,8,'Ref: Liquidacion de Intereses Cuenta Corriente Mercantil');
				    $this->Ln();
				    $this->Ln();
				    $this->SetFont('arial','',12);
				    //file_get_contents($file);
				    // Times 12

				    // Imprimimos el texto justificado
				    //$this->MultiCell(0,5,$p_rut);
				    //$this->MultiCell(0,5,$p_fecha);
				    // Salto de línea
				    //$this->Ln();

				    $txt = "Por la presente, CMI Inversiones S.A. (RUT: 79.826.430-3) y $p_entidad (RUT: $p_rut) reconocen y aceptan la presente liquidacion de intereses devengados por concepto de saldo promedio en cuenta corriente mercantil, conforme al contrato firmado entre las partes con fecha $txt2.";
				    $this->MultiCell(0,8,$txt);
				    // Cita en itálica
				    $this->Ln();
				    $this->SetFont('arial','I',8);
						$txt = "Nota: Saldos positivos son a favor de CMI Inversiones S.A. (CMI es acreedor), mientras que saldos negativos son a favor de ".$p_entidad." (CMI es deudor)";
				    $this->MultiCell(0,4,$txt);
				    //$this->Cell(30,8,"Nota: Saldos positivos son a favor de CMI Inversiones S.A. (CMI es acreedor), saldos negativos son a favor de ".$p_entidad." (CMI es deudor)",0,0,'L'); $this->Ln();

				    $this->Ln();
						$this->sety(130);
						$this->setx(25);
						$this->SetFont('arial','',10);
						$this->SetFillColor(39, 174, 96);
						$this->SetTextColor(255,255,255);
						$this->Cell(50,8,"Detalle Interes Devengado",0,0,'L',true);
						$this->Cell(30,8,"[CLP]",0,0,'C',true);
						$this->Cell(30,8,"[UF]",0,0,'C',true);
						$this->Ln();
						$this->SetTextColor(0,0,0);
				    //$this->SetFont('arial','UI',12);
				    $this->Cell(45,8,"Intereses ".strftime("%B-%Y",$date_ultimo_dia_mes->getTimestamp()),0,0,'L');

				    //$this->Cell(30,8,"Fecha:",0,0,'L');
				    //$this->Cell(35,8,strftime("%d / %B / %Y",$date_ultimo_dia_mes->getTimestamp()),0,0,'R');
				    //$this->Ln();
				    //$this->Cell(30,8,"Monto: $",0,0,'L');
				    $this->Cell(30,8,number_format($p_interes_devengado),0,0,'R');
				    //$this->Ln();
				    //$this->Cell(30,8,"Monto: UF",0,0,'L');
				    $this->Cell(30,8,number_format($p_interes_devengado_uf,2),0,0,'R');

						$this->sety(150);
						$this->setx(25);

						$this->SetTextColor(255,255,255);
						$this->Cell(50,8,"Saldos de Cuenta",0,0,'L',true);
						$this->Cell(30,8,"Capital [CLP]",0,0,'R',true);
						$this->Cell(30,8,"Capital [UF]",0,0,'R',true);
						$this->Cell(30,8,"Interes [CLP]",0,0,'R',true);
						$this->Cell(25,8,"Interes [UF]",0,0,'R',true);
						$this->Ln();
						$this->SetTextColor(0,0,0);
						$this->Cell(50,8,"Saldo Inicial ".strftime("%B-%Y",$date_ultimo_dia_mes->getTimestamp()),0,0,'L');
						$this->Cell(30,8,number_format($c11),0,0,'R');
						$this->Cell(30,8,number_format($c12,2),0,0,'R');
						$this->Cell(30,8,number_format($c13),0,0,'R');
						$this->Cell(25,8,number_format($c14,2),0,0,'R');
						$this->Ln();
						$this->Cell(50,8,"Suma Transacciones:",0,0,'L');
						$this->Cell(30,8,number_format($c21),0,0,'R');
						$this->Cell(30,8,number_format($c22,2),0,0,'R');
						$this->Cell(30,8,number_format($c23),0,0,'R');
						$this->Cell(25,8,number_format($c24,2),0,0,'R');
						$this->Ln();
						$this->Cell(50,8,"Efecto UF Periodo:",0,0,'L');
						$this->Cell(30,8,number_format($c31),0,0,'R');
						$this->Ln();
						$this->Cell(50,8,"Saldo Final ".strftime("%B-%Y",$date_ultimo_dia_mes->getTimestamp()),0,0,'L');
						$this->Cell(30,8,number_format($c41),0,0,'R');
						$this->Cell(30,8,number_format($c42,2),0,0,'R');
						$this->Cell(30,8,number_format($c43),0,0,'R');
						$this->Cell(25,8,number_format($c44,2),0,0,'R');
						$this->Ln();
				    $this->Ln();
				    $this->Ln();
				    $this->Ln();
				    $this->Cell(80,8,"pp. CMI Inversiones S.A.",0,0,'L');
				    $this->Cell(60,8,"pp. ".$p_entidad,0,0,'L');
				    $this->Ln();
				    $this->Cell(80,8,"Nombre:",0,0,'L');
				    $this->Cell(60,8,"Nombre:",0,0,'L');
				    $this->Ln();
				    $this->Cell(80,8,"RUT:",0,0,'L');
				    $this->Cell(60,8,"RUT:",0,0,'L');
				    $this->Ln();
				    $this->Ln();
				    $this->SetLineWidth(0.1);
				    $this->Line(25,213, 85, 213);
				    $this->Line(105,213, 165, 213);



				}

			}


			$c11=$k_acum_clp_mespasado;
			$c12=$k_acum_uf_mespasado;
			$c13=$i_acum_clp_mespasado;
			$c14=$i_acum_uf_mespasado;
			$c21=$suma_transacciones_k_clp;
			$c22=$suma_transacciones_k_uf;
			$c23=$suma_transacciones_i_clp;
			$c24=$suma_transacciones_i_uf;
			$c31=$efecto_uf_mes;
			$c41=$k_acum_clp;
			$c42=$k_acum_uf;
			$c43=$i_acum_clp;
			$c44=$i_acum_uf;

				$pdf = new PDF('P','mm','Letter');
				$pdf->NotaDeCobro($ultimo_dia_mes,$entidad_nombre,$entidad_rut,$interes_devengado,$interes_devengado_uf,$cc_fecha_inicio,$c11,$c12,$c13,$c14,$c21,$c22,$c23,$c24,$c31,$c41,$c42,$c43,$c44);
				$filename = "CMI-$entidad_nombre - NdC - ".str_replace("-","",$ultimo_dia_mes).".pdf";
				//$email = 0;

				//$pdf->Output('F',$filename);
				// aca terminaba pero ahora sigo con el email

				$date_ultimo_dia_mes = DateTime::createFromFormat("Y-m-d",$ultimo_dia_mes);
				$periodo = strftime("%B / %Y",$date_ultimo_dia_mes->getTimestamp());
				// email stuff (change data below)
				$to = "alexander.celle@interpetrol.cl";
				//$to = $email_list;
				$from = "alexander.celle@interpetrol.cl";
				$subject = "Nota de Cobro CMI Inversiones S.A.-$entidad_nombre ($periodo)";
				$message = "Adjunto nota de cobro CMI Inversiones S.A.-$entidad_nombre para el periodo $periodo.";
				// attachment name
				//$filename = "nota_de_cobro.pdf";
				// carriage return type (we use a PHP end of line constant)
				$eol = PHP_EOL;


				$filename = "CMI-$entidad_nombre - NdC - ".str_replace("-","",$ultimo_dia_mes).".pdf";
				$pdfdoc = $pdf->Output($filename,'S');
				// create a new instance called $mail and use its properties and methods.
				        $mail = new PHPMailer();
				        $mail->From = $from;
				        $mail->FromName = "Alexander Celle T.";
						
						foreach ($row5 as $row):
							$mail->AddAddress($row['co_email'],$row['co_nombre']);
						endforeach;
				        //$mail->AddAddress($to);
				        $mail->AddReplyTo($from, "Alexander Celle T.");
						$mail->addStringAttachment($pdfdoc, $filename);
				        $mail->AddAttachment($pdfdoc);
				        $mail->Subject = $subject;
				        $mail->Body = $message;

				        $mail->Send();


?>
