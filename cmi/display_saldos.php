<?php

	$current=21;
	$page_category = "cuentas corrientes";
	$page_name = "saldos";
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
            $sql = "call proc_display_saldos('{$fecha}');";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data_tr = $stmt->fetchAll();
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
		<div class="cell">Spread (CLP)</div>
		<div class="cell">Capital (UF)</div>
		<div class="cell">Interés (UF)</div>
		<div class="cell">Capital (CLP)</div>
		<div class="cell">Interés (CLP)</div>
	</div>

    <?php foreach ($data_tr as $row): ?>
		<div class="row">
		<div class="cell"><a href=display_saldos_cc.php?fecha=<?=$fecha;?>&cc_id=<?=$row['cc_id'];?>><?=$row['nombre']?></a></div>
		<div class="numbercell"><?=number_format($row['spread'],2)?> %</div>
		<div class="numbercell"><?=number_format($row['capital'],2)?></div>
		<div class="numbercell"><?=number_format($row['interes'],2);?></div>
		<div class="numbercell">$ <?=number_format($row['capital_clp'])?></div>
		<div class="numbercell">$ <?=number_format($row['interes_clp']);?></div>
		</div>
    <?php endforeach ?>
	</div>
</div>
<br><br>
<form action="display_saldos.php" method="get">
  Fecha: <input type="text" name="fecha" value="<?php echo $fecha?>"><br>
    <button class="button-reset" type="reset" value="Reset">Reset</button>
  <button class="button-submit" type="submit" value="Submit">Submit</button>
</form>

<?php require_once($path_include."/cmifooter.php");
