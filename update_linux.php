<?php
include_once("menu.php"); 
ini_set('max_execution_time', 600); 
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."parametros_model.php");
include_once($url['models_url']."checkinout_model.php");
//---------------------------------------------------------------------- 
//---------------------------------------------------------------------- 
//                        Actualizo tabla  
//----------------------------------------------------------------------             
//--------------------------------------------------------------------->

$checkinout = new Checkinout();

$checkinout->test();


$query_relojes	= getRelojes(); 
$row_relojes	= mysql_fetch_assoc($query_relojes); 
$array_relojes = array();
$array_relojes_cantidades = array();
do{
	$array_relojes[$row_relojes['id_reloj_access']] = $row_relojes['id_reloj'];
	$array_relojes_cantidades[$row_relojes['id_reloj_access']] = 0;
}while ($row_relojes = mysql_fetch_array($query_relojes)); 

$reg_checkinout	= $checkinout->getRegistros();
$modificados = array(
	'total'			=> 0,
	'modificados'	=> 0,
	'erroneos' 		=> 0 
);
$Update	= 0;

do{
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
		'Update'		=> $Update
	);
	
	$checkinout->insert($datos);
	$modificados['total']	=	$modificados['total'] + 1;
	
}while (odbc_fetch_row($reg_checkinout));

$checkinout->setActualizado();

$CHECKTIME = $checkinout->getActualizado();
$row_CHECKTIME = mysql_fetch_assoc($CHECKTIME); 
$i=0; 
 
do{ 
	if($row_CHECKTIME['USERID'] != 0){
			 
		$i		= $i+1;
		$hora	= date('H:i', strtotime($row_CHECKTIME['CHECKTIME'])); 
		 
		//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA 
		if($row_CHECKTIME['CHECKTYPE']=="I" || $row_CHECKTIME['CHECKTYPE']==1){ 
		    $tipo	= 1; 
		}else{ 
		    $tipo	= 2; 
		} 
		//BUSCO DENTRO DE PARAMETROS SI ES MAÑANA TARDE O NOCHE DEPENDIENDO DE LA HORA 
		$parametros		= getParametros($hora, $tipo); 
		$row_parametros = mysql_fetch_assoc($parametros); 
		$cantidad		= mysql_num_rows($parametros); 
		 
		//SI NO COINCIDE CON NINGUNO VA 0 
		if($cantidad < 0){ 
		    $id_parametros	= 0; 
		}else{ 
		    $id_parametros	= $row_parametros['id_parametros']; 
		} 
		
		$query_usuarios =
			"SELECT 
				id_usuario
			FROM 
				usuario
			WHERE 
				id_usuario_reloj = '$row_CHECKTIME[USERID]'";
				
		$usuarios			= mysql_query($query_usuarios) or die(mysql_error()); 
		$row_usuarios		= mysql_fetch_assoc($usuarios); 
		$cantidad_usuarios	= mysql_num_rows($usuarios);
		
		if($cantidad_usuarios > 0){
			do{
				//INGRESO EL REGISTRO
				
				$id_reloj = $array_relojes[$row_CHECKTIME['SENSORID']];
				$array_relojes_cantidades[$row_CHECKTIME['SENSORID']] = $array_relojes_cantidades[$row_CHECKTIME['SENSORID']] + 1;
				
				$datos = array(
					'entrada'				=> $row_CHECKTIME['CHECKTIME'],
					'entrada_reloj'			=> $row_CHECKTIME['CHECKTIME'],
					'id_usuario'			=> $row_usuarios['id_usuario'],
					'id_parametros_access'	=> $row_CHECKTIME['CHECKTYPE'],
					'id_reloj'				=> $id_reloj,
					//'id_parametros'			=> $id_parametros,
					'id_parametros'			=> $row_CHECKTIME['id_CHECKTIME'],
					'id_estado'				=> 1
				);		
				insertMarcadas($datos);
				
				$checkinout->setUpdate($row_CHECKTIME['id_CHECKTIME'], 1);
				$modificados['modificados'] = $modificados['modificados'] + 1; 
				   
			}while ($row_usuarios = mysql_fetch_array($usuarios)); 
		}else{
			$checkinout->setUpdate($row_CHECKTIME['id_CHECKTIME'], 2);
			$modificados['erroneos'] = $modificados['erroneos'] + 1;
			write_log('El USERID '.$row_CHECKTIME['USERID'].' no tiene usuario asociado');
		}               
	} 
}while ($row_CHECKTIME = mysql_fetch_array($CHECKTIME));  



foreach ($modificados as $key => $value) {
	echo $key.' '.$value.'<br>';
	
}                  
 
 
//GUARDO REGISTRO DE LA ULTIMA FECHA 
$fecha_hoy	= date("Y-m-d H:m:s"); 
$end_date	= date("Y-m-d");
foreach ($array_relojes_cantidades as $key => $value) {
	if($value > 0){
		$insert = 
		"INSERT INTO `update` (
			`start_date`, 
			`end_date`, 
			`id_reloj`, 
			`cantidad_registros`, 
			`fecha_update`, 
			`id_tipo`, 
			`id_usuario`
		) VALUES(
			'$end_date', 
			'$end_date', 
			$array_relojes[$key], 
			$value, 
			'$fecha_hoy', 
			2, 
			2
		)";
		mysql_query($insert) or die(mysql_error());
	}	
}
?>