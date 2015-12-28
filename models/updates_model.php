<?php
function getUpdates(){
	$query = "SELECT 
					* 
				FROM 
					`update` 
				INNER JOIN 
					relojes ON(update.id_reloj = relojes.id_reloj) 
				ORDER BY 
					update.start_date, 
					relojes.id_reloj";   
	$update = mysql_query($query) or die(mysql_error());
	
	return $update;
}

function getUpdate($id){
	$query = "SELECT 
					* 
				FROM 
					`update` 
				INNER JOIN 
					relojes ON(update.id_reloj = relojes.id_reloj) 
				WHERE 
					id_update = '$id'";   
	$update = mysql_query($query) or die(mysql_error());
	
	return $update;
}

function getlastUpdates($id){
	$query = "SELECT 
					max(`end_date`) as end_date 
				FROM 
					`update` 
				WHERE 
					id_reloj = '$id'";   
	$update = mysql_query($query) or die(mysql_error());
	
	return $update;
}

function insertUpdate($datos){
	
	$insert = "INSERT INTO  `update` (
					start_date,
					end_date,
					id_reloj,
					cantidad_registros,
					fecha_update,
					id_tipo,
					id_usuario
				) VALUES (
					'$datos[start_date]',
					'$datos[end_date]',
					'$datos[id_reloj]',
					'$datos[cantidad_registros]',
					'$datos[fecha_update]',
					'$datos[id_tipo]',
					'$datos[id_usuario]'
				);";
	
	mysql_query($insert) or die(mysql_error());
}

?>