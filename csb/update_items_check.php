<?php
	$current=511;
	$page_category = "abastecimiento CSB";
	$page_name = "modificar item";
	require_once('../../includes/csb_common.php');



    // This if statement checks to determine whether the transaction has been submitted
    // If it has, then the transaction insert code is run, otherwise the form is displayed
    if(empty($_POST)){
	// This redirects the user back to the login page after they register
        header("Location: update_items.php");

        // Calling die or exit after performing a redirect using the header function
        // is critical.  The rest of your PHP script will continue to execute and
        // will be sent to the user if you do not die or exit.
        die("Redirecting to update_items.php");
	} else
    {
			$where_string = $_POST['w_string'];




	$item_buque = $_POST['item_buque'];
	$item_descripcion = $_POST['item_descripcion'];
  $item_origen = $_POST['item_origen'];
	$item_proveedor = $_POST['item_proveedor'];
	$item_bl_awb = $_POST['item_bl_awb'];
	$item_container = $_POST['item_container'];
	$item_carrier = $_POST['item_carrier'];
	$item_status = $_POST['item_status'];
  $item_thc = $_POST['item_thc'];

    }

$set_string = "set ";

if (isset($item_buque) and !empty($item_buque)) {
	$set_string = $set_string." item_buque='$item_buque'";
}
if (isset($item_descripcion) and !empty($item_descripcion)) {
	$set_string = $set_string." item_descripcion='$item_descripcion'";
}
if (isset($item_origen) and !empty($item_origen)) {
	$set_string = $set_string." item_origen='$item_origen',";
}
if (isset($item_proveedor) and !empty($item_proveedor)) {
	$set_string = $set_string." item_proveedor='$item_proveedor',";
}
if (isset($item_bl_awb) and !empty($item_bl_awb)) {
	$set_string = $set_string." item_bl_awb='$item_bl_awb',";
}
if (isset($item_container) and !empty($item_container)) {
	$set_string = $set_string." item_container='$item_container',";
}
if (isset($item_carrier) and !empty($item_carrier)) {
	$set_string = $set_string." item_carrier='$item_carrier',";
}
if (isset($item_status) and !empty($item_status)) {
	$set_string = $set_string." item_status='$item_status',";
}
if (isset($item_thc) and !empty($item_thc)) {
	$set_string = $set_string." item_thc='$item_thc',";
}

$set_string = rtrim($set_string, ", ");
$set_string = $set_string." ";




//echo $set_string;
//echo $where_string;
$update_string = "update items ".$set_string.$where_string.";";
		//echo(count($items));
		//echo "<br>Update string: ".$update_string;


?>



<h2>Confirmar</h2>
<form action="post/update_items_post.php" method="post">

	<div class="wrapper">
	<div class="table">
			<div class="rowheader">
		<div class="cell formtext"></div><div size="100" class="textcell"></div>
	</div>
	<div class="row">
		<div class="cell text">SQL:</div><div><input type="hidden" name="update_string" value="<?=$update_string?>" /><?=$update_string?></div>
	</div>


	<div class="row"><div class="cell">
     <button class="button-back" type="button" onclick="history.back();">BACK</button></div>
    <div><button class="button-submit" type="submit" value="Submit">OK</button>
	</div></div></div></div>
	0: terminado <br>
                        1: normal <br>
<div class="row warning">2: warning</div>
<div class="row alert">3: alert </div>
<div class="row sea">4: @ sea</div>
<div class="row cicarelli">5: @ cicarelli</div>
<div class="row puq">6: @ puq</style></div>

	</form>
<?php require_once($path_include."/cmifooter.php");
