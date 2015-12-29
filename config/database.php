<?php
//local a phpmyadmin
	$username	= "root";
	$password	= "bluepill";
	$database	= "controlfinal4";
	$url		= "localhost";
	mysql_connect($url, $username, $password);
	@mysql_select_db($database) or die( "No pude conectarme a la base de datos");
	mysql_query("SET NAMES 'utf8'");


		
		
		//archivo
		//$mdbFilename="D:\Genco\attBackup";
		//$mdbFilename="C:\xampp2\htdocs\genco-horarios\db\att2000_GENCO";
		
		//$ODBC = odbc_connect("Driver={Microsoft Access Driver (*.mdb)};Dbq=$mdbFilename", $user, $password);
		
		//if (!$ODBC){
		//	exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");
		//}	
		/*
		// consulta SQL a nuestra tabla "usuarios" que se encuentra en la base de datos "db.mdb"
		$sql="Select * from USERINFO";
		
		// generamos la tabla mediante odbc_result_all(); utilizando borde 1
		$result=odbc_exec($cid,$sql)or die(exit("Error en odbc_exec"));
		print odbc_result_all($result,"border=1");
*/
?>