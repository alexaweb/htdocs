<?php

	$current=23;
	$page_category = "cuentas corrientes";
	$page_name = "saldos contabilidad";
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

        try
        {
            $sql1 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf, tr_cc_id as cc_id, entidad_codigo_b from transacciones join cc on tr_cc_id = cc.cc_id where tr_tipo_transaccion = 0 and tr_monto >0 and tr_fecha_abono<='{$fecha}' group by tr_cc_id, entidad_codigo_b;";
			//union
			//select sum(tr_monto) as monto2, sum(tr_monto_uf) as monto_uf2, tr_cc_id as cc_id2 from transacciones where tr_tipo_transaccion = 0 and tr_monto <0 group by tr_cc_id;";
			//echo $sql;
            $stmt1 = $db->prepare($sql1);
            $result1 = $stmt1->execute();
            $data1 = $stmt1->fetchAll();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }

		try
        {
            $sql2 = "select sum(tr_monto) as monto, sum(tr_monto_uf) as monto_uf, tr_cc_id as cc_id from transacciones where tr_tipo_transaccion = 0 and tr_monto <0 and tr_fecha_abono<='{$fecha}' group by tr_cc_id;";
			//echo $sql;
            $stmt2 = $db->prepare($sql2);
            $result2 = $stmt2->execute();
            $data2 = $stmt2->fetchAll();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
?>





<h4>Saldos al <?php echo $fecha;?></h4>

<div class="wrapper">
	<div class="table">
	<div class="rowheader">
		<div class="cell">Cuenta</div>
		<div class="rightcell">Abonos de CMI (CLP)</div>
		<!--<div class="cell">Abonos de CMI (UF)</div>-->
		<div class="rightcell">Pagos a CMI (CLP)</div>
		<!--<div class="cell">Pagos a CMI (UF)</div>-->
	</div>

    <?php foreach ($data1 as $row): ?>

		<div class="row">
		<div class="cell"><?=$row['entidad_codigo_b']?></div>
		<div class="numbercell">$ <?=number_format($row['monto'])?></div>
		<!--<div class="numbercell"><?=number_format($row['monto_uf'])?></div>-->
		<?php foreach ($data2 as $row2)
		{
			if ($row2['cc_id'] == $row['cc_id'])
			{?>
				<!--<div class="numbercell"><?=number_format($row2['cc_id']);?></div>-->
				<div class="numbercell">$ <?=number_format($row2['monto'])?></div>
				<!--<div class="numbercell"><?=number_format($row2['monto_uf']);?></div>-->
			<?php
			} else
			{

			}
		}
			?>

		</div>
    <?php endforeach ?>
	</div>
</div>
<br><br>
<form action="display_contabilidad.php" method="get">
  Fecha: <input type="text" name="fecha" value="<?php echo $fecha?>"><br>
    <button class="button-reset" type="reset" value="Reset">Reset</button>
  <button class="button-submit" type="submit" value="Submit">Submit</button>
</form>

<?php require_once($path_include."/cmifooter.php");
