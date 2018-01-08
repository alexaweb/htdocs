<?php
	$current=22;
	//require_once('../../includes/cmi_common.php');
	$dbfile = "cmiDB.php";
	//$menufile = "cmimenu.php";
	//$path = "/var/www/html/cmi";
	require_once('../../includes/common.php');
	//require_once('/var/www/includes/cmiheader.php');
	setlocale(LC_TIME, 'es_ES.UTF-8');
  date_default_timezone_set('America/Santiago');


    require_once('../../includes/fpdf/fpdf.php');
    require_once('../../includes/phpmailer/PHPMailerAutoload.php');
    $ano = $_GET['ano'];
		$mes = $_GET['mes'];
		$cc_id = $_GET['cc_id'];
    $interes_devengado = $_GET['interes_devengado'];
		$interes_devengado_uf = $_GET['interes_devengado_uf'];
    $entidad = $_GET['entidad'];
    $rut = $_GET['rut'];
    $ultimo_dia_mes = $_GET['ultimo_dia_mes'];
    $fecha_inicio = $_GET['fecha_inicio'];
		$email = $_GET['email'];

		$c11 = $_GET['c11'];
		$c12 = $_GET['c12'];
		$c13 = $_GET['c13'];
		$c14 = $_GET['c14'];
		$c21 = $_GET['c21'];
		$c22 = $_GET['c22'];
		$c23 = $_GET['c23'];
		$c24 = $_GET['c24'];
		$c31 = $_GET['c31'];
		$c41 = $_GET['c41'];
		$c42 = $_GET['c42'];
		$c43 = $_GET['c43'];
		$c44 = $_GET['c44'];

		//echo $c44;
		//die;





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

$pdf = new PDF('P','mm','Letter');
$pdf->NotaDeCobro($ultimo_dia_mes,$entidad,$rut,$interes_devengado,$interes_devengado_uf,$fecha_inicio,$c11,$c12,$c13,$c14,$c21,$c22,$c23,$c24,$c31,$c41,$c42,$c43,$c44);
$filename = "CMI-$entidad - NdC - ".str_replace("-","",$ultimo_dia_mes).".pdf";
//$email = 0;

if (!$email){
	$pdf->Output('D',$filename);
}
else {


// cosas que faltan:
// colocar seguridad a este archivo
// leer codigo y otra info directo de la BD



$date_ultimo_dia_mes = DateTime::createFromFormat("Y-m-d",$ultimo_dia_mes);
$periodo = strftime("%B / %Y",$date_ultimo_dia_mes->getTimestamp());
// email stuff (change data below)
$to = "alexander.celle@interpetrol.cl";
$from = "alexander.celle@interpetrol.cl";
$subject = "Nota de Cobro CMI Inversiones S.A.-$entidad ($periodo)";
$message = "Adjunto nota de cobro CMI Inversiones S.A.-$entidad para el periodo $periodo.";
// attachment name
//$filename = "nota_de_cobro.pdf";
// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

$pdfdoc = $pdf->Output('','D');
$filename = "CMI-$codigo - NdC - ".str_replace("-","",$ultimo_dia_mes).".pdf";
// create a new instance called $mail and use its properties and methods.
        $mail = new PHPMailer();
        $mail->From = $from;
        $mail->FromName = "Alexander Celle T.";
        $mail->AddAddress($to);
        $mail->AddReplyTo($from, "name");
		$mail->addStringAttachment($pdfdoc, $filename);
        $mail->AddAttachment($pdfdoc);
        $mail->Subject = $subject;
        $mail->Body = $message;

        $mail->Send();
		header("Location:display_interes_devengado_cc.php?cc_id=$cc_id");
		die("Redirecting to display_interes_devengado_cc.php");

}
?>
