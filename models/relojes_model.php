<?php
function deleteReloj($id){
	mysql_query("UPDATE `departamento` SET id_estado=0 WHERE id_departamento='$id'") or die(mysql_error());
}

function updateReloj($departamento,$id){
	mysql_query("UPDATE `departamento` SET	
				departamento='$departamento',
				id_estado=1		
				WHERE id_departamento='$id'			
				") or die(mysql_error());
}

function getRelojes($dato=NULL, $campo=NULL){
	
	if(isset($dato, $campo)){
		$query="SELECT  *
				FROM `relojes` 
				WHERE 
				relojes.$campo like '$dato'";
		$relojes=mysql_query($query) or die(mysql_error());
	}else{
		
		$query="SELECT * FROM relojes WHERE relojes.delete=0 ORDER BY reloj";   
		$relojes=mysql_query($query) or die(mysql_error());
	}	
	return $relojes;
}

function getReloj($id){
	$query="SELECT * FROM `relojes` WHERE id_reloj='$id'";   
	$reloj=mysql_query($query) or die(mysql_error());

	return $reloj;
}

function insertReloj($departamento){
	$query="SELECT max(id_departamento) as max FROM `departamento`";
	$departamentos=mysql_query($query) or die(mysql_error());
	$row_departamento = mysql_fetch_assoc($departamentos);
	$max=$row_departamento['max']+1;
		
	mysql_query("INSERT INTO `departamento` 
							(id_departamento, departamento, id_estado) 
							VALUES 
							('$max', '$departamento', 1)") or die(mysql_error());
}

?>