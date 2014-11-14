<?php
function getLog($id){
	$query="SELECT * 
					FROM log_auditoria_marcada
					WHERE
					id_marcada='$id'";   
	$log=mysql_query($query) or die(mysql_error());
	
	return $log;
}

function getLogs(){
	$query="SELECT * FROM `log_auditoria_usuario` 
	ORDER BY id_log_usuario DESC";  
	$log=mysql_query($query) or die(mysql_error());
	
	return $log;
}

function insertLog($datos){
	session_start();
	$datos['fecha']		= date('Y-m-d H:i:s'); 
	$datos['id_usuario']= $_SESSION['usuario_id'];
		
	mysql_query("INSERT INTO 
					`logs_sistema`(
					tabla, 
					id_tabla, 
					id_accion, 
					fecha, 
					id_usuario) 
				VALUES(
					'$datos[tabla]', 
					'$datos[id_tabla]',
					'$datos[id_accion]',
					'$datos[fecha]',
					'$datos[id_usuario]')") or die(mysql_error());
}
?>