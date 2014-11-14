<?php
//url
$mensaje	= $_SERVER['REQUEST_URI'];

//paginas permitidas para usuarios restringidos
$index		= 'index.php';
$usuario	= 'usuario.php';


//control de acceso, si no hay session
if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
}else{
	//control de acceso, tipo de usuario
	if($_SESSION['id_tipousuario']==3){
		if(!(strpos($mensaje, $index) || strpos($mensaje, $usuario))){
			header("Location: login/acceso_prohibido.php");
		}
	}
}
?>