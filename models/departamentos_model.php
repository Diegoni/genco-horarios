<?php
include_once('logs_model.php');

function deleteDepartamento($id){
	$update = "UPDATE 
					`departamento` 
				SET 
					id_estado = 0 
				WHERE 
					id_departamento = '$id'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'departamento', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function updateDepartamento($datos){
	$update = "UPDATE 
					`departamento` 
				SET	
					departamento	= '$datos[departamento]',
					id_encargado	= '$datos[id_encargado]',
					id_estado		= 1		
				WHERE 
					id_departamento	= '$datos[id]' ";
	
	mysql_query($update) or die(mysql_error());
	
	$datos=array(
		'tabla'		=> 'departamento', 
		'id_tabla'	=> $datos['id'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}

function getDepartamentos($dato=NULL, $campo=NULL){
	
	if(isset($dato, $campo)){
		$query = "SELECT  
						*
					FROM 
						`departamento` 
					WHERE 
						departamento.$campo like '$dato'";
	}else{
		$query = "SELECT 
						* 
					FROM 
						departamento 
					WHERE 
						id_estado = 1 
					ORDER BY 
						departamento";   
	}	
	$departamento = mysql_query($query) or die(mysql_error());
	
	return $departamento;
}

function getDepartamento($id){
	$query = "SELECT 
					* 
				FROM 
					`departamento` 
				WHERE 
					id_departamento = '$id'";   
	$departamento = mysql_query($query) or die(mysql_error());

	return $departamento;
}

function insertDepartamento($departamento){
	$query = "SELECT 
					max(id_departamento) as max 
				FROM 
					`departamento`";
	$departamentos		= mysql_query($query) or die(mysql_error());
	$row_departamento	= mysql_fetch_assoc($departamentos);
	$max				= $row_departamento['max']+1;
	
	$insert = "INSERT INTO `departamento` (
					id_departamento, 
					departamento, 
					id_estado
				) VALUES (
					'$max', 
					'$departamento', 
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