<?php
function getTipos(){
	$query="SELECT * FROM `tipo`";   
	$tipo=mysql_query($query) or die(mysql_error());
	
	return $tipo;
}

?>