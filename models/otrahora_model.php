<?php
function getOtrahoras($fecha){
		$query="SELECT * 
			FROM otrahora 
			WHERE fecha = '$fecha'";   
		$otrahora=mysql_query($query) or die(mysql_error());
		
		return $otrahora;
}

function getOtrahora($id=NULL, $fecha_inicio=NULL, $fecha_final=NULL){
	if(isset($id, $fecha_inicio, $fecha_final)){
		$query="SELECT * 
		FROM otrahora 
		INNER JOIN tipootra ON(otrahora.id_tipootra=tipootra.id_tipootra)
		WHERE 
		fecha >= '$fecha_inicio' AND
		fecha <= '$fecha_final' AND
		id_usuario='$id'";   
		$otrahora=mysql_query($query) or die(mysql_error());
	}else if(isset($fecha_inicio, $fecha_final)){
		$query="SELECT * 
		FROM otrahora 
		WHERE 
		fecha >= '$fecha_inicio' AND
		fecha <= '$fecha_final'";   
		$otrahora=mysql_query($query) or die(mysql_error());
	}else if(isset($id, $fecha_inicio)){
		$query="SELECT * 
		FROM otrahora 
		INNER JOIN tipootra ON(otrahora.id_tipootra=tipootra.id_tipootra)
		INNER JOIN nota ON(otrahora.id_nota=nota.id_nota)
		WHERE fecha = '$fecha_inicio' 
		AND otrahora.id_usuario='$id'";   
		$otrahora=mysql_query($query) or die(mysql_error());
	}
	
	return $otrahora;
}

function updateOtrahora($id_tipootra, $horas,	$fecha, $nota, $id_nota, $id_otrahora){
	mysql_query("UPDATE `otrahora` SET 
						id_tipootra='$id_tipootra',
						horas = '$horas',
						fecha = '$fecha'
						WHERE id_otrahora='$id_otrahora'
						") or die(mysql_error());
						
	
	mysql_query("UPDATE `nota` SET 
						nota='$nota'
						WHERE id_nota='$id_nota'
						") or die(mysql_error());
}

function insertOtrahora($id, $id_tipootra, $nota, $horas, $fecha){
	mysql_query("INSERT INTO `nota` 
				(nota) 
				VALUES ('$nota')") or die(mysql_error());
				$id_nota=mysql_insert_id();
				
	mysql_query("INSERT INTO `otrahora` (id_usuario, id_tipootra, id_nota, horas, fecha) 
				VALUES ('$id', '$id_tipootra', '$id_nota', '$horas', '$fecha')") or die(mysql_error());				
}

function getTipootra(){
	$query="SELECT * FROM tipootra ORDER BY tipootra.id_tipootra";   
	$tipootra=mysql_query($query) or die(mysql_error());
	
	return $tipootra;
}
?>