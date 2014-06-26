<?php
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

function updatePrametro($id_turno,
												$id_tipo,
												$inicio,
												$final,
												$considerar,
												$id_parametros){
	mysql_query("UPDATE `parametros` SET 
							id_turno='$id_turno',
							id_tipo='$id_tipo',
							inicio='$inicio',
							final='$final',
							considerar='$considerar'
							WHERE id_parametros='$id_parametros'
							") or die(mysql_error());
}



?>