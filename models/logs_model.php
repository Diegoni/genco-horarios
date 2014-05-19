<?
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
?>