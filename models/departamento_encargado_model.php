<?php
function deleteDepartamento_encargado($id){
	mysql_query("DELETE FROM departamento_encargado
				WHERE departamento_encargado.id_departamento='$id';") or die(mysql_error());
}

function updateDepartamento_encargado($departamento,$id){
	mysql_query("UPDATE `departamento` SET	
				departamento='$departamento',
				id_estado=1		
				WHERE id_departamento='$id'			
				") or die(mysql_error());
}

function getDepartamentos_encargado($datos=NULL){
	
	if(isset($datos)){
		$query="SELECT * FROM `departamento_encargado` 
				INNER JOIN departamento 
				ON(departamento.id_departamento=departamento_encargado.id_departamento) 
				INNER JOIN encargados 
				ON(encargados.id_encargado=departamento_encargado.id_encargado)
				WHERE 
				departamento_encargado.id_encargado		= '$datos[id_encargado]'
				departamento_encargado.id_departamento	= '$datos[id_departamento]'";
		$departamento=mysql_query($query) or die(mysql_error());
	}else{
		
		$query="SELECT * FROM `departamento_encargado` 
				INNER JOIN departamento 
				ON(departamento.id_departamento=departamento_encargado.id_departamento) 
				INNER JOIN encargados 
				ON(encargados.id_encargado=departamento_encargado.id_encargado)";   
		$departamento=mysql_query($query) or die(mysql_error());
	}	
	return $departamento;
}

function getDepartamento_encargado($id){
	$query="SELECT * FROM `departamento_encargado` 
			INNER JOIN departamento 
			ON(departamento.id_departamento=departamento_encargado.id_departamento) 
			INNER JOIN encargados 
			ON(encargados.id_encargado=departamento_encargado.id_encargado)
			WHERE departamento_encargado.id_departamento='$id'";   
	$departamento=mysql_query($query) or die(mysql_error());

	return $departamento;
}

function insertDepartamento_encargado($datos){
	mysql_query("INSERT INTO `departamento_encargado` 
							(id_encargado, id_departamento) 
							VALUES 
							('$datos[id_encargado]', '$datos[id_departamento]')") or die(mysql_error());
}

?>