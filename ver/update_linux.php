<?php  
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
 
$fecha_americana=date("Y-m-d H:m:s", strtotime($row_update['ultima_fecha'])); 
 
 
$sql="SELECT * FROM CHECKINOUT WHERE CHECKTIME='$fecha_americana'"; 
$checkinout=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec")); 


$k=0;
$table=array();
do{
$USERID=odbc_result($checkinout,"USERID");
$CHECKTIME=odbc_result($checkinout,"CHECKTIME"); 
$CHECKTYPE=odbc_result($checkinout,"CHECKTYPE");
$marcada_formato=date("Y-m-d H:m:s", strtotime($CHECKTIME));

$registro=array('CHECKTIME'=>$marcada_formato,
        'USERID'=>$USERID,
        'CHECKTYPE'=>$CHECKTYPE);
array_push($table, $registro);
$k=$k+1;

}while (odbc_fetch_row($checkinout));
asort($table); 


$i=0; 
 
foreach($table as $tabla){ 
if($tabla['CHECKTIME']>$fecha_americana){

$i=$i+1; 
if($tabla['USERID']!=0){ 
 
$hora=date('H:i', strtotime($tabla['CHECKTIME'])); 
 
//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA 
if($tabla['CHECKTYPE']=="I" || $tabla['CHECKTYPE']==1){ 
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
        ('$tabla[CHECKTIME]','$tabla[USERID]','$tabla[CHECKTYPE]','$id_parametros',1)")  
                    or die(mysql_error());
 
                     
} 
}                     
                     
                     
} 
 
 
 
//GUARDO REGISTRO DE LA ULTIMA FECHA 
$fecha_hoy=date("Y-m-d H:m:s"); 
 
mysql_query("INSERT INTO  `update` ( 
                `ultima_fecha` , 
                `ultimo_id` , 
                `fecha` , 
                `registros` 
                ) 
                VALUES ( 
                '$tabla[CHECKTIME]',   
                '$USERID',   
                '$fecha_hoy',   
                '$i' 
                );")  
                    or die(mysql_error()); 
                    //('$CHECKTIME','$USERID','$fecha_hoy','$i')")  
if($i>0){
echo 'cantidad de registros '.$i.'<br>';
echo 'fecha '.$tabla['CHECKTIME'].'<br>';
echo 'id '.$USERID.'<br>';
}else{
echo 'no hay registros nuevos';
}

?>