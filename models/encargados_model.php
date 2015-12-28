<?php
include_once('logs_model.php');

function deleteEncargado($id){
	$update = "UPDATE 
					`encargados` 
				SET 
					encargados.delete = 1 
				WHERE 
					id_encargado='$id'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'encargados', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function updateEncargado($datos){
	$update = "UPDATE 
					`encargados` 
				SET	
					nombre			= '$datos[nombre]',
					apellido		= '$datos[apellido]',
					email_1			= '$datos[email_1]',
					email_2			= '$datos[email_2]',
					email_3			= '$datos[email_3]'
				WHERE 
					id_encargado	= '$datos[id_encargado]'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'encargados', 
		'id_tabla'	=> $datos['id'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}

function getEncargados($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query = "SELECT  
						*
					FROM 
						`encargados` 
					WHERE 
						encargado.$campo like '$dato'";
	}else{
		$query = "SELECT 
						* 
					FROM 
						`encargados` 
					WHERE 
						`delete` = 0";   
	}
	$encargado = mysql_query($query) or die(mysql_error());	
	return $encargado;
}

function getEncargado($id){
	$query = "SELECT 
					* 
				FROM 
					`encargados` 
				WHERE 
					id_encargado = '$id'";   
	$encargado	= mysql_query($query) or die(mysql_error());

	return $encargado;
}

function insertEncargado($datos){
	$insert = "INSERT INTO `encargados`(
					nombre, 
					apellido
				) VALUES (
					'$datos[nombre]', 
					'$datos[apellido]'
				)";	
	
	mysql_query($insert) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'encargados', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);
	
	return $id;
}

function getEncargado_departamento($id){
	$query = "SELECT 
					* 
				FROM 
					`departamento`
				INNER JOIN 
					encargados ON(departamento.id_encargado = encargados.id_encargado) 
				WHERE 
					departamento.id_departamento = '$id'";   
	$encargado	= mysql_query($query) or die(mysql_error());

	return $encargado;
	
}

?>