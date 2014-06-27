<?php
include_once("config/database.php");
include_once("config/config.php");
include_once($models_url."configs_model.php");
include_once($models_url."mensajes_model.php");  

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
		echo "<script>alert('Si no se van a aplicar los redondeos se deben “Mostar marcadas sin redondeos”')</script>";
		$mostrar_marcada=1;
	}
	
	$datos=array('id_config'=>$_GET['id'],
				'aplicar_redondeo'=>$aplicar_redondeo,
				'mostrar_marcada'=>$mostrar_marcada,
				'css'=>$_GET['css']);
	
	updateConfig($datos);
		
	
	$extra='config.php?update=1';
	header("location: $extra");
}