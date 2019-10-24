<?php
	$current=332;
	$page_category = "arriendos";
	$page_name = "ingresar pago";
	require_once('../../includes/arriendos_common.php');


    $fecha_valor = date("Y-m-d");
    $fecha = date("Y-m-d");
	//$co_id = $_GET('co_id');
	$co_id = $_GET['co_id'];
  //por defecto seleccionamos mes pasado

		$ano =  date("Y", strtotime( '-1 month' ) );
		$mes =  date("m", strtotime( '-1 month' ) );
    $sql = "select c.co_id, a.ar_id, a.ar_nombre, p.pr_codigo, c.co_tipo from contrato c join arrendatario a on c.ar_id = a.ar_id join propiedad p on c.pr_id = p.pr_id order by p.pr_codigo asc;";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $data = $stmt->fetchAll();

?>



<h2>Ingresar pago</h2>

<form action="insert_pago_check.php" method="post">
	<div class="wrapper">
	<div class="table">
	<div class="row">
		<div class="cell formtext">
			Contrato:</div>
		<div class="cell">
			<select name="co_id">
			<option selected disabled>Seleccionar</option>
			<?php foreach ($data as $row): ?>
            <option value="<?=$row['co_id']?>" <?=($co_id==$row['co_id']?"selected":"")?>><?=$row['pr_codigo']." - ".$row['ar_nombre'].($row['co_tipo']=='1'?" - EE":"")?></option>
			<?php endforeach ?>
			</select>
		</div>
	</div>
	<div class="row">
		<div class="cell formtext">Fecha:</div><div class="cell"><input type="text" name="fecha" value="<?php echo $fecha?>" /></div>
	</div>
	<div class="row">
		<div class="cell formtext">Monto: </div><div class="cell"><input type="text" name="monto" value="" /></div>
	</div>
	<div class="row">
			<div class="cell formtext">Tipo de pago: </div><div class="cell">
			<select name="tipo">
			<option selected disabled>Seleccionar</option>
			<option value="0">arriendo</option>
			<option value="1">electricidad</option>
			</select></div>
	</div>
	<div class="row">
		<div class="cell formtext">Periodo: </div><div class="cell"><input type="text" name="periodo" value="<?=$ano.$mes;?>" /></div>
	</div>
	<div class="row">
		<div class="cell formtext">Notas: </div><div class="cell"><input type="text" name="notas" value="" /></div>
	</div>
	<div class="row"><div class="cell formtext">
	</div><div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
	<!--<input type="hidden" name="co_id" value="<? //echo $co_id;?>">-->
	</form>
<?php require_once($path_include."/cmifooter.php");
