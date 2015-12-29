<?php
include_once('logs_model.php');


class Checkinout{
	
	
	private $usuario	= "";
	private $clave		= "";
	private	$dsn		= "Genco";
	private	$ODBC;  
	
	
	function __construct()
	{
		$this->ODBC		= odbc_connect($this->dsn, $this->usuario, $this->clave);	
	}
	
	
	function getRegistros(){
	
		$sql	=	
			"SELECT 
				* 
			FROM 
				CHECKINOUT
			WHERE 
				Actualizado = FALSE"; 
		$checkinout	= odbc_exec($this->ODBC,$sql)or die(exit("Error en odbc_exec"));
		
		return $checkinout;	
	}
	
	function setActualizado(){
		$sql	=	
		"UPDATE 
			CHECKINOUT
		SET
			Actualizado = TRUE	
		WHERE 
			Actualizado = FALSE"; 
		$checkinout	= odbc_exec($this->ODBC,$sql)or die(exit("Error en UPDATE")); 
	}
	
	
	function insert($datos){
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
				'$datos[USERID]',
				'$datos[CHECKTIME]',
				'$datos[CHECKTYPE]',
				'$datos[VERIFYCODE]',
				'$datos[SENSORID]',
				'$datos[Memoinfo]',
				'$datos[WorkCode]',
				'$datos[sn]',
				'$datos[UserExtFmt]',
				'$datos[Update]'
			)";
		mysql_query($insert) or die(mysql_error());
	}
	
	
	function test(){
		$sql	=	
		"UPDATE 
			CHECKINOUT
		SET
			Actualizado = FALSE	
		WHERE 
			Actualizado = TRUE"; 
		$checkinout	= odbc_exec($this->ODBC,$sql)or die(exit("Error en odbc_exec"));
		
		return $checkinout;
	} 
	
	
	function getActualizado(){
		$query	=
		"SELECT 
			* 
		FROM 
			CHECKTIME 
		WHERE 
			Actualizado = 0";    
		$CHECKTIME = mysql_query($query) or die(mysql_error());
		
		return $CHECKTIME;
	}
	
	
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