<?php
function getUsuario_sistema($id){
	if(!(is_numeric($id))){
		trigger_error("Envió de Id que no es número", E_USER_WARNING);
		
		return FALSE;
	}else if(isset($id)){
		$query="SELECT 	
				usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				usuario.nombre as nombre,
				usuario.apellido as apellido,
				usuario.dni as dni,
				usuario.cuil as cuil,
				usuario.fecha_ingreso as fecha_ingreso,
    			usuario.foto_nombre as foto_nombre,
    			usuario.id_departamento as id_departamento,
    			usuario.id_empresa as id_empresa,
    			empresa.empresa as empresa,
    			empresa.cuil as cuil_empresa,
				departamento.departamento as departamento,
				convenio.semana as semana,				
				convenio.sabado as sabado,	
				convenio.id_convenio as id_convenio,			
				convenio.salida_sabado as salida_sabado				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		INNER JOIN
		convenio on(usuario.id_convenio=convenio.id_convenio)
		INNER JOIN
		empresa on(usuario.id_empresa=empresa.id_empresa)
		WHERE id_usuario='$id'";   
		$usuario=mysql_query($query) or die(mysql_error());
		
		return $usuario;
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
    			empresa.empresa as empresa,
				departamento.departamento as departamento,
				convenio.semana as semana,				
				convenio.sabado as sabado,	
				convenio.id_convenio as id_convenio,			
				convenio.salida_sabado as salida_sabado				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		INNER JOIN
		convenio on(usuario.id_convenio=convenio.id_convenio)
		INNER JOIN
		empresa on(usuario.id_empresa=empresa.id_empresa)
		WHERE usuario.id_estado=1";   
		$usuario=mysql_query($query) or die(mysql_error());
		
		return $usuario;
	}
}

function getUsuarios_sistema($dato=NULL, $campo=NULL){
	$query="SELECT * FROM `usuarios` WHERE usuarios.delete=0";  
	$usuario=mysql_query($query) or die(mysql_error());
										
	return $usuario;
}

function updateUsuario_sistema($datos){
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

function deleteUsuario_sistema($id){
	mysql_query("UPDATE `usuario` SET id_estado=0 WHERE id_usuario='$id'") or die(mysql_error());
}

function insertUsusario_sistema($datos){
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
