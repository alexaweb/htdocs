<?php
	$current=22;
	require_once('../../includes/cmi_common.php');
  	setlocale(LC_TIME, 'es_ES.UTF-8');
    date_default_timezone_set('America/Santiago');


    
    
   
    

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$ano = $_GET['ano'];
	$mes = $_GET['mes'];
	$cc_id = $_GET['cc_id'];
    if(empty($_GET['cc_id']))
    {
            //por defecto seleccionamos la fecha de ayer
			$fecha = date("Y-m-d", strtotime( '-1 days' ) );
			$cc_id=6;
    }
	 if(empty($_GET['ano']) or empty($_GET['mes']))
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

?>

				
<h2>Nota de Cobro Cuenta Corriente Mercantil</h2>
<h3>CMI Inversiones S.A. - <?=$entidad_nombre?></h3>

Santiago, <?php echo strftime("%A %d de %B de %Y",$date_ultimo_dia_mes->getTimestamp());?><br><br>
				
ref: Liquidación de Intereses Cuenta Corriente Mercantil<br><br>

Por la presente, CMI Inversiones S.A. (RUT: 79.826.430-3) y <?=$entidad_nombre;?> (RUT: <?=$entidad_rut;?>)
reconocen y aceptan la presente
liquidación de intereses devengados por concepto de saldo promedio en cuenta corriente
mercantil, conforme al contrato firmado entre las partes con fecha <?php echo strftime("%A %d de %B de %Y",$date_fecha_inicio->getTimestamp());?>

<br><br>
<h7>Nota:</h7><br>
Saldos positivos son a favor de CMI Inversiones S.A.<br>
Saldos negativos son a favor de <?=$entidad_nombre?><br><br><br>
<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">Período</div>
		<div class="cell">Interes (UF)</div>
		<div class="cell">Interés (CLP)</div>
		<div class="cell">...</div>
	</div>
		<div class="row">
		<div class="numbercell"><?php echo strftime("%B de %Y",$date_ultimo_dia_mes->getTimestamp());?></div>
		<div class="numbercell"><?=number_format($interes_devengado_uf,2);?></div>
		<div class="numbercell">$ <?=number_format($interes_devengado);?></div>
		<div class="numbercell"><a href="display_pdf.php?cc_id=<?=$cc_id?>&ano=<?=$ano?>&mes=<?=$mes?>&entidad=<?=$entidad_nombre;?>&rut=<?=$entidad_rut;?>&ultimo_dia_mes=<?=$ultimo_dia_mes;?>&interes_devengado_uf=<?=$interes_devengado_uf;?>&interes_devengado=<?=$interes_devengado;?>&fecha_inicio=<?=$cc_fecha_inicio;?>&email=1">
		<img src="images/mail24x24.png"  alt="Generar reporte PDF"></a><a href="display_pdf.php?cc_id=<?=$cc_id?>&ano=<?=$ano?>&mes=<?=$mes?>&entidad=<?=$entidad_nombre;?>&rut=<?=$entidad_rut;?>&ultimo_dia_mes=<?=$ultimo_dia_mes;?>&interes_devengado_uf=<?=$interes_devengado_uf;?>&interes_devengado=<?=$interes_devengado;?>&fecha_inicio=<?=$cc_fecha_inicio;?>&email=0">
		<img src="images/pdf24x24.png"  alt="Generar reporte PDF"></a></div>
		</div>
	</div>
</div>
				
<form action="display_interes_devengado_cc.php" method="get">
      Mes:
    <select name="mes" id="mes">
            <option value="1" <?php if($mes==1){ print ' selected'; }?>>1</option>
            <option value="2" <?php if($mes==2){ print ' selected'; }?>>2</option>
            <option value="3" <?php if($mes==3){ print ' selected'; }?>>3</option>
            <option value="4" <?php if($mes==4){ print ' selected'; }?>>4</option>
            <option value="5" <?php if($mes==5){ print ' selected'; }?>>5</option>
            <option value="6" <?php if($mes==6){ print ' selected'; }?>>6</option>
            <option value="7" <?php if($mes==7){ print ' selected'; }?>>7</option>
            <option value="8" <?php if($mes==8){ print ' selected'; }?>>8</option>
            <option value="9" <?php if($mes==9){ print ' selected'; }?>>9</option>
            <option value="10" <?php if($mes==10){ print ' selected'; }?>>10</option>
            <option value="11" <?php if($mes==11){ print ' selected'; }?>>11</option>
            <option value="12" <?php if($mes==12){ print ' selected'; }?>>12</option>
    </select><br />
	   Año:
    <select name="ano" id="ano">
            <option value="2013" <?php if($ano==2013){ print ' selected'; }?>>2013</option>
            <option value="2014" <?php if($ano==2014){ print ' selected'; }?>>2014</option>
            <option value="2015" <?php if($ano==2015){ print ' selected'; }?>>2015</option>
            <option value="2016" <?php if($ano==2016){ print ' selected'; }?>>2016</option>
            <option value="2017" <?php if($ano==2017){ print ' selected'; }?>>2017</option>
            <option value="2018" <?php if($ano==2018){ print ' selected'; }?>>2018</option>
            <option value="2019" <?php if($ano==2019){ print ' selected'; }?>>2019</option>
    </select><br />
      Cuenta Corriente: <?=$entidad_codigo;?>
		 <input type="hidden" name="cc_id" value="<?=$cc_id;?>"> 
	<br />
    
  <button class="button submit" type="submit" value="Submit">Submit</button>
</form> 

<?php require_once($path_include."/cmifooter.php");

