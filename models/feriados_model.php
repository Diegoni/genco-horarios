<?php
include_once('logs_model.php');

function deleteFeriado($id){
	mysql_query("DELETE FROM `feriado` WHERE id_feriado='$id'") or die(mysql_error());
	
	$datos=array(
			'tabla'		=> 'feriado', 
			'id_tabla'	=> $id, 
			'id_accion'	=> 3 );
			
	insertLog($datos);
}

function insertFeriado($dia, $motivo){
	mysql_query("INSERT INTO `feriado` (dia, feriado) 
	VALUES ('$dia', '$motivo')") or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos=array(
			'tabla'		=> 'feriado', 
			'id_tabla'	=> $id, 
			'id_accion'	=> 1 );
			
	insertLog($datos);
}

function getFeriados($dia=NULL){
	if(isset($dia)){
		$query="SELECT * FROM feriado WHERE DATE_FORMAT(dia, '%Y-%m-%d') like '$dia'";   
		$feriado=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM feriado ORDER BY dia";   
		$feriado=mysql_query($query) or die(mysql_error());
	}
	
	return $feriado;
}
?>