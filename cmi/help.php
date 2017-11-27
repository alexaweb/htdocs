<?php
	require_once('../../includes/cmi_common.php');
    
    // At the top of the page we check to see whether the user is logged in or not
    if(empty($_SESSION['user']))
    {
        // If they are not, we redirect them to the login page.
        header("Location: login.php");
        
        // Remember that this die statement is absolutely critical.  Without it,
        // people can view your members-only content without logging in.
        die("Redirecting to login.php");
    }
    
    // Everything below this point in the file is secured by the login system
    
    // We can retrieve a list of members from the database using a SELECT query.
    // In this case we do not have a WHERE clause because we want to select all
    // of the rows from the database table.

?>


<h2>Instrucciones</h2>

<p><h6>Saldos:</h6> Muestra saldo de capital e interés al día de ayer (por defecto).  Se puede seleccionar otra fecha en el pasado también.</p>

<p><h6>Interés:</h6> Muestra saldo de interés devengado en UF y en CLP para cada cuenta.  Desde esta página se vinculan un conjunto de opciones: <br>
<ul>a) Generar un reporte HTML o PDF del interés devengado.</ul>
<ul>b) Ingresar un abono para cancelar interés.</ul>
<p>A su vez, a partir del reporte HTML se puede nuevamente generar el reporte PDF o enviar dicho PDF por email a: XXXXXXX</p>


<p><h6>Cartolas:</h6> Se muestran todas las transferencias efectuadas en ambos sentidos, así como su descomposición en Capital y/o Interés.</p>

<p><h6>RESET:</h6> Operación recalcula el interés devengado y pagado, así como los abonos y amortizaciones de capital. Se debe muestran todas las transferencias efectuadas en ambos sentidos, así como su descomposición en Capital y/o Interés.</p>
Se debe ejecutar una vez por cada cuenta.
<p><h7>Nota:</h7> El ideal es ejecutar esta operación diariamente a continuación de la carga de indicadores mediante un 'cron'.</p>

<?php require_once("/var/www/includes/cmifooter.php");