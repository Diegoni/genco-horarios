<?php
function getTurnos(){
	$query="SELECT * FROM `turno`";   
	$turno=mysql_query($query) or die(mysql_error());
	
	return $turno;
}

?>