<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php"); 
//---------------------------------------------------------------------- 
//---------------------------------------------------------------------- 
//                        Actualizo tabla  
//----------------------------------------------------------------------             
//---------------------------------------------------------------------> 

$sql	=	
	"UPDATE 
		CHECKINOUT
	SET
		Actualizado = FALSE	
	WHERE 
		Actualizado = TRUE"; 
$checkinout	= odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec")); 


$sql	=	
	"SELECT 
		* 
	FROM 
		`relojes`"; 
$query_relojes = mysql_query($sql) or die(mysql_error()); 
$row_relojes = mysql_fetch_assoc($query_relojes); 

$array_relojes = array();
$array_relojes_cantidades = array();
do{
	$array_relojes[$row_relojes['id_reloj_access']] = $row_relojes['id_reloj'];
	$array_relojes_cantidades[$row_relojes['id_reloj_access']] = 0;
}while ($row_relojes = mysql_fetch_array($query_relojes)); 

$sql	=	
	"SELECT 
		* 
	FROM 
		CHECKINOUT
	WHERE 
		Actualizado = FALSE"; 
$checkinout	= odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));

$k		= 0;
$Update	= 0;

do{
	
	$USERID		= odbc_result($checkinout,"USERID");
	$CHECKTIME	= odbc_result($checkinout,"CHECKTIME"); 
	$CHECKTYPE	= odbc_result($checkinout,"CHECKTYPE");
	$marcada_formato = date("Y-m-d H:i:s", strtotime($CHECKTIME));
	$VERIFYCODE	= odbc_result($checkinout,"VERIFYCODE");
	$SENSORID	= odbc_result($checkinout,"SENSORID");
	$Memoinfo	= odbc_result($checkinout,"Memoinfo");
	$WorkCode	= odbc_result($checkinout,"WorkCode");
	$sn			= odbc_result($checkinout,"sn");
	$UserExtFmt	= odbc_result($checkinout,"UserExtFmt");
	
	
	$insert = 
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
			Actualizado
		) VALUES (
			'$USERID',
			'$marcada_formato',
			'$CHECKTYPE',
			'$VERIFYCODE,',
			'$SENSORID',
			'$Memoinfo',
			'$WorkCode',
			'$sn',
			'$UserExtFmt',
			'$Update'
		)";
	mysql_query($insert) or die(mysql_error());
	$k	=	$k+1;
}while (odbc_fetch_row($checkinout));


$sql	=	
	"UPDATE 
		CHECKINOUT
	SET
		Actualizado = TRUE	
	WHERE 
		Actualizado = FALSE"; 
$checkinout	= odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec")); 



$query	=
	"SELECT 
		* 
	FROM 
		CHECKTIME 
	WHERE 
		Actualizado = 0";    
$CHECKTIME = mysql_query($query) or die(mysql_error()); 
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
		$query	=
			"SELECT 
				* 
			FROM 
				`parametros`  
			WHERE 
				DATE_FORMAT(inicio, '%H:%m')	< '$hora'  
		        AND DATE_FORMAT(final, '%H:%m')	> '$hora' 
		        AND id_tipo	= '$tipo'";    
		$parametros		= mysql_query($query) or die(mysql_error()); 
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
				
				$insert = 
					"INSERT INTO marcada (
						entrada, 
						entrada_reloj, 
						id_usuario,
						id_parametros_access,
						id_reloj, 
						id_parametros,
						id_estado
					) VALUES (
						'$row_CHECKTIME[CHECKTIME]',
						'$row_CHECKTIME[CHECKTIME]',
						'$row_usuarios[id_usuario]',
						'$row_CHECKTIME[CHECKTYPE]',
						'$id_reloj',
						'$id_parametros',
						1
					)";
				mysql_query($insert) or die(mysql_error());
				
				$update	=	
					"UPDATE 
						CHECKTIME
					SET
						Actualizado = 1	
					WHERE 
						id_CHECKTIME = '$row_CHECKTIME[id_CHECKTIME]'"; 
				mysql_query($update) or die(mysql_error());
				   
			}while ($row_usuarios = mysql_fetch_array($usuarios)); 
		}else{
			$update	=	
				"UPDATE 
					CHECKTIME
				SET
					Actualizado = 2	
				WHERE 
					id_CHECKTIME = '$row_CHECKTIME[id_CHECKTIME]'"; 
			mysql_query($update) or die(mysql_error());
			write_log('El USERID '.$row_CHECKTIME['USERID'].' no tiene usuario asociado');
		}               
	} 
}while ($row_CHECKTIME = mysql_fetch_array($CHECKTIME));                     
 
 
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