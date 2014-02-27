<?php include_once("menu.php"); 
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Actualizo tabla 
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$bandera=1;

$query="SELECT * FROM `update` 
		ORDER BY id_update DESC";   
$update=mysql_query($query) or die(mysql_error());
$row_update = mysql_fetch_assoc($update);

$fecha_americana=date( "Y-m-d H:m:s", strtotime($row_update['ultima_fecha']));


$sql="SELECT *			
		FROM CHECKINOUT 
		WHERE (CHECKINOUT.CHECKTIME)>#$fecha_americana# ORDER BY CHECKTIME";
$checkinout=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));


$i=0;



do{
$i=$i+1;

$USERID=odbc_result($checkinout,"USERID");
if($USERID!=0){

$CHECKTIME=odbc_result($checkinout,"CHECKTIME");
$CHECKTYPE=odbc_result($checkinout,"CHECKTYPE");

$hora=date('H:i', strtotime("$CHECKTIME"));

//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA
if($CHECKTYPE=="I"){
	$tipo=1;
}else{
	$tipo=2;
}
//BUSCO DENTRO DE PARAMETROS SI ES MAÑANA TARDE O NOCHE DEPENDIENDO DE LA HORA
$query="SELECT * FROM `parametros` 
		WHERE DATE_FORMAT(inicio, '%H:%m')<'$hora' 
		AND DATE_FORMAT(final, '%H:%m')>'$hora'
		AND id_tipo='$tipo'";   
$parametros=mysql_query($query) or die(mysql_error());
$row_parametros = mysql_fetch_assoc($parametros);
$cantidad=mysql_num_rows($parametros);

//SI NO COINCIDE CON NINGUNO VA 0
if($cantidad<0){
	$id_parametros=0;
}else{
	$id_parametros=$row_parametros['id_parametros'];
}

//INGRESO EL REGISTRO
mysql_query("INSERT INTO marcada 
					(entrada, id_usuario,id_parametros_access, id_parametros,id_estado) 
					VALUES 
					('$CHECKTIME','$USERID','$CHECKTYPE','$id_parametros',1)") 
					or die(mysql_error());
					
}else{
echo "No hay nuevos registros";
$bandera=0;
}					
					
					
}while (odbc_fetch_row($checkinout));

if($bandera==1){

//GUARDO REGISTRO DE LA ULTIMA FECHA
$ultima_fecha=date( "Y-m-d H:m:s", strtotime($CHECKTIME));
$fecha_hoy=date("Y-m-d H:m:s");

mysql_query("INSERT INTO  `update` (
				`ultima_fecha` ,
				`ultimo_id` ,
				`fecha` ,
				`registros`
				)
				VALUES (
				'$ultima_fecha',  
				'$USERID',  
				'$fecha_hoy',  
				'$i'
				);") 
					or die(mysql_error());
					//('$CHECKTIME','$USERID','$fecha_hoy','$i')") 
					
echo "Los datos se han cargado correctamente";
}

?>