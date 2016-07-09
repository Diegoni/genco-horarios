<?php
include_once("menu.php"); 
ini_set('max_execution_time', 600); 
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."parametros_model.php");
include_once($url['models_url']."checkinout_model.php");
include_once($url['models_url']."updates_db_model.php");
include_once($url['models_url']."convenio_turnos_model.php");

/*---------------------------------------------------------------------------------  
					Turno de los convenios             
---------------------------------------------------------------------------------*/

$query_cturnos	= getConvenioturnos(); 
$row_cturnos	= mysql_fetch_assoc($query_cturnos); 
$can_cturnos	= mysql_num_rows($query_cturnos);

if($row_cturnos > 0){
	do{
		if($row_cturnos['sabado'] == 0){
			if($row_cturnos['id_turno'] == 1){
				$array_cturnos[$row_cturnos['id_convenio']][$row_cturnos['id_turno']] = array(
					'1'	=> date('H', strtotime($row_cturnos['entrada'])),
					'2'	=> date('H', strtotime($row_cturnos['salida'])),
				);	
			}else{
				$array_cturnos[$row_cturnos['id_convenio']][$row_cturnos['id_turno']] = array(
					'3'	=> date('H', strtotime($row_cturnos['entrada'])),
					'4'	=> date('H', strtotime($row_cturnos['salida'])),
				);
			}	
			
			
			
		}
	}while ($row_cturnos = mysql_fetch_array($query_cturnos));
}

/*---------------------------------------------------------------------------------  
					Ultimo ID actualizado             
---------------------------------------------------------------------------------*/

$checkinout		= new Checkinout();
$m_update_db	= new m_update_db();

/*---------------------------------------------------------------------------------  
					Preparo los datos            
---------------------------------------------------------------------------------*/

$query_update	= $m_update_db->getLastID(); 
$row_update		= mysql_fetch_assoc($query_update); 
$can_update		= mysql_num_rows($query_update);

if($row_update > 0){
	do{
		$ultimo = $row_update['ultimo'];
	}while ($row_update = mysql_fetch_array($query_update));
}

$query_relojes	= getRelojes(); 
$row_relojes	= mysql_fetch_assoc($query_relojes); 
$array_relojes 	= array();
$array_relojes_cantidades = array();
do{
	$array_relojes[$row_relojes['id_reloj_access']] = $row_relojes['id_reloj'];
	$array_relojes_cantidades[$row_relojes['id_reloj_access']] = 0;
}while ($row_relojes = mysql_fetch_array($query_relojes)); 

$modificados = array(
	'total'			=> 0,
	'modificados'	=> 0,
	'erroneos' 		=> 0 
);

$ids_actualizado = array();
$total_registros = 0;

$Update	= 0;

$query_usuarios ="
SELECT 
	id_usuario,
	id_usuario_reloj,
	nombre,
	apellido,
	usuario
FROM 
	usuario";
				
$usuarios			= mysql_query($query_usuarios) or die(mysql_error()); 
$row_usuarios		= mysql_fetch_assoc($usuarios); 
$cantidad_usuarios	= mysql_num_rows($usuarios);

do{
	$array_usuarios[$row_usuarios['id_usuario_reloj']] = array(
		'id_usuario_reloj'	=> $row_usuarios['id_usuario_reloj'],
		'id_usuario'		=> $row_usuarios['id_usuario'],
		'nombre'			=> $row_usuarios['nombre'],
		'apellido'			=> $row_usuarios['apellido'],
		'usuario'			=> $row_usuarios['usuario'],
	); 
}while ($row_usuarios = mysql_fetch_array($usuarios));

/*---------------------------------------------------------------------------------  
					Inserto los registros sin actualizar             
---------------------------------------------------------------------------------*/

$reg_checkinout	= $checkinout->getRegistros($ultimo);

