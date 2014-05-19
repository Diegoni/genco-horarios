<?php
function deleteFeriado($id){
	mysql_query("DELETE FROM `feriado` WHERE id_feriado='$id'") or die(mysql_error());
}

function insertFeriado($dia, $motivo){
	mysql_query("INSERT INTO `feriado` (dia, feriado) 
	VALUES ('$dia', '$motivo')") or die(mysql_error());
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