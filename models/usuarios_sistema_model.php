<?php
function getUsuario_sistema($id){
	if(!(is_numeric($id))){
		trigger_error("Envió de Id que no es número", E_USER_WARNING);
		
		return FALSE;
	}else if(isset($id)){
		$query="SELECT * FROM `usuarios` WHERE usuarios.usuario_id='$id'";   
		$usuario=mysql_query($query) or die(mysql_error());
		
		return $usuario;
	}
		
}

function getUsuarios_sistema(){
	$query	= " SELECT * FROM `usuarios` 
				INNER JOIN tipo_usuario
				ON(usuarios.id_tipousuario=tipo_usuario.id_tipo_usuario)
				WHERE usuarios.id_estado=1";  
	$usuario= mysql_query($query) or die(mysql_error());
										
	return $usuario;
}

function updateUsuario_sistema($datos){
	if($datos['email_update']=='on'){
		$datos['email_update']=1;
	}else{
		$datos['email_update']=0;
	}
	
	mysql_query("UPDATE `usuarios` SET	
					usuario_nombre	= '$datos[usuario]',
					usuario_email	= '$datos[email]',
					email_update	= '$datos[email_update]',
					id_tipousuario	= '$datos[id_tipo]'
				WHERE 
					usuario_id		= '$datos[id]'") or die(mysql_error());
}


function changePass($datos){
	$pass = md5($datos['pass']);
	mysql_query("UPDATE `usuarios` SET	
					usuario_clave	= '$pass'
				WHERE 
					usuario_id		= '$datos[id]'") or die(mysql_error());
}

function deleteUsuario_sistema($id){
	mysql_query("UPDATE `usuarios` SET id_estado=0 WHERE usuario_id='$id'") or die(mysql_error());
}

function insertUsusario_sistema($datos){
	$pass = md5($datos['pass']);
	
	if($datos['email_update']=='on'){
		$datos['email_update']=1;
	}else{
		$datos['email_update']=0;
	}
	
	mysql_query("INSERT INTO `usuarios` (
				usuario_nombre,
				usuario_clave,
				usuario_email,
				id_tipousuario,
				email_update,
				id_estado)
			VALUES (
				'$datos[usuario]',
				'$pass',
				'$datos[email]',
				'$datos[id_tipo]',
				'$datos[email_update]',
				1)	
			") or die(mysql_error());

}
