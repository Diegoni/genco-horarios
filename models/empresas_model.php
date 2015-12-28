<?php
include_once('logs_model.php');

function getEmpresas($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query = "SELECT 
						*
					FROM 
						`empresa` 
					WHERE 
						empresa.$campo like '$dato'";	
	}else{
		$query = "SELECT 
						* 
					FROM 
						`empresa` 
					WHERE 
						empresa.id_estado=1 
					ORDER BY 
						empresa ASC";   
	}
	$empresa = mysql_query($query) or die(mysql_error());
	
	return $empresa;
}

function getEmpresa($id){
	$query = "SELECT 
					* 
				FROM 
					`empresa` 
				WHERE 
					id_empresa = '$id'";   
	$empresa = mysql_query($query) or die(mysql_error());
	
	return $empresa;
}

function updateEmpresa($datos){
	$cuil = $datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
	$update = "UPDATE 
					`empresa` 
				SET	
					empresa		= '$datos[empresa]',
					cod_empresa	= '$datos[cod_empresa]',
					cuil		= '$cuil',
					id_estado	= 1		
				WHERE 
					id_empresa	= '$datos[id]'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'empresa', 
		'id_tabla'	=> $datos['id'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}

function deleteEmpresa($id){
	$update = "UPDATE
					`empresa` 
				SET 
					id_estado = 0 
				WHERE 
					id_empresa = '$id'";	
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'empresa', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function insertEmpresa($datos){
	$cuil = $datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
	$update = "INSERT INTO `empresa` (
					empresa, 
					cod_empresa, 
					cuil, 
					id_estado
				) VALUES (
					'$datos[empresa]', 
					'$datos[cod_empresa]',
					'$cuil',
					1
				)";
	
	mysql_query($update) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'empresa', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);
}

?>