<?php
function getCorreos($dato=NULL, $campo=NULL){
	
	if(isset($dato, $campo)){
		$query="SELECT  *
				FROM `departamento` 
				WHERE 
				departamento.$campo like '$dato'";
		$departamento=mysql_query($query) or die(mysql_error());
	}else{
		
		$query="SELECT * FROM departamento WHERE id_estado=1 ORDER BY departamento";   
		$departamento=mysql_query($query) or die(mysql_error());
	}	
	return $departamento;
}

function getCorreo($id){
	$query="SELECT * FROM `departamento` WHERE id_departamento='$id'";   
	$departamento=mysql_query($query) or die(mysql_error());

	return $departamento;
}

function insertCorreo($datos){	
	mysql_query("INSERT INTO `correos` (
					asunto,
					mensaje,
					fecha_inicio,
					fecha_final,
					grupo,
					id,
					email_1,
					email_2,
					email_3,
					id_usuario,
					fecha,
					id_tipo_reporte) 
				VALUES(
					'$datos[asunto]',
					'$datos[mensaje]',
					'$datos[fecha_inicio]',
					'$datos[fecha_final]',
					'$datos[grupo]',
					'$datos[id]',
					'$datos[email_1]',
					'$datos[email_2]',
					'$datos[email_3]',
					'$datos[id_usuario]',
					'$datos[fecha]',
					'$datos[id_tipo_reporte]')") or die(mysql_error());
}

?>