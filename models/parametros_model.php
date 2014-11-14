<?php
include_once('logs_model.php');

function getParametros($hora=NULL, $tipo=NULL){
	if(isset($hora, $tipo)){
		$query="SELECT * FROM `parametros` 
			WHERE DATE_FORMAT(inicio, '%H:%m')<'$hora' 
			AND DATE_FORMAT(final, '%H:%m')>'$hora'
			AND id_tipo='$tipo'";   
		$parametros=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM parametros 		
				INNER JOIN turno ON(parametros.id_turno=turno.id_turno)
				INNER JOIN tipo ON(parametros.id_tipo=tipo.id_tipo)";   
		$parametros=mysql_query($query) or die(mysql_error());
	}
	
	return $parametros;
}

function getParametro($id){
	$query="SELECT * FROM parametros
			INNER JOIN
			tipo ON(parametros.id_tipo=tipo.id_tipo)
			INNER JOIN
			turno ON(parametros.id_turno=turno.id_turno)
			WHERE id_parametros='$id'"; 
	$parametros=mysql_query($query) or die(mysql_error());

	return $parametros;
}

function getParametroMax(){
	$query="SELECT max(id_parametros) as max FROM `parametros`";   
	$max_id=mysql_query($query) or die(mysql_error());
	$row_max_id = mysql_fetch_assoc($max_id);
	
	return $row_max_id['max'];
}

function updatePrametro($registro){
	mysql_query("UPDATE `parametros` SET 
					id_turno	= '$registro[id_turno]',
					id_tipo		= '$registro[id_tipo]',
					inicio		= '$registro[inicio]',
					final		= '$registro[final]',
					considerar	= '$registro[considerar]'
				WHERE 
					id_parametros='$registro[id_parametros]'") or die(mysql_error());
					
	$datos=array(
			'tabla'		=> 'parametros', 
			'id_tabla'	=> $registro['id_parametros'], 
			'id_accion'	=> 2 );
			
	insertLog($datos);
}



?>