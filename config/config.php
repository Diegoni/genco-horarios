<?php		//local a phpmyadmin
	$models_url="models/";
	$librerias_url="librerias/";
	$imagenes_perfil_url="imagenes/perfil/";
	$arhivo_otra_hora="imagenes/otra_hora/";
	$iconos_url="imagenes/iconos/";
		
	$query="SELECT * FROM config ";   
	$config=mysql_query($query) or die(mysql_error());
	$row_config = mysql_fetch_assoc($config);
	do{
		$aplicar_redondeo=$row_config['aplicar_redondeo'];	
		$mostar_marcada=$row_config['mostrar_marcada'];
		$css=$row_config['css'];
		$logo=$row_config['logo'];
		$title=$row_config['title'];
	}while($row_config=mysql_fetch_array($config))
?>