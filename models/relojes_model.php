<?php
include_once('logs_model.php');

function deleteReloj($id){
	$update = "UPDATE 
					`relojes` 
				SET 
					id_estado = 0 
				WHERE 
					id_reloj = '$id'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'relojes', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function updateReloj($datos){
	$update = "UPDATE 
					`relojes` 
				SET	
					reloj	= '$datos[reloj]',
					ip		= '$datos[ip]',
					puerto	= '$datos[puerto]',
					color	= '$datos[color]'		
				WHERE 
					id_reloj = '$datos[id_reloj]'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'departamento', 
		'id_tabla'	=> $datos['id_reloj'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}

function getRelojes($dato=NULL, $campo=NULL){
	
	if(isset($dato, $campo)){
		$query = "SELECT 
						*
					FROM 
						`relojes` 
					WHERE 
						relojes.$campo like '$dato'";
	}else{
		$query = "SELECT 
						* 
					FROM 
						relojes 
					WHERE 
						relojes.id_estado = 1 
					ORDER BY 
						reloj";   
	}
	$relojes = mysql_query($query) or die(mysql_error());	
	return $relojes;
}

function getReloj($id){
	$query = "SELECT 
					* 
				FROM 
					`relojes` 
				WHERE 
					id_reloj = '$id'";   
	$reloj=mysql_query($query) or die(mysql_error());

	return $reloj;
}

function insertReloj($datos){
	$insert = "INSERT INTO `relojes`(
					reloj, 
					ip, 
					puerto, 
					color, 
					id_estado
				) VALUES (
					'$datos[reloj]', 
					'$datos[ip]',
					'$datos[puerto]',
					'$datos[color]', 
					1
				)";
	
	mysql_query($insert) or die(mysql_error());
				
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'departamento', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);
}

?>