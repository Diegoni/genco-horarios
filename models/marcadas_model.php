<?php
include_once('logs_model.php');

function deleteMarcada($id){
	$update = "UPDATE 
					`marcada` 
				SET 
					id_estado = 0
				WHERE 
					id_marcada = '$id'";
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'marcada', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3
	);
			
	insertLog($datos);
}

function updateMarcada($id_parametros, $fecha, $entrada, $id_estado, $id_marcada){
	//ver estado = 2
	$update = "UPDATE 
					`marcada` 
				SET 
					id_parametros	= '$id_parametros',
					entrada 		= '$fecha $entrada:00',
					id_estado 		= '$id_estado'
				WHERE 
					id_marcada		= '$id_marcada'";
		
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'marcada', 
		'id_tabla'	=> $id_marcada, 
		'id_accion'	=> 2
	);
			
	insertLog($datos);
}

function getMarcaciones($id=NULL, $fecha_inicio=NULL, $fecha_final=NULL){
	if(isset($id, $fecha_inicio, $fecha_final)){
		$query = "SELECT 
						* 
					FROM 
						marcada 
					WHERE
						DATE_FORMAT(entrada, '%Y-%m-%d')	>= '$fecha_inicio' AND
						DATE_FORMAT(entrada, '%Y-%m-%d')	<= '$fecha_final' AND
						id_usuario							=  '$id' AND
						id_estado							!= 0";   
	}else if(isset($id, $fecha_inicio)){
		$query = "SELECT 
						*	
					FROM 
						marcada 
					INNER JOIN 
						parametros ON(marcada.id_parametros = parametros.id_parametros)
					WHERE 
						DATE_FORMAT(entrada, '%Y-%m-%d') 	like '$fecha_inicio' AND 
						id_usuario						 	=    '$id' AND 
						id_estado							!=	 0
					ORDER BY 
						marcada.id_parametros";   
	}else if(isset($fecha_inicio, $fecha_final)){
		$query = "SELECT 
						* 
					FROM 
						marcada 
					WHERE 
						DATE_FORMAT(entrada, '%Y-%m-%d') 	>= '$fecha_inicio' AND
						DATE_FORMAT(entrada, '%Y-%m-%d') 	<= '$fecha_final' AND
						id_estado							!=	0";   
	}else if(isset($fecha_inicio)){
		$query = "SELECT 
						* 
					FROM 
						marcada 
					INNER JOIN 
							parametros ON(marcada.id_parametros=parametros.id_parametros)
					WHERE 
						DATE_FORMAT(entrada, '%Y-%m-%d') 	like '$fecha_inicio' AND	
						id_estado							!= 0
					ORDER BY 
						marcada.id_parametros";   
	}
	$marcacion = mysql_query($query) or die(mysql_error());
	return $marcacion;
}


function insertMarcada($id_parametros, $fecha, $entrada, $id){
	$insert = "INSERT INTO `marcada` (
					id_parametros, 
					entrada, 
					id_usuario, 
					id_estado
				) VALUES (
					'$id_parametros', 
					'$fecha $entrada:00', 
					'$id', 
					2
				)";	
	
	mysql_query($insert) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'marcada', 
		'id_tabla'	=> $id	, 
		'id_accion'	=> 1
	);
			
	insertLog($datos);
}

function insertMarcadaAccess($CHECKTIME, $USERID, $CHECKTYPE, $id_parametros){
	$insert = "INSERT INTO marcada (
					entrada, 
					id_usuario,
					id_parametros_access, 
					id_parametros,
					id_estado
				) VALUES (
					'$CHECKTIME',
					'$USERID',
					'$CHECKTYPE',
					'$id_parametros'
				)";
						
	mysql_query($insert) or die(mysql_error());
}


function insertMarcadaReloj($registro){

		$entrada = $registro['date']." ".$registro['time'];
		
		$query = "SELECT 
						*	
					FROM 
						marcada 
					WHERE 
						entrada_reloj like '$entrada' AND 
						id_usuario = '$registro[id_user]'";
		$marcacion			= mysql_query($query) or die(mysql_error());
		$cantidad_marcacion	= mysql_num_rows($marcacion);
		
		if($cantidad_marcacion==0){
			$hora=date('H:i', strtotime($registro['time'])); 
			
			//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA 
			if($registro['status']=="IN" || $registro['status']==1 || $registro['status']=="Break IN"){ 
			    $tipo=1; 
			}else{ 
			    $tipo=2; 
			} 
			
			//BUSCO DENTRO DE PARAMETROS SI ES MAÑANA TARDE O NOCHE DEPENDIENDO DE LA HORA 
			$query = "SELECT 
						* 
					FROM 
						`parametros`  
			        WHERE 
			        	DATE_FORMAT(inicio, '%H:%m')	< '$hora' AND 
			        	DATE_FORMAT(final, '%H:%m')		> '$hora' AND 
			        	id_tipo							= '$tipo'";    
			$parametros		= mysql_query($query) or die(mysql_error()); 
			$row_parametros = mysql_fetch_assoc($parametros); 
			$cantidad		= mysql_num_rows($parametros); 
			 
			//SI NO COINCIDE CON NINGUNO VA 0 
			if($cantidad<0){ 
			    $id_parametros=0; 
			}else{ 
			    $id_parametros=$row_parametros['id_parametros']; 
			}
			
			$insert = "INSERT INTO marcada(
							entrada, 
							id_usuario,
							id_parametros,
							id_parametros_access,
							verification,
							id_reloj,
							entrada_reloj,
							id_estado
						) VALUES (
							'$entrada',
						 	'$registro[id_user]',
						 	'$id_parametros',
						 	'$registro[status]',
						 	'$registro[verification]',
							'$registro[id_reloj]',
							'$entrada',
							1
						)"; 
	
			mysql_query($insert) or die(mysql_error());
			return 1;
		}else{
			return 0;
		}
}


	function insertMarcadas($datos){
		$insert = 
			"INSERT INTO marcada (
				entrada, 
				entrada_reloj, 
				id_usuario,
				id_parametros_access,
				id_reloj, 
				id_parametros,
				id_estado
			) VALUES (
				'$datos[entrada]',
				'$datos[entrada_reloj]',
				'$datos[id_usuario]',
				'$datos[id_parametros_access]',
				'$datos[id_reloj]',
				'$datos[id_parametros]',
				'$datos[id_estado]'
			)";
		mysql_query($insert) or die(mysql_error());
	}

?>