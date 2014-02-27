<?
if(isset($_GET['actualizar'])&& $bandera==1){
$sql="SELECT * 
		FROM CHECKINOUT 
		WHERE (((CHECKINOUT.CHECKTIME)>#$fecha_americana# AND (CHECKINOUT.CHECKTIME)<#$fecha_access2#));";
$checkinout=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));

do{
$USERID=odbc_result($checkinout,"USERID");
$CHECKTIME=odbc_result($checkinout,"CHECKTIME");
$CHECKTYPE=odbc_result($checkinout,"CHECKTYPE");

$hora=date('H', strtotime("$CHECKTIME"));
if($CHECKTYPE=="I"){
	$query="SELECT * FROM `parametros` 
			WHERE DATE_FORMAT(inicio, '%H:%m:%s')<'$hora' 
			AND DATE_FORMAT(final, '%H:%m:%s')>'$hora'
			AND id_tipo=1";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);
}else{
	$query="SELECT * FROM `parametros` 
			WHERE DATE_FORMAT(inicio, '%H:%m:%s')<'$hora' 
			AND DATE_FORMAT(final, '%H:%m:%s')>'$hora'
			AND id_tipo=2";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);
}
mysql_query("INSERT INTO marcada 
						(entrada, id_usuario,id_parametros,id_estado) 
						VALUES 
						('$CHECKTIME','$USERID','$row_parametros[id_parametros]',1)") 
						or die(mysql_error());

}while (odbc_fetch_row($checkinout));
$bandera=actualizar ($fecha_americana,$fecha_access2);
}
?>