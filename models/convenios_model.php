<?php
function deleteConvenio($id){
	mysql_query("UPDATE `convenio` SET id_estado=0 WHERE id_convenio='$id'") or die(mysql_error());
}

function actualizarConvenio($datos){
	mysql_query("UPDATE `convenio` SET 
				semana='$datos[semana]',
				sabado='$datos[sabado]',
				salida_sabado='$datos[salida_sabado]'
				WHERE id_convenio='$datos[id_convenio]'") or die(mysql_error());

}


function updateConvenio($id_convenio, $convenio){
	mysql_query("UPDATE `convenio` SET 
				convenio='$convenio'
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


function insertConvenio($convenio,
												$semana,
												$sabado,
												$salida_sabado){
	mysql_query("INSERT INTO `convenio` (convenio, semana, sabado, salida_sabado, id_estado) 
				VALUES ('$convenio', '$semana', '$sabado', '$salida_sabado', 1)") or die(mysql_error());
}

?>