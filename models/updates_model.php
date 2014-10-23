<?php
function getUpdates(){
	$query="SELECT * FROM `update` INNER JOIN relojes ON (update.id_reloj=relojes.id_reloj) ORDER BY update.start_date, relojes.id_reloj";   
	$update=mysql_query($query) or die(mysql_error());
	
	return $update;
}

function insertUpdate($datos){
	
	mysql_query("INSERT INTO  `update` (
				start_date,
				end_date,
				id_reloj,
				cantidad_registros,
				fecha_update,
				id_tipo,
				id_usuario
				)
		VALUES (
				'$datos[start_date]',
				'$datos[end_date]',
				'$datos[id_reloj]',
				'$datos[cantidad_registros]',
				'$datos[fecha_update]',
				'$datos[id_tipo]',
				'$datos[id_usuario]'
				);") or die(mysql_error());
}

?>