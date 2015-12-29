<?php
include_once('logs_model.php');


class UserInfo{
	
	
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
				USERINFO"; 
		$USERINFO	= odbc_exec($this->ODBC, $sql)or die(exit("Error en odbc_exec"));
		return $USERINFO;
	
	}
	
}


?>