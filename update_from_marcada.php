<?php include_once("menu.php"); 
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Actualizo tabla 
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->


$query="SELECT * FROM `marcada_old` ORDER BY entrada";   
$marcada_old=mysql_query($query) or die(mysql_error());
$row_marcada_old = mysql_fetch_assoc($marcada_old);


do{
$USERID=$row_marcada_old['usuario_idusuario'];
$CHECKTIME=$row_marcada_old['entrada'];
$CHECKTYPE=$row_marcada_old['tipo'];

$hora=date('H:i', strtotime("$CHECKTIME"));

//CONTROLO QUE TIPO ES E=ENTRADA Y S=SALIDA
if($CHECKTYPE=="E"){
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
					
}while ($row_marcada_old = mysql_fetch_array($marcada_old));	



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


?>