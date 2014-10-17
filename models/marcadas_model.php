<?php
function deleteMarcada($id){
	mysql_query("UPDATE `marcada` SET 
						id_estado=0
						WHERE id_marcada='$id'
						") or die(mysql_error());
}

function updateMarcada($id_parametros, $fecha, $entrada, $id_estado, $id_marcada){
	mysql_query("UPDATE `marcada` SET 
						id_parametros='$id_parametros',
						entrada = '$fecha $entrada:00',
						id_estado = '$id_estado'
						WHERE id_marcada='$id_marcada'
						") or die(mysql_error());
}

function getMarcaciones($id=NULL, $fecha_inicio=NULL, $fecha_final=NULL){
	if(isset($id, $fecha_inicio, $fecha_final)){
		$query="SELECT * 
			FROM marcada 
			WHERE 
			DATE_FORMAT(entrada, '%Y-%m-%d') >= '$fecha_inicio' AND
			DATE_FORMAT(entrada, '%Y-%m-%d') <= '$fecha_final' AND
			id_usuario='$id' AND
			id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());
	}else if(isset($id, $fecha_inicio)){
		$query="SELECT *	
			FROM marcada 
			INNER JOIN parametros ON(marcada.id_parametros=parametros.id_parametros)
			WHERE DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha_inicio' 
			AND id_usuario='$id' 
			AND id_estado!=0
			ORDER BY marcada.id_parametros";   
		$marcacion=mysql_query($query) or die(mysql_error());
	}else if(isset($fecha_inicio, $fecha_final)){
		$query="SELECT * 
			FROM marcada 
			WHERE 
			DATE_FORMAT(entrada, '%Y-%m-%d') >= '$fecha_inicio' AND
			DATE_FORMAT(entrada, '%Y-%m-%d') <= '$fecha_final' AND
			id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());	
	}else if(isset($fecha_inicio)){
		$query="SELECT * 
			FROM marcada 
			INNER JOIN parametros ON(marcada.id_parametros=parametros.id_parametros)
			WHERE 
			DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha_inicio'
			AND	id_estado!=0
			ORDER BY marcada.id_parametros";   
		$marcacion=mysql_query($query) or die(mysql_error());
	}
	return $marcacion;
}


function insertMarcada($id_parametros, $fecha, $entrada, $id){
	mysql_query("INSERT INTO `marcada` (id_parametros, entrada, id_usuario, id_estado) 
	VALUES ('$id_parametros', '$fecha $entrada:00', '$id', 2)") or die(mysql_error());
}

function insertMarcadaAccess($CHECKTIME, $USERID, $CHECKTYPE, $id_parametros){
	mysql_query("INSERT INTO marcada 
					(entrada, id_usuario,id_parametros_access, id_parametros,id_estado) 
					VALUES 
					('$CHECKTIME','$USERID','$CHECKTYPE','$id_parametros')") 
					or die(mysql_error());

}


function insertMarcadaReloj($registro){

		$entrada = $registro['date']." ".$registro['time'];
		
		$query="SELECT *	
				FROM marcada 
				WHERE entrada_reloj like '$entrada' 
				AND id_usuario='$registro[id_user]'";
		   
		$marcacion			= mysql_query($query) or die(mysql_error());
		$cantidad_marcacion	= mysql_num_rows($marcacion);
		
		if($cantidad_marcacion==0){
			$hora=date('H:i', strtotime($registro['time'])); 
			
			//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA 
			if($registro['status']=="IN" || $registro['status']==1){ 
			    $tipo=1; 
			}else{ 
			    $tipo=2; 
			} 
			//BUSCO DENTRO DE PARAMETROS SI ES MAÑANA TARDE O NOCHE DEPENDIENDO DE LA HORA 
			$query="SELECT * FROM `parametros`  
			        WHERE DATE_FORMAT(inicio, '%H:%m')<'$hora'  
			        AND DATE_FORMAT(final, '%H:%m')>'$hora' 
			        AND id_tipo='$tipo'";    
			$parametros=mysql_query($query) or die(mysql_error()); 
			$row_parametros = mysql_fetch_assoc($parametros); 
			$cantidad=mysql_num_rows($parametros); 
			 
			//SI NO COINCIDE CON NINGUNO VA 0 
			if($cantidad<0){ 
			    $id_parametros=0; 
			}else{ 
			    $id_parametros=$row_parametros['id_parametros']; 
			} 
	
			mysql_query("INSERT INTO 
						marcada(
							entrada, 
							id_usuario,
							id_parametros,
							id_parametros_access,
							verification,
							id_reloj,
							entrada_reloj,
							id_estado
						)
						VALUES(
							'$entrada',
						 	'$registro[id_user]',
						 	'$id_parametros',
						 	'$registro[status]',
						 	'$registro[verification]',
							'$registro[id_reloj]',
							'$entrada',
							1
						)") 
						or die(mysql_error());
			return 1;
		}else{
			return 0;
		}
}

?>