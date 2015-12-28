<?php
function getOtrahoras($fecha){
		$query = 
			"SELECT 
				* 
			FROM 
				otrahora 
			WHERE 
				fecha = '$fecha' AND 
				eliminado = 0";   
		$otrahora = mysql_query($query) or die(mysql_error());
		
		return $otrahora;
}

function getOtrahora($id=NULL, $fecha_inicio=NULL, $fecha_final=NULL){
	if(isset($id, $fecha_inicio, $fecha_final)){
		$query = 
			"SELECT 
				* 
			FROM 
				otrahora 
			INNER JOIN 
				tipootra ON(otrahora.id_tipootra=tipootra.id_tipootra)
			WHERE 
				fecha >= '$fecha_inicio' AND
				fecha <= '$fecha_final' AND
				id_usuario ='$id' AND 
				eliminado = 0";   
		$otrahora = mysql_query($query) or die(mysql_error());
	}else if(isset($fecha_inicio, $fecha_final)){
		$query = 
			"SELECT 
				* 
			FROM 
				otrahora 
			WHERE 
				fecha >= '$fecha_inicio' AND
				fecha <= '$fecha_final' AND
				eliminado = 0";   
		$otrahora = mysql_query($query) or die(mysql_error());
	}else if(isset($id, $fecha_inicio)){
		$query =
			"SELECT 
				* 
			FROM 
				otrahora 
			INNER JOIN 
				tipootra ON(otrahora.id_tipootra=tipootra.id_tipootra)
			INNER JOIN 
				nota ON(otrahora.id_nota=nota.id_nota)
			WHERE 
				fecha = '$fecha_inicio' AND 
				otrahora.id_usuario='$id' AND
				eliminado = 0";      
		$otrahora = mysql_query($query) or die(mysql_error());
	}
	
	return $otrahora;
}

function updateOtrahora($id_tipootra, $horas,	$fecha, $nota, $id_nota, $id_otrahora){
	$update = "UPDATE 
					`otrahora` 
				SET	
					id_tipootra='$id_tipootra',
					horas = '$horas',
					fecha = '$fecha'
				WHERE 
					id_otrahora='$id_otrahora'";		
		
	mysql_query($update) or die(mysql_error());
	
	$update = "UPDATE 
					`nota` 
				SET 
					nota='$nota'
				WHERE 
					id_nota='$id_nota'";
	
	mysql_query($update) or die(mysql_error());
						
	$datos = array(
		'tabla'		=> 'otrahora', 
		'id_tabla'	=> $id_otrahora, 
		'id_accion'	=> 2
	);
			
	insertLog($datos);
}

function insertOtrahora($id, $id_tipootra, $nota, $horas, $fecha){
	$insert = "INSERT INTO `nota`(
					nota
				) VALUES (
					'$nota'
				)";		
		
	mysql_query($insert) or die(mysql_error());
	$id_nota = mysql_insert_id();
	
	$insert = "INSERT INTO `otrahora`(
					id_usuario, 
					id_tipootra, 
					id_nota, 
					horas, 
					fecha
				) VALUES (
					'$id', 
					'$id_tipootra', 
					'$id_nota', 
					'$horas', 
					'$fecha'
				)";
				
	mysql_query($insert) or die(mysql_error());
	
	$id = mysql_insert_id();
	
	$datos = array(
		'tabla'		=> 'otrahora', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 1 
	);
			
	insertLog($datos);				
}

function getTipootra(){
	$query = 
		"SELECT 
			* 
		FROM 
			tipootra 
		ORDER BY 
			tipootra.id_tipootra";   
	$tipootra = mysql_query($query) or die(mysql_error());
	
	return $tipootra;
}


function deleteOtrahora($id_otrahora){
	$sql = "UPDATE 
					`otrahora` 
				SET	
					eliminado = '1'
				WHERE 
					id_otrahora ='$id_otrahora'";	
	mysql_query($sql) or die(mysql_error());
						
	$datos = array(
		'tabla'		=> 'otrahora', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);			
	insertLog($datos);
}

?>