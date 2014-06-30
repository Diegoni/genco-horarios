<?php
function insertArchivo($archivo){
	mysql_query("INSERT INTO `archivo` (
				nombre,
				tipo,
				extension,
				size)
			VALUES (
				'$archivo[archivo_nombre]',
				'$archivo[archivo_tipo]',
				'$archivo[archivo_extencion]',
				'$archivo[archivo_size]')	
			") or die(mysql_error());
	$id_archivo = mysql_insert_id();
	
	mysql_query("UPDATE `otrahora` SET	
				id_archivo='$id_archivo'	
				WHERE id_otrahora='$archivo[id_otrahora]'") or die(mysql_error());
}

function updateArchivo($archivo){
	mysql_query("UPDATE `archivo` SET	
				nombre='$archivo[archivo_nombre]',
				tipo='$archivo[archivo_tipo]',
				extension='$archivo[archivo_extencion]',
				size='$archivo[archivo_size]'
				WHERE id_archivo='$archivo[id_archivo]'") or die(mysql_error());
}

function getArchivo($id){
	$query="SELECT 	*
			FROM `archivo` 
			WHERE 
			id_archivo='$id'";   
	$archivo=mysql_query($query) or die(mysql_error());
	
	return $archivo;
}










?>