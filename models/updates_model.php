<?php
function getUpdates(){
	$query="SELECT * FROM `update` 
			ORDER BY id_update DESC";   
	$update=mysql_query($query) or die(mysql_error());
}

function insertUpdate($ultima_fecha, $USERID, $fecha_hoy, $i){
	mysql_query("INSERT INTO  `update` (
				`ultima_fecha` ,
				`ultimo_id` ,
				`fecha` ,
				`registros`)
		VALUES (
				'$ultima_fecha',  
				'$USERID',  
				'$fecha_hoy',  
				'$i'
				);") or die(mysql_error());
}

?>