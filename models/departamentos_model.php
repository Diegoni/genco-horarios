<?php
function deleteDepartamento($id){
	mysql_query("UPDATE `departamento` SET id_estado=0 WHERE id_departamento='$id'") or die(mysql_error());
}

function updateDepartamento($departamento,$id){
	mysql_query("UPDATE `departamento` SET	
				departamento='$departamento',
				id_estado=1		
				WHERE id_departamento='$id'			
				") or die(mysql_error());
}

function getDepartamentos($dato=NULL, $campo=NULL){
	if(isset($dato, $campo)){
		$query="SELECT  *
				FROM `departamento` 
				WHERE 
				departamento.$campo like '$dato'";
		$departamento=mysql_query($query) or die(mysql_error());
	}else{
		$query="SELECT * FROM departamento WHERE id_estado=1 ORDER BY departamento";   
		$departamento=mysql_query($query) or die(mysql_error());
	}	
	return $departamento;
}

function getDepartamento($id){
	$query="SELECT * FROM `departamento` WHERE id_departamento='$id'";   
	$departamento=mysql_query($query) or die(mysql_error());

	return $departamento;
}

function insertDepartamento($departamento){
	$query="SELECT max(id_departamento) as max FROM `departamento`";
	$departamentos=mysql_query($query) or die(mysql_error());
	$row_departamento = mysql_fetch_assoc($departamentos);
	$max=$row_departamento['max']+1;
		
	mysql_query("INSERT INTO `departamento` 
							(id_departamento, nombre, id_estado) 
							VALUES 
							('$max', '$departamento', 1)") or die(mysql_error());
}

?>