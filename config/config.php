<?php		
	date_default_timezone_set('America/Argentina/Mendoza');
	
	
	$url=array(
		'models_url'			=> "models/",
		'librerias_url'			=> "librerias/",
		'imagenes_perfil_url'	=> "imagenes/perfil/",
		'arhivo_otra_hora'		=> "imagenes/otra_hora/",
		'iconos_url'			=> "imagenes/iconos/",
		'imagenes'				=> "imagenes/",
		'return'				=> "login/acceso.php"
	);
	
		
	$query		= "SELECT * FROM config ";   
	$configs	= mysql_query($query) or die(mysql_error());
	$row_config	= mysql_fetch_assoc($configs);
	do{
		$config=array(
			'aplicar_redondeo'	=> $row_config['aplicar_redondeo'],
			'mostrar_marcada'	=> $row_config['mostrar_marcada'],
			'css'				=> $row_config['css'],
			'logo'				=> $row_config['logo'],
			'firma'				=> $row_config['firma'],
			'title'				=> $row_config['title'],
			'fecha_actual'		=> $row_config['fecha_actual'],
			'suma_dias'			=> $row_config['suma_dias'],
			'marcaciones_x_hoja'=> $row_config['marcaciones_x_hoja'],
			'remitente'			=> $row_config['remitente'],
			'correo'			=> $row_config['correo']
		);
	}while($row_config=mysql_fetch_array($configs));	
?>