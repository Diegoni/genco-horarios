<?php		
	//debe ser de sistema no de usuario
	$usuario	= "";
	$clave		= "";

	//Nota: la conexion se debe hacer por sistema, en el caso de que falle probar por archivo
	//ODBC por sistema
	
	$dsn		= "Genco"; 
	$ODBC		= odbc_connect($dsn, $usuario, $clave);
	
?>