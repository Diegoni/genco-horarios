<?php
include_once('logs_model.php');

function getConfig(){
	$query	= "SELECT * FROM config ";   
	$config	= mysql_query($query) or die(mysql_error());
	
	return $config;
}


function updateConfig($datos){
	$update = "UPDATE 
					`config` 
				SET	
					aplicar_redondeo	= '$datos[aplicar_redondeo]',
					mostrar_marcada		= '$datos[mostrar_marcada]',
					css					= '$datos[css]', 
					suma_dias			= '$datos[suma_dias]',
					marcaciones_x_hoja	= '$datos[marcaciones_x_hoja]',
					fecha_actual		= '$datos[fecha_actual]',
					remitente			= '$datos[remitente]',
					correo				= '$datos[correo]'
				WHERE 
					id_config			= '$datos[id_config]'			
				";
	mysql_query($update) or die(mysql_error());
				
	$datos = array(
		'tabla'		=> 'config', 
		'id_tabla'	=> $datos['id_config'], 
		'id_accion'	=> 2 
	);
			
	insertLog($datos);
}
?>