$comienzo = 
"INSERT INTO CHECKTIME(
	USERID, 
	CHECKTIME, 
	CHECKTYPE,
	VERIFYCODE,
	SENSORID,
	Memoinfo,
	WorkCode,
	sn,
	UserExtFmt,
	ID
) VALUES ";

$conteo  = 0;
$querys  = 0;
$limite_sql = 700;

while (odbc_fetch_row($reg_checkinout)){
	if($conteo < $limite_sql){
		$datos = array(
			'USERID'		=> odbc_result($reg_checkinout,"USERID"),
			'CHECKTIME'		=> date("Y-m-d H:i:s", strtotime(odbc_result($reg_checkinout,"CHECKTIME"))),
			'CHECKTYPE'		=> odbc_result($reg_checkinout,"CHECKTYPE"),
			'VERIFYCODE'	=> odbc_result($reg_checkinout,"VERIFYCODE"),
			'SENSORID'		=> odbc_result($reg_checkinout,"SENSORID"),
			'Memoinfo'		=> odbc_result($reg_checkinout,"Memoinfo"),
			'WorkCode'		=> odbc_result($reg_checkinout,"WorkCode"),
			'sn'			=> odbc_result($reg_checkinout,"sn"),
			'UserExtFmt'	=> odbc_result($reg_checkinout,"UserExtFmt"),
			'ID'			=> odbc_result($reg_checkinout,"ID"),
		);
		
		$array_checkinout[] = $datos;
		
		$array_querys[$querys] .= "(";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"USERID")."', ";
		$array_querys[$querys] .= "'".date("Y-m-d H:i:s", strtotime(odbc_result($reg_checkinout,"CHECKTIME")))."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"CHECKTYPE")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"VERIFYCODE")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"SENSORID")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"Memoinfo")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"WorkCode")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"sn")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"UserExtFmt")."', ";
		$array_querys[$querys] .= "'".odbc_result($reg_checkinout,"ID")."' ";
		$array_querys[$querys] .= "), ";
		
		$total_registros	= $total_registros + 1;
		$ids_actualizado[]	= $datos['ID'];
		
		$conteo = $conteo + 1;
	}else{		
		$querys = $querys + 1;
		$conteo = 0;
	}
	
};


foreach ($array_querys as $sql) {
	$sql = substr($sql, 0, -2);
	$sql .= "; ";
	mysql_query($comienzo.$sql) or die(mysql_error());
}




/*---------------------------------------------------------------------------------  
					Actualizo la tabla update_db             
---------------------------------------------------------------------------------*/
$registro = array(
	'date_add'				=> date('Y-m-d H:i:s'),
	'cantidad_registros'	=> $total_registros,
	'last_id'				=> max($ids_actualizado),
);

$m_update_db->insert($registro);


$i=0; 


$comienzo = 
"INSERT INTO marcada (
	entrada, 
	entrada_reloj, 
	id_usuario,
	id_parametros_access,
	id_reloj, 
	id_parametros,
	id_db,
	id_estado
) VALUES ";

$errores = "";

$tolerancia = 2;
$conteo = 0;
$querys = 0;
$echo = '';

