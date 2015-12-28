<?php
function updateFoto($foto){
	$error=1;	
	
	if(!(is_array($foto))){
		trigger_error("No se envió un array en updateFoto", E_USER_WARNING);
		$error=0;		
	}else if(!(	$foto['foto_tipo']=='image/png' || 
				$foto['foto_tipo']=='image/gif' ||
				$foto['foto_tipo']=='image/jpg' ||
				$foto['foto_tipo']=='image/jpeg')) {
		trigger_error("Formato de foto no valido", E_USER_ERROR);
		$error=0;		
	}
	
	if($error==1){
		mysql_query("UPDATE `usuario` SET	
					foto_nombre	= '$foto[foto_nombre]',
					foto_tipo	= '$foto[foto_tipo]',
					foto_size	= '$foto[foto_size]'
					WHERE 
					id_usuario	= '$foto[id_usuario]'") or die(mysql_error());
			
	}
}

function updateFotologo($foto){
	$error=1;	
	
	if(!(is_array($foto))){
		trigger_error("No se envió un array en updateFoto", E_USER_WARNING);
		$error=0;		
	}else if(!(	$foto['foto_tipo']=='image/png' || 
				$foto['foto_tipo']=='image/gif' ||
				$foto['foto_tipo']=='image/jpg' ||
				$foto['foto_tipo']=='image/jpeg')) {
		trigger_error("Formato de foto no valido", E_USER_ERROR);
		$error=0;		
	}
	$logo='imagenes/'.$foto['foto_nombre'];
	
	if($error==1){
		$update = "UPDATE 
						`config` 
					SET	
						logo = '$logo'
					WHERE 
						id_config = '$foto[id_config]'";	
		
		mysql_query($update) or die(mysql_error());
			
	}
}


function updateFirma($foto){
	$error=1;	
	
	if(!(is_array($foto))){
		trigger_error("No se envió un array en updateFoto", E_USER_WARNING);
		$error=0;		
	}else if(!(	$foto['foto_tipo']=='image/png' || 
				$foto['foto_tipo']=='image/gif' ||
				$foto['foto_tipo']=='image/jpg' ||
				$foto['foto_tipo']=='image/jpeg')) {
		trigger_error("Formato de foto no valido", E_USER_ERROR);
		$error=0;		
	}
	$logo='imagenes/'.$foto['foto_nombre'];
	
	if($error==1){
		$update = "UPDATE 
						`config` 
					SET	
						firma = '$logo'
					WHERE 
						id_config = '$foto[id_config]'";	
		
		mysql_query($update) or die(mysql_error());
			
	}
}
?>