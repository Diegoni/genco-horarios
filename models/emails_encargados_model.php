<?php
function deleteEncargado_email($id){
	mysql_query("UPDATE `emails_encargado` SET emails_encargado.delete=1 WHERE id_email_encargado='$id'") or die(mysql_error());
}

function updateEncargado_email($departamento,$id){
	mysql_query("UPDATE `departamento` SET	
				nombre='$departamento',
				id_estado=1		
				WHERE id_departamento='$id'			
				") or die(mysql_error());
}

function getEncargados_email($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query="SELECT  *
				FROM `encargados` 
				WHERE 
				encargado.$campo like '$dato'";
		$encargado=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM `encargados` WHERE `delete` = 0";   
		$encargado=mysql_query($query) or die(mysql_error());
	}	
	return $encargado;
}

function getEncargado_email($id){
	$query				= "SELECT * FROM `emails_encargado` WHERE id_encargado = '$id' AND emails_encargado.delete=0";   
	$emails_encargado	= mysql_query($query) or die(mysql_error());

	return $emails_encargado;
}

function insertEncargado_email($datos){
	mysql_query("INSERT INTO `emails_encargado` 
							(email_encargado, id_encargado) 
							VALUES 
							('$datos[email]', '$datos[id_encargado]')") or die(mysql_error());
}

?>