<?php
function deleteConvenioturno($id){
	mysql_query("UPDATE `convenio_turno` SET id_estado=0 WHERE id_convenio_turno='$id'") or die(mysql_error());
}

function updateConvenioturno($datos){
	mysql_query("UPDATE `convenio_turno` SET 
					entrada='$datos[entrada]',
					salida='$datos[salida]',
					limite='$datos[limite]',
					id_estado='$datos[id_estado]',
					lunes='$datos[lunes]',
					martes='$datos[martes]',
					miercoles='$datos[miercoles]',
					jueves='$datos[jueves]',
					viernes='$datos[viernes]',
					sabado='$datos[sabado]',
					domingo='$datos[domingo]',
					redondeo='$datos[redondeo]'	
					WHERE id_convenio_turno ='$datos[id_convenio_turno]'") or die(mysql_error());

}

function getConvenioturnos($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
	 	$query="SELECT * FROM convenio_turno WHERE convenio_turno.$campo='$dato' AND id_estado=1";   
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