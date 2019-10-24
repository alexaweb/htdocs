<?php
	$current=22;
	$page_category = "cuentas corrientes";
	$page_name = "interés";
	require_once('../../includes/cmi_common.php');





    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	if(empty($_GET['cc_id']))
    {
            //por defecto seleccionamos la fecha de ayer
			$fecha = date("Y-m-d", strtotime( '-1 days' ) );
			$cc_id=6;
    } else
	{
		$cc_id = $_GET['cc_id'];
	}

	if(empty($_GET['ano']) or empty($_GET['mes']))
    {
            //por defecto seleccionamos mes pasado
			$ano = (int) date("Y", strtotime( '-1 month' ) );
			$mes = (int) date("m", strtotime( '-1 month' ) );
	} else
	{
		$ano = $_GET['ano'];
		$mes = $_GET['mes'];
	}


        try
        {
            $sql = "call proc_display_interes_devengado_ano_mes( $ano, $mes);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
			//$interes_devengado = $row['interes'];
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

  /*  $sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc order by entidad_codigo_b asc";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $data_cc = $stmt->fetchAll();
*/
  $nombremes = date('F', mktime(0, 0, 0, $mes, 10)); //

?>





<h4>Interes devengado durante <?php echo $nombremes." - ".$ano;?></h4>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">Cuenta</div>
		<div class="cell">Interes (UF)</div>
		<div class="cell">Interes (CLP)</div>
		<div class="cell">..</div>
		<div class="cell">..</div>
	</div>
		<?php foreach ($data as $row): ?>
		<div class="row">
		<div class="cell"><?=$row['entidad_codigo_b']?></div>
		<div class="numbercell"><?=number_format($row['interes_uf'],2)?></div>
		<div class="numbercell"><?="$ ".number_format($row['interes'])?></div>
		<div class="numbercell"><a href="display_detalle_cc.php?tr_cc_id=<?=$row['cc_id']?>&fecha_orden=1" class="button">detalle</a></div>
		<div class="numbercell"><a href="display_interes_devengado_cc_detalle.php?cc_id=<?=$row['cc_id']?>&ano=<?=$ano?>&mes=<?=$mes?>" class="button">reporte</a></div>
		</div>
		<?php endforeach ?>
	</div>
</div>
<br><br>
<form action="display_interes_devengado.php" method="get">
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

  <button class="button-submit" type="submit" value="Submit">Submit</button>
</form>


<?php require_once($path_include."/cmifooter.php");
