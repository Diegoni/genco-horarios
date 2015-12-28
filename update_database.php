<?php
include_once("menu.php"); 

//---------------------------------------------------------------------- 
//---------------------------------------------------------------------- 
//                        Actualizo tabla usuarios
//----------------------------------------------------------------------             
//---------------------------------------------------------------------> 

$sql	=	
	"SELECT 
		* 
	FROM 
		USERINFO"; 
$checkinout	= odbc_exec($ODBC, $sql)or die(exit("Error en odbc_exec"));

do{
	
	$USERID		= odbc_result($checkinout,"USERID");
	$PAGER		= odbc_result($checkinout,"PAGER"); 
	
	if($PAGER != ''){
			
		$query	=
			"SELECT 
				* 
			FROM 
				usuario 
			WHERE 
				cuil like '$PAGER'";    
		
		$usuarios 		= mysql_query($query) or die(mysql_error()); 
		$row_usuarios 	= mysql_fetch_assoc($usuarios); 
		$cantidad		= mysql_num_rows($usuarios);
		
		if($cantidad > 0){
			do{
				$update	=	
					"UPDATE 
						usuario
					SET
						id_usuario_reloj = '$USERID'	
					WHERE 
						cuil = '$PAGER'"; 
				mysql_query($update) or die(mysql_error());
			}while ($row_usuarios = mysql_fetch_array($usuarios));	
		}else{
			echo "no se encontro el cuil ".$PAGER.'<br>';	
		}
	}
	
}while (odbc_fetch_row($checkinout));

?>