<?php
	$current=32;
	$page_category = "cuentas corrientes";
	$page_name = "pago a cmi";
	require_once('../../includes/cmi_common.php');;


    $fecha_valor = date("Y-m-d");
    $fecha_abono = date("Y-m-d");



    $sql = "select cc_id, entidad_codigo_a, entidad_codigo_b from cc order by entidad_codigo_b asc";
    $stmt = $db->prepare($sql);
    $result = $stmt->execute();
    $data_cc = $stmt->fetchAll();

?>


<h4>Ingresar pago A cmi</h4>

<form action="insert_pago_check.php" method="post">
	<div class="wrapper">
	<div class="table">
	<div class="row"><div class="cell formtext">
	Fecha Abono:</div><div class="cell">
    <input type="text" name="tr_fecha_abono" value="<?php echo $fecha_abono?>" />
    </div></div><div class="row"><div class="cell formtext">
	Fecha Valor:</div><div class="cell">
    <input type="text" name="tr_fecha_valor" value="<?php echo $fecha_valor?>" />
    </div></div><div class="row"><div class="cell formtext">
    Cuenta Corriente:</div><div class="cell">
    <select name="tr_cc_id" id="cuenta_corriente">
        <?php foreach ($data_cc as $row): ?>
            <option value=<?=$row['cc_id']?>><?=$row['entidad_codigo_b']?></option>
        <?php endforeach ?>
    </select>
	    </div></div><div class="row"><div class="cell formtext">
    Moneda:</div><div class="cell">
    <select name="tr_moneda" id="">
            <option value="clp">CLP</option>
            <option value="uf">UF</option>
    </select>
	    </div></div><div class="row"><div class="cell formtext">
    Monto: </div><div class="cell"><input type="text" name="tr_monto" value="" />
	    </div></div><div class="row"><div class="cell formtext">
    Descripción: </div><div class="cell"><input type="text" name="tr_descripcion" value="" />
	    </div></div><div class="row"><div class="cell formtext">
    </div><div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
</form>


<?php require_once($path_include."/cmifooter.php");
