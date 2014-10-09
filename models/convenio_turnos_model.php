<?php


function deleteConvenioturno($id){
	mysql_query("UPDATE `convenio_turno` SET id_estado=0 WHERE id_convenio_turno='$id'") or die(mysql_error());
}

function updateConvenioturno($datos){
	mysql_query("UPDATE `convenio_turno` SET 
					entrada		= '$datos[entrada]',
					salida		= '$datos[salida]',
					limite		= '$datos[limite]',
					id_estado	= '$datos[id_estado]',
					lunes		= '$datos[lunes]',
					martes		= '$datos[martes]',
					miercoles	= '$datos[miercoles]',
					jueves		= '$datos[jueves]',
					viernes		= '$datos[viernes]',
					sabado		= '$datos[sabado]',
					domingo		= '$datos[domingo]',
					redondeo	= '$datos[redondeo]',
					id_turno	= '$datos[id_turno]'	
					WHERE id_convenio_turno ='$datos[id_convenio_turno]'") or die(mysql_error());

}

function getConvenioturnos($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
	 	$query="SELECT * FROM convenio_turno
	 			INNER JOIN
	 			turno ON(turno.id_turno=convenio_turno.id_turno) 
	 			WHERE convenio_turno.$campo='$dato' AND id_estado=1";   
		$convenio_turno=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM convenio_turno WHERE id_estado=1";   
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


function getConvenios_tardanza($datos){
	if($datos['id_parametros']!=0){	
		switch ($datos['dia']) {
			case 0:
		         $dia="domingo";
		         break;
			case 1:
		         $dia="lunes";
		         break;
			case 2:
		         $dia="martes";
		         break;
			case 3:
		         $dia="miercoles";
		         break;
			case 4:
		         $dia="jueves";
		         break;
			case 5:
		         $dia="viernes";
		         break;
			case 6:
		         $dia="sabado";
		         break;
		}
		
		
		switch ($datos['id_parametros']) {
			case 1:
				$parametro="entrada";
				$id_turno = 1;
				break;
			case 2:
				$parametro="salida";
				$id_turno = 1;
				break;
			case 3:
				$parametro="entrada";
				$id_turno = 2;
				break;
			case 4:
				$parametro="salida";
				$id_turno = 2;
				break;
		}
		
		
		$query="SELECT  
					convenio_turno.$parametro as parametro,
					convenio_turno.limite
				FROM `usuario`
				INNER JOIN convenio
				ON(usuario.id_convenio=convenio.id_convenio)
				INNER JOIN convenio_turno
				ON(convenio.id_convenio=convenio_turno.id_convenio)
				WHERE id_usuario='$datos[id_usuario]'
				AND convenio_turno.$dia = 1
				AND convenio_turno.id_turno = '$id_turno'
				";   
		$convenio_turno=mysql_query($query) or die(mysql_error());
		$row_convenio_turno = mysql_fetch_assoc($convenio_turno);   
		$cantidad_convenio_turno = mysql_num_rows($convenio_turno);
		
		
		if($cantidad_convenio_turno>0){
			
			if($parametro=="entrada"){
				if(date('H:i', strtotime($datos['marcada'])) > date('H:i', strtotime("$row_convenio_turno[parametro] + $row_convenio_turno[limite] MINUTES"))){
					$me = date('H:i', strtotime($datos['marcada']));
					$ms = date('H:i', strtotime($row_convenio_turno['parametro']));
					
					$tardanza['tiempo'] = intervalo_tiempo($ms,$me);
					$tardanza['parametro'] = date('H:i', strtotime($row_convenio_turno['parametro']));
					
				}else{
					$tardanza['tiempo'] = 0;
				}
			}else if($parametro=="salida"){
				if(date('H:i', strtotime($datos['marcada'])) < date('H:i', strtotime("$row_convenio_turno[parametro] - $row_convenio_turno[limite] MINUTES"))){
					$me = date('H:i', strtotime($datos['marcada']));
					$ms = date('H:i', strtotime($row_convenio_turno['parametro']));
					
					$tardanza['tiempo'] = intervalo_tiempo($me,$ms);
					$tardanza['parametro'] = date('H:i', strtotime($row_convenio_turno['parametro']));
				}else{
					$tardanza['tiempo'] = 0;
				}
			}
		
		}else{
			$tardanza['tiempo'] = 0;		
		}
		
	}else{
		$tardanza['tiempo'] = 0;		
	}


	
	
	return $tardanza;	
}


?>