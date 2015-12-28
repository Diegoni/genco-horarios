<?php
function deleteEncargado_email($id){
	$update = "UPDATE 
					`emails_encargado` 
				SET 
					emails_encargado.delete = 1 
				WHERE 
					id_email_encargado = '$id'";
	
	mysql_query($update) or die(mysql_error());
}

function updateEncargado_email($departamento,$id){
	$update = "UPDATE 
					`departamento` 
				SET	
					nombre = '$departamento',
					id_estado = 1		
				WHERE 
					id_departamento='$id' ";
	
	mysql_query($update) or die(mysql_error());
}

function getEncargados_email($dato=NULL, $campo=NULL){
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

function getEncargado_email($id){
	$query = "SELECT 
					* 
				FROM 
					`emails_encargado` 
				WHERE 
					id_encargado = '$id' AND 
					emails_encargado.delete = 0";   
	$emails_encargado	= mysql_query($query) or die(mysql_error());

	return $emails_encargado;
}

function insertEncargado_email($datos){
	$insert = "INSERT INTO `emails_encargado` (
					email_encargado, 
					id_encargado
				) VALUES (
					'$datos[email]', 
					'$datos[id_encargado]'
				)";
	
	mysql_query($insert) or die(mysql_error());
}

?>