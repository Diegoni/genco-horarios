<?php
function getTemp($id_usuario, $id_parametro){
	$query = "SELECT 
					* 
				FROM 
					temp 
				WHERE
					id_usuario = '$id_usuario' AND 
					id_parametros = '$id_parametro' ";       
	$rows = mysql_query($query) or die(mysql_error());
	
	return $rows;
}

function getTempotra($id_usuario){
	$query = "SELECT 
					* 
				FROM 
					tempotra 
				INNER JOIN 
					tipootra ON(tempotra.id_tipootra = tipootra.id_tipootra)
				INNER JOIN 
					nota ON(tempotra.id_nota = nota.id_nota)
				WHERE
					id_usuario = '$id_usuario'";  
	$rows = mysql_query($query) or die(mysql_error());
	
	return $rows;
}

function getTempFecha($fecha, $id_parametro){
	$query = "SELECT 
					* 
				FROM 
					temp 
				WHERE
					DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha' AND 
					id_parametros = $id_parametro";   
	$rows = mysql_query($query) or die(mysql_error());
	
	return $rows;
}


function getTempFechaOtra($fecha, $id_usuario){
	$query = "SELECT 
					* 
				FROM 
					tempotra 
				INNER JOIN 
					tipootra ON(tempotra.id_tipootra = tipootra.id_tipootra)
				INNER JOIN 
					nota ON(tempotra.id_nota = nota.id_nota)
				WHERE
					id_usuario = '$id_usuario' AND
					fecha = '$fecha'";    
	$rows = mysql_query($query) or die(mysql_error());
	
	return $rows;
}
	
	




?>