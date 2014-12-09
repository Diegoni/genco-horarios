<?php   
session_start(); 
ini_set('max_execution_time', 600); //600 segundos = 10 minutos

include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."usuarios_sistema_model.php");
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."updates_model.php");
include_once("helpers.php");


function update_usuario_reloj($id_usuario){

	$relojes			= getRelojes();
	$row_reloj			= mysql_fetch_assoc($relojes);
	$cantidad_reloj		= mysql_num_rows($relojes);
		

$i=0;

do{
	$contador=0;
	$ip = $row_reloj['ip'];
	
		
	if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
	    $status = pingAddressWin($ip);
	} else {
	    $status = pingAddress($ip);
	}
		 
	if($status=='dead'){	
    	$update_reloj['reloj'.$i]		= $row_reloj['reloj'];
		$update_reloj['cantidad'.$i]	= 'sin conexión';
	}else {
		$usuarios			= getUsuario($id_usuario);
		$row_usuario		= mysql_fetch_assoc($usuarios);
		$cantidad_usuario	= mysql_num_rows($usuarios);
		
		$_GET['start_date']	= $row_usuario['fecha_ingreso'];
		$_GET['end_date']	= date('Y-m-d');
		
		
	    do{
			$datos=array(
						'ip'		=> $row_reloj['ip'],
						'id'		=> $row_usuario['id_usuario'],
						'id_u_reloj'=> $row_usuario['id_usuario_reloj'],
						'start_date'=> $_GET['start_date'],
						'end_date'	=> $_GET['end_date'],
						'id_reloj'	=> $row_reloj['id_reloj']);
			$contador = $contador + buscarMarcacion($datos);		
			
		}while($row_usuario=mysql_fetch_array($usuarios));
		
		if($contador>0){
			$update_reloj['reloj'.$i]		= $row_reloj['reloj'];
			$update_reloj['cantidad'.$i]	= $contador." marcaciones actualizadas"; 
		}else{
			$update_reloj['reloj'.$i]		= $row_reloj['reloj'];
			$update_reloj['cantidad'.$i]	= 'sin registros';
		}
	}
	
	$i=$i+1;
}while($row_reloj=mysql_fetch_array($relojes));






/*********************************************************************************
 * *******************************************************************************
 * 			Envio de correo con el resumen de la actualización 
 * *******************************************************************************
 ********************************************************************************/

	$titulo		= 'Resumen de actualización '.date('d-m-Y');
	
	$mensaje = '<b>Resumen</b>: <br>';

	for ($j=0; $j < $i; $j++) { 
		$mensaje	.= $update_reloj['reloj'.$j]." : ";
		$mensaje	.= $update_reloj['cantidad'.$j];
		$mensaje	.= "<br>";
	}
	
	$mensaje	.='Actualización: de usuario '.$row_usuario['apellido']." ".$row_usuario['nombre'];
		
	$usuarios_sistema	= getUsuarios_sistema();
	$row_usuario_sistema= mysql_fetch_assoc($usuarios_sistema);
	$cantidad_u_sistema	= mysql_num_rows($usuarios_sistema);
		
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$cabeceras .= 'From: '.$config['remitente'].' <'.$config['correo'].'>' . "\r\n";
	
	do{
		if($row_usuario_sistema['email_update']==1){
			$para	= $row_usuario_sistema['usuario_email'];
			
			mail($para, $titulo, $mensaje, $cabeceras);	
		}		
	}while($row_usuario_sistema=mysql_fetch_array($usuarios_sistema));
		
	return $mensaje;
	
}

?>