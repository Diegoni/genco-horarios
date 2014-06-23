<?php
function deleteConvenioturno($id){
	mysql_query("UPDATE `convenio` SET id_estado=0 WHERE id_convenio='$id'") or die(mysql_error());
}

function updateConvenioturno($id_convenio, 
												$convenio, 
												$semana,
												$sabado,
												$salida_sabado,
												$id_estado){
	mysql_query("UPDATE `convenio` SET 
								convenio='$convenio',
								semana='$semana',
								sabado='$sabado',
								salida_sabado='$salida_sabado',
								id_estado='$id_estado'
								WHERE id_convenio='$id_convenio'") or die(mysql_error());

}

function getConvenioturnos($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
	 	$query="SELECT * FROM convenio_turno WHERE convenio_turno.$campo='$dato'";   
		$convenio_turno=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM convenio_turno WHERE id_estado=1 ORDER BY convenio";   
		$convenio_turno=mysql_query($query) or die(mysql_error());
	}
			
	return $convenio_turno;
}

function getConvenioturno($id){
	$query="SELECT * FROM 
			`convenio_turno` 

			WHERE id_convenio_turno='$id'";   
	$convenio_turno=mysql_query($query) or die(mysql_error());
	
	return $convenio_turno;
}


function insertConvenioturno($datos){
	mysql_query("INSERT INTO `convenio_turno` 
				(id_convenio, 
				entrada, 
				salida, 
				limite, 
				redondeo,
				lunes,
				martes,
				miercoles,
				jueves,
				viernes,
				sabado,
				domingo,
				id_estado) 
				VALUES 
				('$datos[id_convenio]',
				'$datos[entrada]',
				'$datos[salida]',
				'$datos[limite]',
				'$datos[redondeo]',
				'$datos[lunes]',
				'$datos[martes]',
				'$datos[miercoles]',
				'$datos[jueves]',
				'$datos[viernes]',
				'$datos[sabado]',
				'$datos[domingo]',
				'$datos[id_estado]')") or die(mysql_error());
}

?>