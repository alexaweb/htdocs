

<?php
		$current_url = basename($_SERVER['PHP_SELF']);
    $active = "class=\"current\"";
?>
<div id="cartolas">
<a <?php echo ($current_url == "display_cartola_transacciones.php") ? $active : '';?> href="display_cartola_transacciones.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Cartola Transacciones</a>&nbsp;&nbsp;
<a <?php echo ($current_url == "display_cartola_aplicaciones.php") ? $active : '';?> href="display_cartola_aplicaciones.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Cartola Aplicaciones</a>&nbsp;&nbsp;
<a <?php echo ($current_url == "display_detalle_cc.php") ? $active : '';?> href="display_detalle_cc.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Cartola Interés y Saldos</a>&nbsp;&nbsp;
<a <?php echo ($current_url == "display_cartola_interes.php") ? $active : '';?> href="display_cartola_interes.php?tr_cc_id=<?=$tr_cc_id?>&fecha_orden=1">Cartola Interés</a>&nbsp;&nbsp;
</div>
