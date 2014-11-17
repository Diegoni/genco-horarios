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


if(	date('Y-m-d', strtotime($_GET['start_date'])) <= date('Y-m-d', strtotime($_GET['end_date']))){
	/*Si no hay fecha especifica buscamos los ultimos updates*/
	if(!isset($_GET['start_date']) && !isset($_GET['end_date'])){
		$relojes			= getRelojes();
		$row_reloj			= mysql_fetch_assoc($relojes);
		$cantidad_reloj		= mysql_num_rows($relojes);
		
		do{
			$reloj_update			= getlastUpdates($row_reloj['id_reloj']);
			$row_reloj_update		= mysql_fetch_assoc($reloj_update);
			$dates['star_'.$row_reloj['id_reloj']] = $row_reloj_update['end_date'];
		}while($row_reloj = mysql_fetch_array($relojes));
	}
	
	/*Consulto si es para un solo reloj o todos*/
	if($_GET['reloj']==0 || !isset($_GET['reloj'])){
		$relojes			= getRelojes();
		$row_reloj			= mysql_fetch_assoc($relojes);
		$cantidad_reloj		= mysql_num_rows($relojes);
	}else{
		$relojes			= getReloj($_GET['reloj']);
		$row_reloj			= mysql_fetch_assoc($relojes);
		$cantidad_reloj		= mysql_num_rows($relojes);
	}

	/*Consulto si la actualización es manual o automatica*/
	if(isset($_GET['tipo'])){
		$tipo				= $_GET['tipo'];
		$id_usuario			= $_SESSION['usuario_id'];	
		$_GET['start_date']	= date('Y-m-d', strtotime($_GET['start_date']));
		$_GET['end_date']	= date('Y-m-d', strtotime($_GET['end_date']));
	}else{
		$tipo		= 1;
		$id_usuario	= 0;
	}

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
		$usuarios			= getUsuarios();
		$row_usuario		= mysql_fetch_assoc($usuarios);
		$cantidad_usuario	= mysql_num_rows($usuarios);
		
		if(isset($dates['star_'.$row_reloj['id_reloj']])){
			$_GET['start_date']	= $dates['star_'.$row_reloj['id_reloj']];
			$_GET['end_date']	=  date('Y-m-d');
		}
		
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
			$datos=array(
						'start_date'		=> $_GET['start_date'],
						'end_date'			=> $_GET['end_date'],
						'id_reloj'			=> $row_reloj['id_reloj'],
						'cantidad_registros'=> $contador,	
						'fecha_update'		=> date('Y/m/d H:i:s'),
						'id_tipo'			=> $tipo,
						'id_usuario'		=> $id_usuario);
						
			insertUpdate($datos);
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
	
	if($tipo==1){
		$mensaje	.='Actualización: programada';
	}else{
		$mensaje	.='Actualización: manual<br>';
		$mensaje	.='Realizada por usuario: '.$_SESSION['usuario_nombre'];
	}
	
	$usuarios_sistema	= getUsuarios_sistema();
	$row_usuario_sistema= mysql_fetch_assoc($usuarios_sistema);
	$cantidad_u_sistema	= mysql_num_rows($usuarios_sistema);
		
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
	$cabeceras .= 'From: '.'Genco'.' <'.'root@gencosa.com.ar'.'>' . "\r\n";
	
	do{
		if($row_usuario_sistema['email_update']==1){
			$para	= $row_usuario_sistema['usuario_email'];
			
			mail($para, $titulo, $mensaje, $cabeceras);	
		}		
	}while($row_usuario_sistema=mysql_fetch_array($usuarios_sistema));
		
	echo $mensaje;

}else{
	//no se envian los parametros de las fechas o la fecha de inicio es mayor a la final
	$titulo	= 'Resumen de actualización '.date('d-m-Y');
	
	$mensaje = '<b>Resumen</b>: <br>';
	
	$mensaje .= 'La ejecución de la actualización no se ha ejecutado correctamente.<br>';
	$mensaje .= 'Controle los parametros de fecha enviados.<br>';
		
	$usuarios_sistema	= getUsuarios_sistema();
	$row_usuario_sistema= mysql_fetch_assoc($usuarios_sistema);
	$cantidad_u_sistema	= mysql_num_rows($usuarios_sistema);
		
	$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
	$cabeceras .= 'Content-Type: text/html; charset=UTF-8' . "\r\n";
	$cabeceras .= 'From: '.'Genco'.' <'.'root@gencosa.com.ar'.'>' . "\r\n";
	
	do{
		if($row_usuario_sistema['email_update']==1){
			$para	= $row_usuario_sistema['usuario_email'];
			
			$titulo	= "=?ISO-8859-1?B?".base64_encode($titulo)."=?=";
			
			mail($para, $titulo, $mensaje, $cabeceras);	
		}		
	}while($row_usuario_sistema=mysql_fetch_array($usuarios_sistema));
		
	echo $mensaje;
}


?>