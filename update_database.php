<?php
include_once("menu.php"); 
include_once($url['models_url']."userinfo_model.php");
include_once($url['models_url']."usuarios_model.php");

//---------------------------------------------------------------------- 
//---------------------------------------------------------------------- 
//                        Actualizo tabla usuarios
//----------------------------------------------------------------------             
//---------------------------------------------------------------------> 

$userinfo = new UserInfo();

$reg_userinfo = $userinfo->getRegistros();

do{
	
	$USERID		= odbc_result($reg_userinfo,"USERID");
	$PAGER		= odbc_result($reg_userinfo,"PAGER"); 
	
	if($PAGER != ''){
			
		$usuarios = getCuit($PAGER);
		$row_usuarios 	= mysql_fetch_assoc($usuarios); 
		$cantidad		= mysql_num_rows($usuarios);
		
		if($cantidad > 0){
			do{
				updateID($USERID, $PAGER);
			}while ($row_usuarios = mysql_fetch_array($usuarios));	
		}else{
			echo "no se encontro el cuil ".$PAGER.'<br>';	
		}
	}
	
}while (odbc_fetch_row($reg_userinfo));

?>