<?php

	$current=323;
		$dbfile = "arriendosDB.php";
		require_once('../../../includes/common.php');
		//require_once('/var/www/includes/cmiheader.php');
		setlocale(LC_TIME, 'es_ES.UTF-8');
		date_default_timezone_set('America/Santiago');

//	require_once('../../includes/arriendos_common.php');
    
    
    
    

    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
	$p_orden = $_GET['p_orden'];
        
    if(empty($p_orden))
    {
            $p_orden = 0;
			//die("Ingresar ano.");
    }
	
		$fecha_hoy = date("Y-m-d");
    	try
        {
            $sql_uf = "call get_valor_uf('{$fecha_hoy}');";
			
            $stmt_uf = $db->prepare($sql_uf);
            $result_uf = $stmt_uf->execute();
            $data_uf = $stmt_uf->fetch();
			$stmt_uf->closeCursor();
			$valor_uf = $data_uf['valor'];

        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
                
        try
        {
            //$sql = "select tr_fecha, tr_tipo_transaccion, tr_moneda,tr_monto,tr_monto_uf,tr_descripcion from transacciones where tr_cc_id = '{$tr_cc_id}' order by tr_fecha;";
            $sql = "call proc_display_arriendo_moroso(5);";
			//echo $sql;
            $stmt = $db->prepare($sql);
            $result = $stmt->execute();
            $data = $stmt->fetchAll();
			$stmt->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
        
$total_mora_uf = 0;
$total_mora_clp = 0;
?>
<head profile="http://www.w3.org/2005/10/profile">
<title>Gestión Cicarelli</title>
<link rel="icon" 
      type="image/png" 
      href="/audit/css/favicon.ico" />
<link rel="stylesheet" type="text/css" href="/audit/css/cmistyles.css" />
</head>
<body>
	<div id="content-body-wrapper" >
        <div id="content-body">
			  <div id="maincontent" >
		
				
<h2>Propiedades / Contratos de Arriendo en Mora</h2>
		
	
<div class="table">
	<div class="rowheader">
		<div class="cell">f. Aviso</div>
		<div class="cell">Propietario</div>
		<div class="cell">Propiedad</div>
		<div class="textcell">Arrendatario</div>
		<div class="cell">[ $ o UF/mes ]</div>
		<div class="cell">Cuotas Vencidas</div>
		<div class="cell">Monto (UF)</div>
		<div class="cell">Monto (CLP)</div>
	</div>
    <?php foreach ($data as $row): ?>
			<?php
		$co_id = $row['co_id'];
		try
        {
		   
            $sql_3 = "call proc_display_cuotas_vencidas($co_id);";
            $stmt_3 = $db->prepare($sql_3);
            $result_3 = $stmt_3->execute();
            $data_3 = $stmt_3->fetch();
			$stmt_3->closeCursor();
        }
        catch(PDOException $ex)
        {
            die("Failed to run query: " . $ex->getMessage());
        }
		?>
		
		<?php if ($data_3['cantidad']>0){
			echo '<div class="row alert">';
		} else{
			echo '<div class="row">';
		}
		?>
			<div class="cell"><?=$row['co_fecha_aviso'];?></div>
			<div class="cell"><?=$row['co_cuenta_deposito'];?></div>
			<div class="cell"><?=$row['pr_codigo'];?></div>
			<div class="textcell"><?=$row['ar_nombre'];?></div>
			<div class="numbercell"><?=($row['co_tipo']==1?"EE":($row['co_tipo']==2?money_format('%.0n',$row['co_monto_clp']):number_format($row['co_monto_uf'],2)));?></div>
		
			
			

			<div class="numbercell"><?=$data_3['cantidad'];?></div>
			<div class="numbercell">UF <?=number_format($data_3['monto_uf'],2);?></div>
			<div class="numbercell">$ <?php
				if ($data_3['monto_uf']>0 and $data_3['monto']==0) {
					echo number_format($data_3['monto_uf']*$valor_uf);
					$total_mora_clp = $data_3['monto_uf']*$valor_uf + $total_mora_clp;
						$total_mora_uf = $data_3['monto_uf'] + $total_mora_uf;
				}
				else {
					echo number_format($data_3['monto']);
					$total_mora_clp = $data_3['monto'] + $total_mora_clp;
						$total_mora_uf = $data_3['monto_uf'] + $total_mora_uf;
				}?></div>
		</div>
		<?
			//$total_mora_uf = $data_3['monto_uf'] + $total_mora_uf;
		?>
    <?php endforeach ?>
	<div class="rowfooter">
		<div class="cell"></div>
		<div class="textcell"></div>
		<div class="cell"></div>
		<div class="cell"></div>
		<div class="cell"></div>
		<div class="cell"></div>
		<div class="numbercell">UF <?=number_format($total_mora_uf,2);?></div>
		<div class="numbercell">$ <?=number_format($total_mora_clp);?></div>
	</div>
</div>
		

<?php require_once($path_include."/cmifooter.php");
