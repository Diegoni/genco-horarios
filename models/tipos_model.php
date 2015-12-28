<?php
function getTipos(){
	$query = "SELECT 
					* 
				FROM 
					`tipo`";   
	$tipo = mysql_query($query) or die(mysql_error());
	
	return $tipo;
}


function getTipos_usuario(){
	$query = "SELECT 
					* 
				FROM 
					`tipo_usuario`";   
	$tipo = mysql_query($query) or die(mysql_error());
	
	return $tipo;
}

?>