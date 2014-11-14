<?php
function deleteConvenio($id){
	mysql_query("UPDATE `convenio` SET id_estado=0 WHERE id_convenio='$id'") or die(mysql_error());
}

function actualizarConvenio($datos){
	mysql_query("UPDATE `convenio` SET 
				semana			= '$datos[semana]',
				sabado			= '$datos[sabado]',
				salida_sabado	= '$datos[salida_sabado]'
				WHERE id_convenio='$datos[id_convenio]'") or die(mysql_error());

}


function updateConvenio($id_convenio, $convenio){
	mysql_query("UPDATE `convenio` SET 
				convenio		= '$convenio'
				WHERE id_convenio='$id_convenio'") or die(mysql_error());
}

function getConvenios($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
	 	$query="SELECT * FROM convenio WHERE convenio.$campo like '$dato'";   
		$convenio=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM convenio WHERE id_estado=1 ORDER BY convenio";   
		$convenio=mysql_query($query) or die(mysql_error());
	}
			
	return $convenio;
}

function getConvenio($id){
	$query="SELECT * FROM `convenio` WHERE id_convenio='$id'";   
	$convenio=mysql_query($query) or die(mysql_error());
	
	return $convenio;
}


function insertConvenio($convenio){
	mysql_query("INSERT INTO `convenio` (convenio) 
				VALUES ('$convenio')") or die(mysql_error());
	$id_convenio=mysql_insert_id();
	
	mysql_query("INSERT INTO `convenio_turno` 
				(id_convenio, id_turno, lunes, martes, miercoles, jueves, viernes, redondeo, limite) 
				VALUES 
				('$id_convenio', 1,1,1,1,1,1,1,5)") or die(mysql_error());
	
	mysql_query("INSERT INTO `convenio_turno` 
				(id_convenio, id_turno, lunes, martes, miercoles, jueves, viernes, redondeo, limite) 
				VALUES 
				('$id_convenio', 2,1,1,1,1,1,1,5)") or die(mysql_error());
	
	mysql_query("INSERT INTO `convenio_turno` 
				(id_convenio, id_turno, sabado, redondeo, limite) 
				VALUES 
				('$id_convenio', 1,1,1,5)") or die(mysql_error());
}

?>