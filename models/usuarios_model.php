<?php
function getUsuario($id){
	if(!(is_numeric($id))){
		trigger_error("Envió de Id que no es número", E_USER_WARNING);
		
		return FALSE;
	}else{
		$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.dni as dni,
				usuario.cuil as cuil,
				usuario.fecha_ingreso as fecha_ingreso,
    			usuario.foto_nombre as foto_nombre,
				departamento.nombre as departamento,
				convenio.semana as semana,				
				convenio.sabado as sabado,	
				convenio.id_convenio as id_convenio,			
				convenio.salida_sabado as salida_sabado				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		INNER JOIN
		convenio on(usuario.id_convenio=convenio.id_convenio)
		WHERE id_usuario='$id'";   
		$usuario=mysql_query($query) or die(mysql_error());
		
		return $usuario;
	}	
}

function getUsuarios($dato=NULL, $campo=NULL){
	if($dato=='all'){
		$query="SELECT 	usuario.id_usuario,
					usuario.usuario as usuario,
					usuario.legajo as legajo,
					usuario.id_estado as id_estado,
					departamento.nombre as departamento,
					convenio.semana as semana,								
					convenio.sabado as sabado,								
					convenio.salida_sabado as salida_sabado		
			FROM `usuario` 
			INNER JOIN departamento
			ON(usuario.id_departamento=departamento.id_departamento)
			INNER JOIN convenio 
			ON(usuario.id_convenio=convenio.id_convenio)
			ORDER BY usuario.usuario";  
		$usuario=mysql_query($query) or die(mysql_error());
	}else if(isset($dato, $campo)){
		$query="SELECT 	usuario.id_usuario,
					usuario.usuario as usuario,
					usuario.legajo as legajo,
					usuario.id_estado as id_estado,
					departamento.nombre as departamento
			FROM `usuario` INNER JOIN departamento
			ON (usuario.id_departamento=departamento.id_departamento)
			WHERE 
			usuario.$campo like '$dato' 
			ORDER BY usuario.usuario";   
		$usuario=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT 	usuario.id_usuario,
					usuario.usuario as usuario,
					usuario.legajo as legajo,
					usuario.id_estado as id_estado,
					usuario.fecha_ingreso as fecha_ingreso,
					usuario.id_convenio as id_convenio,
					departamento.nombre as departamento,
					convenio.semana as semana,								
					convenio.sabado as sabado,								
					convenio.salida_sabado as salida_sabado		
			FROM `usuario` 
			INNER JOIN departamento
			ON(usuario.id_departamento=departamento.id_departamento)
			INNER JOIN convenio 
			ON(usuario.id_convenio=convenio.id_convenio)
			WHERE 
			usuario.id_estado=1
			ORDER BY usuario.usuario";  
		$usuario=mysql_query($query) or die(mysql_error());
	}
										
	return $usuario;
}

function updateUsuario($datos){
	if(is_array($datos)){
		$cuil=$datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
		$fecha=date( "Y-m-d", strtotime($datos['fecha_ingreso']));
	
		mysql_query("UPDATE `usuario` SET	
								usuario='$datos[usuario]',
								nombre='$datos[nombre]',
								apellido='$datos[apellido]',
								dni='$datos[dni]',
								cuil='$cuil',
								id_estado='$datos[estado]',
								id_empresa='$datos[empresa]',
								id_departamento='$datos[departamento]',
								id_convenio='$datos[convenio]',
								fecha_ingreso='$fecha',
								legajo='$datos[legajo]'	
								WHERE id_usuario='$datos[id]'") or die(mysql_error());
	}else{
		trigger_error("No se envió un array en updateUsuario", E_USER_WARNING);
	}	

}

function deleteUsuario($id){
	mysql_query("UPDATE `usuario` SET id_estado=0 WHERE id_usuario='$id'") or die(mysql_error());
}

function insertUsusario($datos){
	$query="SELECT * FROM `usuario` ORDER BY id_usuario DESC";   
	$idusuario=mysql_query($query) or die(mysql_error());
	$row_idusuario = mysql_fetch_assoc($idusuario);

	$ultimoid=$row_idusuario['id_usuario'];
	$ultimoid=$ultimoid+1;
	$estado=1;
	
	$fecha=date( "Y-m-d", strtotime($datos['fecha_ingreso']));

	$cuil=$datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
	mysql_query("INSERT INTO `usuario` (
				id_usuario,
				usuario,
				legajo,
				nombre,
				apellido,
				dni,
				cuil,
				id_empresa,
				id_departamento,
				id_convenio,
				fecha_ingreso,
				id_estado)
			VALUES (
				'$ultimoid',
				'$datos[usuario]',
				'$datos[legajo]',
				'$datos[nombre]',
				'$datos[apellido]',
				'$datos[dni]',
				'$cuil',
				'$datos[empresa]',
				'$datos[departamento]',
				'$datos[convenio]',
				'$fecha',
				'$estado')	
			") or die(mysql_error());

}










?>