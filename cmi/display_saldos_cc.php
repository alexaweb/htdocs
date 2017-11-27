<?php
	$current=21;
	require_once('../../includes/cmi_common.php');







    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed

  if(empty($_GET['fecha']))
  {
            //por defecto seleccionamos la fecha de ayer
		$fecha = date("Y-m-d", strtotime( '-1 days' ) );
  } else
	{
		$fecha = $_GET['fecha'];
	}

	if(empty($_GET['cc_id']))
	{
					//por defecto seleccionamos la fecha de ayer
		$cc_id = 4;
	} else
	{
		$cc_id = $_GET['cc_id'];
	}
	$ano = substr($fecha, 0, 4);
	$fecha_temp =strtotime($fecha);
	//$fecha_ano_pasado = strtotime('-1 year',$fecha_temp);
	$ano_pasado = $ano - 1;//substr($fecha_ano_pasado,0,4);
	$fecha_ano_pasado=$ano_pasado."-12-31";//  date('Y-m-d',$fecha_ano_pasado);

	//Obtengo el valor de la UF para desplegar antes de hacer cualquier cosa
$sql_entidad = "select entidad_codigo_b, cc_spread,cc_fecha_inicio from cc where cc_id=$cc_id";
$stmt_entidad = $db->prepare($sql_entidad);
$result_entidad = $stmt_entidad->execute();
$row_entidad = $stmt_entidad->fetch();
$entidad_codigo_b = $row_entidad['entidad_codigo_b'];

 		//Obtengo montos de transacciones
		$sqlA1 = "select sum(tr_monto) as monto, count(*) as cantidad from transacciones where tr_cc_id = $cc_id and tr_monto > 0 and year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 0 and tr_fecha_valor <= '{$fecha}';";
		$stmtA1 = $db->prepare($sqlA1);
		$result = $stmtA1->execute();
		$rowA1 = $stmtA1->fetch();
		$A1_clp = $rowA1['monto'];
		$A1_count = $rowA1['cantidad'];

		$sqlA2 = "select sum(tr_monto) as monto, count(*) as cantidad from transacciones where tr_cc_id = $cc_id and tr_monto < 0 and year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 0 and tr_fecha_valor <= '{$fecha}';";
		$stmtA2 = $db->prepare($sqlA2);
		$result = $stmtA2->execute();
		$rowA2 = $stmtA2->fetch();
		$A2_clp = $rowA2['monto'];
		$A2_count = $rowA2['cantidad'];

		$sqlA3 = "select sum(tr_monto) as monto, count(*) as cantidad from transacciones where tr_cc_id = $cc_id and year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 0 and tr_fecha_valor <= '{$fecha}';";
		$stmtA3 = $db->prepare($sqlA3);
		$result = $stmtA3->execute();
		$rowA3 = $stmtA3->fetch();
		$A3_clp = $rowA3['monto'];
		$A3_count = $rowA3['cantidad'];


		$sqlB1 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = $cc_id and tr_monto > 0 and  year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 2 and tr_fecha_valor <= '{$fecha}';";
		$stmtB1 = $db->prepare($sqlB1);
		$result = $stmtB1->execute();
		$rowB1 = $stmtB1->fetch();
		$B1_clp = $rowB1['monto'];
		$B1_uf = $rowB1['monto_uf'];
		$sqlB2 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = $cc_id and tr_monto < 0 and  year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 2 and tr_fecha_valor <= '{$fecha}';";
		$stmtB2 = $db->prepare($sqlB2);
		$result = $stmtB2->execute();
		$rowB2 = $stmtB2->fetch();
		$B2_clp = $rowB2['monto'];
		$B2_uf = $rowB2['monto_uf'];
		$sqlB3 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf from transacciones where tr_cc_id = $cc_id and  year(tr_fecha_valor) = $ano and tr_tipo_transaccion = 2 and tr_fecha_valor <= '{$fecha}';";
		$stmtB3 = $db->prepare($sqlB3);
		$result = $stmtB3->execute();
		$rowB3 = $stmtB3->fetch();
		$B3_clp = $rowB3['monto'];
		$B3_uf = $rowB3['monto_uf'];

		$sqlB4 = "select sum(`ccs_interes_devengado_uf`) as monto_uf from cc_saldos where cc_id = $cc_id and year(ccs_fecha) = $ano and ccs_fecha <= '{$fecha}';";
		$stmtB4 = $db->prepare($sqlB4);
		$result = $stmtB4->execute();
		$rowB4 = $stmtB4->fetch();
		$B4_uf = $rowB4['monto_uf'];

      try
        {
            $sql = "call proc_display_saldos_cc('{$fecha}','{$cc_id}');";
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
						$sql2 = "call proc_display_saldos_cc('{$fecha_ano_pasado}','{$cc_id}');";
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
				$k_acum_clp_anopasado=$row2['capital_clp'];
				$k_acum_uf_anopasado=$row2['capital'];
				$i_acum_clp_anopasado=$row2['interes_clp'];
				$i_acum_uf_anopasado=$row2['interes'];


				$sqlA3_pasado = "select sum(tr_monto) as monto from transacciones where tr_cc_id = $cc_id and  year(tr_fecha_valor) <= $ano_pasado and tr_tipo_transaccion = 0;";
				$stmtA3_pasado = $db->prepare($sqlA3_pasado);
				$result_pasado = $stmtA3_pasado->execute();
				$rowA3_pasado = $stmtA3_pasado->fetch();
				$A3_clp_pasado = $rowA3_pasado['monto'];
				$A3_count_pasado = $rowA3_pasado['cantidad'];

				$sqlB3_pasado = "select sum(tr_monto) as monto from transacciones where tr_cc_id = $cc_id and  year(tr_fecha_valor) <= $ano_pasado and tr_tipo_transaccion = 2;";
				$stmtB3_pasado = $db->prepare($sqlB3_pasado);
				$result_pasado = $stmtB3_pasado->execute();
				$rowB3_pasado = $stmtB3_pasado->fetch();
				$B3_clp_pasado = $rowB3_pasado['monto'];
				$B3_uf_pasado = $rowB3_pasado['monto_uf'];


$saldo_capital_nominal_periodo_clp = $A3_clp - $B3_clp;
$saldo_capital_nominal_periodo_clp_ano_pasado = $A3_clp_pasado - $B3_clp_pasado;
$saldo_capital_nominal_acumulado_clp = $saldo_capital_nominal_periodo_clp_ano_pasado + $saldo_capital_nominal_periodo_clp;


$saldo_capital_periodo = $k_acum_clp-$k_acum_clp_anopasado;
$efecto_uf_periodo = $saldo_capital_periodo - $saldo_capital_nominal_periodo_clp;
$efecto_uf_acum = $k_acum_clp - $saldo_capital_nominal_acumulado_clp;
?>




<h2><?php echo $entidad_codigo_b;?>: Saldos al <?php echo $fecha;?></h2>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">TRANSACCIONES (AÑO EN CURSO)</div>
		<div class="cell">CLP</div>
		<div class="cell">#</div>
	</div>

    <?php?>
		<div class="row">
		<div class="textcell">(+) Cargos / Giros de CMI</div>
		<div class="numbercell">$ <?=number_format($A1_clp);?></div>
		<div class="numbercell"><?=number_format($A1_count);?></div>
		</div>
		<div class="row">
		<div class="cell">(-) Abonos a CMI</div>
		<div class="numbercell">$ <?=number_format($A2_clp);?></div>
		<div class="numbercell"><?=number_format($A2_count);?></div>
		</div>
		<div class="row">
		<div class="cell">Total</div>
		<div class="numbercell">$ <?=number_format($A3_clp);?></div>
		<div class="numbercell"><?=number_format($A3_count);?></div>
		</div>
		<?php?>
	</div>
	</div>

<br><br>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">INTERES (AÑO EN CURSO)</div>
		<div class="cell">CLP</div>
		<div class="cell">UF</div>
		<div class="cell">UF Devengado</div>
	</div>

    <?php?>
		<div class="row">
		<div class="textcell">(+) Interes</div>
		<div class="numbercell">$ <?=number_format($B1_clp);?></div>
		<div class="numbercell"><?=number_format($B1_uf,2);?></div>
		<div class="numbercell"></div>
		</div>
		<div class="row">
		<div class="cell">(-) Interes</div>
		<div class="numbercell">$ <?=number_format($B2_clp);?></div>
		<div class="numbercell"><?=number_format($B2_uf,2);?></div>
		<div class="numbercell"></div>
		</div>
		<div class="row">
		<div class="cell">Total</div>
		<div class="numbercell">$ <?=number_format($B3_clp);?></div>
		<div class="numbercell"><?=number_format($B3_uf,2);?></div>
		<div class="numbercell"><?=number_format($B4_uf,2);?></div>
		</div>
		<?php?>
	</div>
</div>
<br><br>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">CAPITAL</div>
		<div class="cell">CLP</div>
		<div class="cell"></div>
	</div>




    <?php?>
		<div class="row">
		<div class="textcell">Saldo Capital Nominal (año en curso)</div>
		<div class="numbercell">$ <?=number_format($saldo_capital_nominal_periodo_clp);?></div>
		<div class="numbercell"></div>
		</div>
		<div class="row">
		<div class="cell">Saldo Capital Nominal Acumulado</div>
		<div class="numbercell">$ <?=number_format($saldo_capital_nominal_acumulado_clp);?></div>
		<div class="numbercell"></div>
		</div>
		<?php?>
	</div>
</div>
<br><br>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">EFECTO UF</div>
		<div class="cell">CLP</div>
		<div class="cell"></div>
	</div>

    <?php?>
		<div class="row">
		<div class="textcell">Efecto UF (año en curso)</div>
		<div class="numbercell">$ <?=number_format($efecto_uf_periodo);?></div>
		<div class="numbercell"></div>
		</div>
		<div class="row">
		<div class="cell">Efecto UF Acumulado</div>
		<div class="numbercell">$ <?=number_format($efecto_uf_acum);?></div>
		<div class="numbercell"></div>
		</div>
		<?php?>
	</div>
</div>
<br><br>


<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">SALDO FINAL</div>
		<div class="cell">CLP</div>
		<div class="cell">UF</div>
	</div>


			<div class="row">
			<div class="textcell">Saldo Capital (año en curso)</div>
			<div class="numbercell">$ <?=number_format($saldo_capital_periodo);?></div>
			<div class="numbercell"><?=number_format($k_acum_uf- $k_acum_uf_anopasado,2);?></div>
			</div>
		<div class="row">
		<div class="cell">Saldo Capital Acumulado</div>
		<div class="numbercell">$ <?=number_format($k_acum_clp);?></div>
		<div class="numbercell"><?=number_format($k_acum_uf,2);?></div>
		</div>
		<div class="row">
		<div class="cell">Interés x Pagar Período</div>
		<div class="numbercell"></div>
		<div class="numbercell"><?=number_format($B4_uf + $B3_uf,2);?></div>
		</div>
		<div class="row">
		<div class="cell">Interés x Pagar Acumulado</div>
		<div class="numbercell">$ <?=number_format($i_acum_clp);?></div>
		<div class="numbercell"><?=number_format($i_acum_uf,2);?></div>
		</div>

	</div>
</div>
<br><br>
<form action="display_saldos_cc.php" method="get">
  Fecha: <input type="text" name="fecha" value="<?php echo $fecha?>"><br>
	Cuenta Corriente: <input type="text" name="cc_id" value="<?php echo $cc_id?>"><br>
    <button class="button" type="reset" value="Reset">Reset</button>
  <button class="button submit" type="submit" value="Submit">Submit</button>
</form>

<?php require_once($path_include."/cmifooter.php");