foreach ($array_checkinout as $row_CHECKTIME) {
	if($row_CHECKTIME['USERID'] != 0){
		$i		= $i+1;
				
		if($array_usuarios[$row_CHECKTIME['USERID']] != ''){
			
			if($conteo < $limite_sql){
				$hora		= date('H', strtotime($row_CHECKTIME['CHECKTIME']));
				$horaMin	= date('Hi', strtotime($row_CHECKTIME['CHECKTIME']));
				$diaMarcada	= date('Ymd', strtotime($row_CHECKTIME['CHECKTIME']));
				
				// Agrego el numero del parametro
				$id_parametros = 0;
				foreach ($array_cturnos[1] as $turno => $valores) {
					foreach ($valores as $idParametro => $horaConvenio) {
						if($hora > $horaConvenio - $tolerancia  && $hora <= $horaConvenio + $tolerancia){
							$id_parametros = $idParametro;
						}
					}
				} 
				
				$echo .= '<br> id_usuario_reloj: <b>'.$array_usuarios[$row_CHECKTIME['USERID']]['id_usuario_reloj'];
				$echo .= '</b> - id_usuario: '.$array_usuarios[$row_CHECKTIME['USERID']]['id_usuario'];
				$echo .= '- ID: <b>'.$row_CHECKTIME['ID'];
				$echo .= '</b>1- nombre: '.$array_usuarios[$row_CHECKTIME['USERID']]['apellido'].' '.$array_usuarios[$row_CHECKTIME['USERID']]['nombre'];
				$echo .= '- usuario: '.$array_usuarios[$row_CHECKTIME['USERID']]['usuario'];
				$echo .= '';	
				
				//INGRESO EL REGISTRO
				
				$id_usuario = $array_usuarios[$row_CHECKTIME['USERID']]['id_usuario'];
				$id_reloj	= $array_relojes[$row_CHECKTIME['SENSORID']];
				$array_relojes_cantidades[$row_CHECKTIME['SENSORID']] = $array_relojes_cantidades[$row_CHECKTIME['SENSORID']] + 1;
				
				if(!isset($arra_repetidos[$id_usuario.'-'.$diaMarcada.'-'.$id_parametros])){
				
					$array_sql[$querys] .= "("
					."'".$row_CHECKTIME['CHECKTIME']."',"
					."'".$row_CHECKTIME['CHECKTIME']."',"
					."'".$id_usuario."',"
					."'".$row_CHECKTIME['CHECKTYPE']."',"
					."'".$id_reloj."',"
					."'".$id_parametros."',"
					."'".$row_CHECKTIME['ID']."',"
					."'"."1'"
					."), ";
					
					$modificados['modificados'] = $modificados['modificados'] + 1; 
					$arra_repetidos[$id_usuario.'-'.$diaMarcada.'-'.$id_parametros] = $horaMin;
					$conteo = $conteo + 1;
				}else{
					$anterior = (int) $arra_repetidos[$id_usuario.'-'.$diaMarcada.'-'.$id_parametros];
					$horaMin  = (int) $horaMin;
					
					$diferencia = $horaMin - $anterior;
					
					echo $horaMin.' '.$array_usuarios[$row_CHECKTIME['USERID']]['usuario'].' '.$diferencia.'<br>';
					
					if($diferencia >= 20  || $diferencia < 0){
						if($diferencia >= 20){
							$id_parametros = $id_parametros + 1 ;
						}	
						
						$array_sql[$querys] .= "("
						."'".$row_CHECKTIME['CHECKTIME']."',"
						."'".$row_CHECKTIME['CHECKTIME']."',"
						."'".$id_usuario."',"
						."'".$row_CHECKTIME['CHECKTYPE']."',"
						."'".$id_reloj."',"
						."'".$id_parametros."',"
						."'".$row_CHECKTIME['ID']."',"
						."'"."1'"
						."), ";
						
						$modificados['modificados'] = $modificados['modificados'] + 1; 
						$arra_repetidos[$id_usuario.'-'.$diaMarcada.'-'.$id_parametros] = $horaMin;
						$conteo = $conteo + 1;
					}
				}
			}else{		
				$querys = $querys + 1;
				$conteo = 0;
			}
			
			
		}else{
			$modificados['erroneos'] = $modificados['erroneos'] + 1;
			$errores .= 'El USERID '.$row_CHECKTIME['USERID'].' no tiene usuario asociado'.chr(13).chr(10);	
		}               
	}
};

foreach ($array_sql as $sql) {
	$sql = substr($sql, 0, -2);
	$sql .= "; ";
	mysql_query($comienzo.$sql) or die(mysql_error());
}

if($errores != ""){
	write_log($errores);	
}

echo $echo;
?>