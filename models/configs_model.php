<?php


function getConfig(){
	$query="SELECT * FROM config ";   
	$config=mysql_query($query) or die(mysql_error());
	
	return $config;
}


function updateConfig($datos){
	mysql_query("UPDATE `config` SET	
				aplicar_redondeo='$datos[aplicar_redondeo]',
				mostrar_marcada='$datos[mostrar_marcada]',
				css='$datos[css]', 
				suma_dias='$datos[suma_dias]',
				fecha_actual='$datos[fecha_actual]'
				WHERE id_config='$datos[id_config]'			
				") or die(mysql_error());
}
?>