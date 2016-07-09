<?php
include_once('logs_model.php');

class Checkinout{
	private $usuario	= "";
	private $clave		= "";
	private	$dsn		= "DSS";
	private	$ODBC;  
	
	
	function __construct()
	{
		$this->ODBC		= odbc_connect($this->dsn, $this->usuario, $this->clave);	
	}
	
/*---------------------------------------------------------------------------------  
					Trae los registros sin actualizar             
---------------------------------------------------------------------------------*/	
	
	function getRegistros($last_id){
		$sql	=	
			"SELECT 
				* 
			FROM 
				CHECKINOUT
			WHERE 
				ID > $last_id"; 
				
		$checkinout	= odbc_exec($this->ODBC,$sql)or die(exit("Error en odbc_exec"));
		
		return $checkinout;	
	}

/*---------------------------------------------------------------------------------  
					Inserta los registros           
---------------------------------------------------------------------------------*/	
	
	function insert($sql){
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
				ID,
				Actualizado
			) VALUES (
				'$datos[USERID]',
				'$datos[CHECKTIME]',
				'$datos[CHECKTYPE]',
				'$datos[VERIFYCODE]',
				'$datos[SENSORID]',
				'$datos[Memoinfo]',
				'$datos[WorkCode]',
				'$datos[sn]',
				'$datos[UserExtFmt]',
				'$datos[ID]',
				0
			)";
		mysql_query($insert) or die(mysql_error());
	}
/*---------------------------------------------------------------------------------  
					Estado del registros           
---------------------------------------------------------------------------------*/		
	
	function setUpdate($id_CHECKTIME, $actualizado){
		$update	=	
		"UPDATE 
			CHECKTIME
		SET
			Actualizado = $actualizado	
		WHERE 
			id_CHECKTIME = '$id_CHECKTIME'"; 
		mysql_query($update) or die(mysql_error());
	}
}
?>