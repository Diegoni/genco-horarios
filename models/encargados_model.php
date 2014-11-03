<?php
function deleteEncargado($id){
	mysql_query("UPDATE `encargados` SET encargados.delete=1 WHERE id_encargado='$id'") or die(mysql_error());
}

function updateEncargado($datos){
	mysql_query("UPDATE `encargados` SET	
				nombre			= '$datos[nombre]',
				apellido		= '$datos[apellido]',
				email_1			= '$datos[email_1]',
				email_2			= '$datos[email_2]',
				email_3			= '$datos[email_3]'
				WHERE 
				id_encargado	= '$datos[id_encargado]'			
				") or die(mysql_error());
}

function getEncargados($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query		= "SELECT  *
						FROM `encargados` 
						WHERE 
						encargado.$campo like '$dato'";
		$encargado	= mysql_query($query) or die(mysql_error());
	}else{
		$query		= "SELECT * FROM `encargados` WHERE `delete` = 0";   
		$encargado	= mysql_query($query) or die(mysql_error());
	}	
	return $encargado;
}

function getEncargado($id){
	$query		= "SELECT * FROM `encargados` WHERE id_encargado='$id'";   
	$encargado	= mysql_query($query) or die(mysql_error());

	return $encargado;
}

function insertEncargado($datos){
	mysql_query("INSERT INTO 
					`encargados` 
					(nombre, apellido) 
				VALUES 
					('$datos[nombre]', '$datos[apellido]')") or die(mysql_error());
	return mysql_insert_id();
}

?>