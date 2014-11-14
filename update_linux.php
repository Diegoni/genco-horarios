<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php"); 
//---------------------------------------------------------------------- 
//---------------------------------------------------------------------- 
//                        Actualizo tabla  
//----------------------------------------------------------------------             
//---------------------------------------------------------------------> 
 
$query="SELECT * FROM `update`  
        ORDER BY id_update DESC";    
$update=mysql_query($query) or die(mysql_error()); 
$row_update = mysql_fetch_assoc($update); 
 
$ultima_fecha=$row_update['ultima_fecha']; 
 
 
$sql="SELECT * FROM CHECKINOUT"; 
$checkinout=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec")); 


$k=0;

mysql_query("TRUNCATE TABLE CHECKTIME ") or die(mysql_error());

do{
	$USERID=odbc_result($checkinout,"USERID");
	$CHECKTIME=odbc_result($checkinout,"CHECKTIME"); 
	$CHECKTYPE=odbc_result($checkinout,"CHECKTYPE");
	$marcada_formato=date("Y-m-d H:i:s", strtotime($CHECKTIME));
	
	mysql_query("INSERT INTO CHECKTIME  
	        (USERID, CHECKTIME, CHECKTYPE)  
	        VALUES  
	        ('$USERID','$marcada_formato','$CHECKTYPE')")  
	                    or die(mysql_error());
	$k=$k+1;
}while (odbc_fetch_row($checkinout));

$query="SELECT * FROM CHECKTIME 
		WHERE CHECKTIME>'$ultima_fecha' 
        ORDER BY CHECKTIME";    
$CHECKTIME=mysql_query($query) or die(mysql_error()); 
$row_CHECKTIME = mysql_fetch_assoc($CHECKTIME); 
 


$i=0; 
 
do{ 

 
if($row_CHECKTIME['USERID']!=0){
	 
$i=$i+1;
$hora=date('H:i', strtotime($row_CHECKTIME['CHECKTIME'])); 
 
//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA 
if($row_CHECKTIME['CHECKTYPE']=="I" || $row_CHECKTIME['CHECKTYPE']==1){ 
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
        ('$row_CHECKTIME[CHECKTIME]','$row_CHECKTIME[USERID]','$row_CHECKTIME[CHECKTYPE]','$id_parametros',1)")  
                    or die(mysql_error());
 
  $CHECKTIME2=$row_CHECKTIME['CHECKTIME'];
  $USERID2=$row_CHECKTIME['USERID'];                   
} 
}while ($row_CHECKTIME = mysql_fetch_array($CHECKTIME));                     
                     
                     

 
 
 
//GUARDO REGISTRO DE LA ULTIMA FECHA 
$fecha_hoy=date("Y-m-d H:m:s"); 

if(isset($USERID2)){
	mysql_query("INSERT INTO  `update` ( 
                `ultima_fecha` , 
                `ultimo_id` , 
                `fecha` , 
                `registros` 
                ) 
                VALUES ( 
                '$CHECKTIME2',   
                '$USERID2',   
                '$fecha_hoy',   
                '$i' 
                );")  
                    or die(mysql_error()); 
                    //('$CHECKTIME','$USERID','$fecha_hoy','$i')")  

	echo 'cantidad de registros '.$i.'<br>';
	echo 'fecha '.$CHECKTIME2.'<br>';
	echo "id ".$USERID2."<br>";
}else{ 
	echo "no hay registros nuevos";
}

?>