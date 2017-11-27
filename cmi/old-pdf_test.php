<?php
//require('fpdf.php');
	setlocale(LC_TIME, 'es_ES.UTF-8');
    date_default_timezone_set('America/Santiago');
// First we execute our common code to connection to the database and start the session
    //$path = $_SERVER['DOCUMENT_ROOT'];
    require_once('../includes/fpdf/fpdf.php');
    require_once('../includes/phpmailer/PHPMailerAutoload.php');
    $ano = $_GET['ano'];
	$mes = $_GET['mes'];
	$cc_id = $_GET['cc_id'];
    $interes_devengado = $_GET['interes_devengado'];
	$interes_devengado_uf = $_GET['interes_devengado_uf'];
    $entidad = $_GET['entidad'];
	$cc_id = $_GET['cc_id'];
    $rut = $_GET['rut'];
    $ultimo_dia_mes = $_GET['ultimo_dia_mes'];
    $fecha_inicio = $_GET['fecha_inicio'];
    
/*class PDF extends FPDF
{
// Page header
function Header()
{
    // Logo
    //$this->Image('logo.png',10,6,30);
    // Arial bold 15
    $this->SetFont('Arial','B',15);
    // Move to the right
    //$this->Cell(50);
    // Title
    $this->Cell(0,10,'CMI Inversiones S.A.',1,0,'C');
    // Line break
    $this->Ln(20);
}
// Page footer
function Footer()
{
    // Position at 1.5 cm from bottom
    $this->SetY(-15);
    // Arial italic 8
    $this->SetFont('Arial','I',8);
    // Page number
    $this->Cell(0,10,utf8_decode('página').$this->PageNo().'/{nb}',0,0,'R');
}
}*/

//$pdf->Cell(40,10,'Hello World!');
//$pdf->Output();
/*
$pdf = new PDF();
$pdf->AliasNbPages();
$pdf->AddPage();
$pdf->SetFont('Arial','',12);
$pdf->MultiCell(60,5,'$interes_devengado');
    //$pdf->Ln();
    $pdf->Cell(0,6,"Capítulo $rut : $label",0,1,'L',true);
    $pdf->MultiCell(60,5,'asda');
//$pdf->Cell(0,10,$interes_devengado,'',0,0,'l');
for($i=1;$i<=4;$i++)
    $pdf->Cell(0,10,'Printing line number '.$i,0,1);
//$pdf->Output();*/




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

function NotaDeCobro($p_fecha,$p_entidad,$p_rut,$p_interes_devengado,$p_interes_devengado_uf,$p_fecha_inicio)
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
    $fecha1 = strftime("%A %d de %B de %Y",$date_ultimo_dia_mes->getTimestamp());
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
    $this->Ln();
    
    $txt = "Por la presente, CMI Inversiones S.A. (RUT: 79.826.430-3) y $p_entidad (RUT: $p_rut) reconocen y aceptan la presente liquidacion de intereses devengados por concepto de saldo promedio en cuenta corriente mercantil, conforme al contrato firmado entre las partes con fecha $txt2";
    $this->MultiCell(0,8,$txt);
    // Cita en itálica
    $this->Ln();
    $this->Ln();
    $this->SetFont('arial','UI',12);
    $this->Cell(30,8,"Nota:",0,0,'L'); $this->Ln();
    $this->SetFont('arial','',12);
    $this->Cell(30,8,"Saldos positivos son a favor de CMI Inversiones S.A. (CMI es acreedor)",0,0,'L'); $this->Ln();
    $this->Cell(30,8,"Saldos negativos son a favor de ".$p_entidad." (CMI es deudor)",0,0,'L'); $this->Ln();
    $this->Ln();
    $this->Ln();
    $this->SetFont('arial','UI',12);
    $this->Cell(30,8,"Detalle:",0,0,'L'); $this->Ln();
    $this->SetFont('arial','',12);
    $this->SetFont('arial','',12);
    $this->Cell(30,8,"Fecha:",0,0,'L');
    $this->Cell(35,8,strftime("%d / %B / %Y",$date_ultimo_dia_mes->getTimestamp()),0,0,'R');
    $this->Ln();
    $this->Cell(30,8,"Monto: $",0,0,'L');
    $this->Cell(35,8,number_format($p_interes_devengado),0,0,'R');
    $this->Ln();
    $this->Cell(30,8,"Monto: UF",0,0,'L');
    $this->Cell(35,8,number_format($p_interes_devengado_uf,2),0,0,'R');
    $this->Ln();
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
    $this->Line(25,233, 85, 233);
    $this->Line(105,233, 165, 233);
    
		
    
}

function PrintChapter($num, $title, $file)
{
    $this->AddPage();
    $this->ChapterTitle($num,$title);
    $this->ChapterBody($file);
}
}

$pdf = new PDF('P','mm','Letter');
//$title = 'Nota de Cobro - Cuenta Corriente Mercantil';
//$pdf->SetTitle($title);
//$pdf->SetAuthor('Julio Verne');
//$pdf->PrintChapter(1,'UN RIZO DE HUIDA','20k_c1.txt');
//$pdf->PrintChapter(2,'LOS PROS Y LOS CONTRAS','20k_c2.txt');
$pdf->NotaDeCobro($ultimo_dia_mes,$entidad,$rut,$interes_devengado,$interes_devengado_uf,$fecha_inicio);

//$pdf->Output();

// email stuff (change data below)
$to = "alexander.celle@interpetrol.cl";
$from = "alexander.celle@interpetrol.cl"; 
$subject = "send email with pdf attachment"; 
$message = "<p>Please see the attachment.</p>";
// attachment name
$filename = "nota_de_cobro.pdf";
// carriage return type (we use a PHP end of line constant)
$eol = PHP_EOL;

// encode data (puts attachment in proper format)
$pdf_filename = 'attachment.pdf';
$pdfdoc = $pdf->Output('','S');
//'attachment.pdf', "S");
  //      if(!file_exists($pdf_filename) || is_writable($pdf_filename)){
    //        $pdf->Output($pdf_filename, "F");
     //   } else { 
      //      exit("Path Not Writable");
       // }
//$attachment = chunk_split(base64_encode($pdfdoc));
//$mailer->AddStringAttachment($attachment, 'attachment.pdf');

// create a new instance called $mail and use its properties and methods.
        $mail = new PHPMailer();
        $staffEmail = "alexander.celle@interpetrol.cl";
        $mail->From = $staffEmail;
        $mail->FromName = "Alexander Celle T.";
        $mail->AddAddress("alexander.celle@interpetrol.cl");
        $mail->AddReplyTo($staffEmail, "name");
$mail->addStringAttachment($pdfdoc, 'my-doc.pdf');
        $mail->AddAttachment($pdfdoc);
        $mail->Subject = "PDF file attachment";

        $mail->Body = "message!";

        $mail->Send();
?>