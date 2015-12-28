<?php
include_once('logs_model.php');

function getUsuario($id){
	if(!(is_numeric($id))){
		trigger_error("Envió de Id que no es número", E_USER_WARNING);
		
		return FALSE;
	}else if(isset($id)){
		$query = "SELECT 	
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
	    			usuario.id_usuario_reloj as id_usuario_reloj,
	    			empresa.empresa as empresa,
	    			empresa.cuil as cuil_empresa,
					departamento.departamento as departamento,
					convenio.semana as semana,				
					convenio.sabado as sabado,	
					convenio.id_convenio as id_convenio,			
					convenio.salida_sabado as salida_sabado				
				FROM 
					`usuario` 
				INNER JOIN
					departamento on(usuario.id_departamento=departamento.id_departamento)
				INNER JOIN
					convenio on(usuario.id_convenio=convenio.id_convenio)
				INNER JOIN
					empresa on(usuario.id_empresa=empresa.id_empresa)
				WHERE 
					id_usuario='$id'";   
		$usuario = mysql_query($query) or die(mysql_error());
		
		return $usuario;
	}else{
		$query ="SELECT 	
					usuario.id_usuario,
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
				FROM 
					`usuario` 
				INNER JOIN
					departamento on(usuario.id_departamento=departamento.id_departamento)
				INNER JOIN
					convenio on(usuario.id_convenio=convenio.id_convenio)
				INNER JOIN
					empresa on(usuario.id_empresa=empresa.id_empresa)
				WHERE 
					usuario.id_estado=1";   
		$usuario = mysql_query($query) or die(mysql_error());
		
		return $usuario;
	}
}

function getUsuarios($dato=NULL, $campo=NULL){
	if($dato=='all'){
		$query = "SELECT 	
					usuario.id_usuario,
					usuario.usuario as usuario,
					usuario.legajo as legajo,
					usuario.id_estado as id_estado,
					departamento.departamento as departamento,
					convenio.semana as semana,								
					convenio.sabado as sabado,								
					convenio.salida_sabado as salida_sabado		
				FROM 
					`usuario` 
				INNER JOIN 
					departamento ON(usuario.id_departamento=departamento.id_departamento)
				INNER JOIN 
					convenio ON(usuario.id_convenio=convenio.id_convenio)
				ORDER BY 
					usuario.usuario";  
		$usuario=mysql_query($query) or die(mysql_error());
	}else if(isset($dato, $campo)){
		$query = "SELECT 	
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
				FROM 
					`usuario` 
				INNER JOIN
					departamento on(usuario.id_departamento=departamento.id_departamento)
				INNER JOIN
					convenio on(usuario.id_convenio=convenio.id_convenio)
				INNER JOIN
					empresa on(usuario.id_empresa=empresa.id_empresa)
				WHERE 
					usuario.$campo like '$dato' AND 
					usuario.id_estado = 1
				ORDER BY 
					usuario.usuario";   
		$usuario = mysql_query($query) or die(mysql_error());
	}else{
		$query = "SELECT 	
					usuario.id_usuario,
					usuario.usuario as usuario,
					usuario.legajo as legajo,
					usuario.id_estado as id_estado,
					usuario.fecha_ingreso as fecha_ingreso,
					usuario.id_convenio as id_convenio,
					usuario.id_usuario_reloj as id_usuario_reloj,
					departamento.departamento as departamento,
					convenio.semana as semana,								
					convenio.sabado as sabado,								
					convenio.salida_sabado as salida_sabado		
				FROM 
					`usuario` 
				INNER JOIN 
					departamento ON(usuario.id_departamento=departamento.id_departamento)
				INNER JOIN 
					convenio ON(usuario.id_convenio=convenio.id_convenio)
				WHERE 
					usuario.id_estado = 1
				ORDER BY 
					usuario.usuario";  
		$usuario=mysql_query($query) or die(mysql_error());
	}
										
	return $usuario;
}

function updateUsuario($datos){
	if(is_array($datos)){
		$query = "SELECT 
					* 
				FROM 
					`usuario` 
				WHERE 
					id_usuario_reloj = '$datos[id_reloj]'";   
		$usuario		= mysql_query($query) or die(mysql_error());
		$row_usuario	= mysql_fetch_assoc($usuario);
		$número_filas	= mysql_num_rows($usuario);
		
		if($número_filas==1 && $row_usuario['id_usuario']==$datos['id']){
			
			$cuil	= $datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
			$fecha	= date( "Y-m-d", strtotime($datos['fecha_ingreso']));
			$update = "UPDATE 
							`usuario` 
						SET	
							id_usuario_reloj= '$datos[id_reloj]',
							usuario			= '$datos[usuario]',
							nombre			= '$datos[nombre]',
							apellido		= '$datos[apellido]',
							dni				= '$datos[dni]',
							cuil			= '$cuil',
							id_estado		= '$datos[estado]',
							id_empresa		= '$datos[empresa]',
							id_departamento	= '$datos[departamento]',
							id_convenio		= '$datos[convenio]',
							fecha_ingreso	= '$fecha',
							legajo			= '$datos[legajo]'	
						WHERE 
							id_usuario		= '$datos[id]'";
			mysql_query($update) or die(mysql_error());
			
			$datos = array(
				'tabla'		=> 'usuario', 
				'id_tabla'	=> $datos['id'], 
				'id_accion'	=> 2 
			);
				
			insertLog($datos);
			
			return TRUE;
		}else{
			return FALSE;
		}
	}else{
		trigger_error("No se envió un array en updateUsuario", E_USER_WARNING);
		return FALSE;
	}	

}

function deleteUsuario($id){
	$update = "UPDATE 
					`usuario` 
				SET 
					id_estado = 0 
				WHERE 
					id_usuario = '$id'";	
	
	mysql_query($update) or die(mysql_error());
	
	$datos = array(
		'tabla'		=> 'usuario', 
		'id_tabla'	=> $id, 
		'id_accion'	=> 3 
	);
			
	insertLog($datos);
}

function insertUsusario($datos){
	$query = "SELECT 
				* 
			FROM 
				`usuario` 
			WHERE 
				id_usuario_reloj = '$datos[id_reloj]'";   
	$usuario		= mysql_query($query) or die(mysql_error());
	$row_usuario	= mysql_fetch_assoc($usuario);
	$número_filas	= mysql_num_rows($usuario);
	
	if($número_filas==0){
		
		$query = 
			"SELECT 
				* 
			FROM 
				`usuario` 
			ORDER BY 
				id_usuario DESC";   
		$idusuario		= mysql_query($query) or die(mysql_error());
		$row_idusuario	= mysql_fetch_assoc($idusuario);
	
		$ultimoid		= $row_idusuario['id_usuario'];
		$ultimoid		= $ultimoid+1;
		$estado			= 1;
		
		$fecha = date( "Y-m-d", strtotime($datos['fecha_ingreso']));
	
		$cuil = $datos['cuil1']."-".$datos['cuil2']."-".$datos['cuil3'];
		
		$insert = "INSERT INTO `usuario` (
					id_usuario_reloj,
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
					id_estado
				)VALUES (
					'$datos[id_reloj]',
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
					'$estado'
				)";
		mysql_query($insert) or die(mysql_error());
				
		//$id = mysql_insert_id();
		
		$datos=array(
				'tabla'		=> 'usuario', 
				'id_tabla'	=> $ultimoid, 
				'id_accion'	=> 1 );
				
		insertLog($datos);
		
		return $ultimoid;
	}else{
		return FALSE;
	}

}
?>