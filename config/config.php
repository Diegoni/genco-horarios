<?php		//local a phpmyadmin
	$url=array(
		'models_url'			=> "models/",
		'librerias_url'			=> "librerias/",
		'imagenes_perfil_url'	=> "imagenes/perfil/",
		'arhivo_otra_hora'		=> "imagenes/otra_hora/",
		'iconos_url'			=> "imagenes/iconos/",
		'imagenes'				=> "imagenes/"
	);
	
		
	$query="SELECT * FROM config ";   
	$configs=mysql_query($query) or die(mysql_error());
	$row_config = mysql_fetch_assoc($configs);
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
			'marcaciones_x_hoja'=> $row_config['marcaciones_x_hoja']
		);
	}while($row_config=mysql_fetch_array($configs));
	
	$texto	=array(
				'baja'	=> 'baja',
				'alta'	=> 'activa');
	
	
	$botones=array(
			'Alta'		=> "<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class='icon-plus-sign-alt'></i> Nuevo</a>",
			'Imprimir'	=> "<a href='javascript:imprSelec('muestra')' class='btn btn-default'><i class='icon-print'></i> Imprimir</a>",
			'Excel'		=> "<button class='btn btn-default' onclick='tableToExcel('example', 'W3C Example Table')'><i class='icon-download-alt'></i> Excel</button>"
	);   
	
	function button_edit($datos){
		$button ="<A 
					class='btn btn-primary' 
					title='Editar registro' 
					HREF='".$datos['href']."?id=".$datos['id']."&action=".$datos['action']."'>
					<i class='icon-edit'></i>
				  </A>";
				
		return $button;
	}
	
	function button_delete($datos){
		$button = "<A 
					class='btn btn-danger ".$datos['delete']."'  
					title='Dar de baja' 
					HREF='".$datos['href']."?id=".$datos['id']."&action=".$datos['action']."'>
					<i class='icon-minus-sign'></i>
					</A>";
				   
		return $button;
	}
	
?>