<?php
include_once('logs_model.php');

function deleteFeriado($id){
	$delete = "DELETE FROM 
					`feriado` 
				WHERE 
					id_feriado = '$id'";
	
	mysql_query($delete) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'feriado', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function insertFeriado($dia, $motivo){
	$insert = "INSERT INTO `feriado` (
					dia, 
					feriado
				) VALUES (
					'$dia', 
					'$motivo'
				)";
	
	mysql_query($insert) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'feriado', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);
}

function getFeriados($dia=NULL){
	if(isset($dia)){
		$query = "SELECT 
						* 
					FROM 
						feriado 
					WHERE 
						DATE_FORMAT(dia, '%Y-%m-%d') like '$dia'";   
	}else{
		$query = "SELECT 
						* 
					FROM 
						feriado 
					ORDER BY 
						dia";   
	}
	
	$feriado = mysql_query($query) or die(mysql_error());
	
	return $feriado;
}
?>