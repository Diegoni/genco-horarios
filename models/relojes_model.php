<?php
function deleteReloj($id){
	mysql_query("UPDATE `relojes` SET id_estado=0 WHERE id_reloj='$id'") or die(mysql_error());
}

function updateReloj($datos){
	mysql_query("UPDATE `relojes` SET	
				reloj	= '$datos[reloj]',
				ip		= '$datos[ip]',
				puerto	= '$datos[puerto]',
				color	= '$datos[color]'		
				WHERE id_reloj='$datos[id_reloj]'			
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
		
		$query="SELECT * FROM relojes WHERE relojes.id_estado=1 ORDER BY reloj";   
		$relojes=mysql_query($query) or die(mysql_error());
	}	
	return $relojes;
}

function getReloj($id){
	$query="SELECT * FROM `relojes` WHERE id_reloj='$id'";   
	$reloj=mysql_query($query) or die(mysql_error());

	return $reloj;
}

function insertReloj($datos){
	mysql_query("INSERT INTO `relojes`(
				reloj, 
				ip, 
				puerto, 
				color, 
				id_estado) 
					VALUES(
				'$datos[reloj]', 
				'$datos[ip]',
				'$datos[puerto]',
				'$datos[color]', 
				1)") 
				or die(mysql_error());
}

?>