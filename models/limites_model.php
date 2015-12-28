<?php
include_once('logs_model.php');

function deleteLimite($id){
	$delete = "DELETE FROM 
					`limite` 
				WHERE 
					id_limite='$id'";
	
	mysql_query($delete) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'limite', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function insertLimite($datos){
	$insert = "INSERT INTO `limite` (
					limite, 
					redondeo, 
					suma_hora
				) VALUES (
					'$datos[limite]', 
					'$datos[redondeo]', 
					$datos[suma_hora]
				)";
	mysql_query($insert) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'limite', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);
}


function getLimites(){
	$query = "SELECT 
					* 
				FROM 
					limite 
				ORDER BY 
					limite";   
	$limite = mysql_query($query) or die(mysql_error());
	
	return $limite;
}

function getLimite($id){
	$query = "SELECT 
					* 
				FROM 
					limite 
				WHERE 
					id_limite = '$id'";   
	$limite = mysql_query($query) or die(mysql_error());
	
	return $limite;
}

function updateLimite($datos){
	$update = "UPDATE 
					`limite` 
				SET	
					limite		= '$datos[limite]',
					redondeo	= '$datos[redondeo]',
					suma_hora	= '$datos[suma_hora]'		
				WHERE 
					id_limite	= '$datos[id_limite]'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'limite', 
		'id_tabla'	=> $datos['id_limite'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}
?>