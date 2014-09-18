<?php
include_once("config/database.php");
include_once("config/config.php");
include_once($url['models_url']."configs_model.php");
include_once($url['models_url']."mensajes_model.php");  

if(isset($_GET['update'])){
	if(isset($_GET['aplicar_redondeo'])){
		$aplicar_redondeo=1;
	}else{
		$aplicar_redondeo=0;
	}
	
	if(isset($_GET['mostrar_marcada'])){
		$mostrar_marcada=1;
	}else{
		$mostrar_marcada=0;
	}
	
	if($mostrar_marcada==0 && $aplicar_redondeo==0){
		$bandera=1;
		$mostrar_marcada=1;
	}else{
		$bandera=0;
	}
	
	if(isset($_GET['fecha_actual'])){
		$fecha_actual=1;
	}else{
		$fecha_actual=0;
	}
	
	$datos=array(
				'id_config'			=> $_GET['id'],
				'aplicar_redondeo'	=> $aplicar_redondeo,
				'mostrar_marcada'	=> $mostrar_marcada,
				'fecha_actual'		=> $fecha_actual,
				'suma_dias'			=> $_GET['suma_dias'],
				'marcaciones_x_hoja'=> $_GET['marcaciones_x_hoja'],
				'css'				=> $_GET['css']);
	
	updateConfig($datos);
		
	
	$extra='config.php?update='.$bandera;
	header("location: $extra");
}