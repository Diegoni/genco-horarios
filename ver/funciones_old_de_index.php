<?php
//Funcion para saber si se debe actualizar la pagina
function actualizar ($fecha_americana,$fecha_access2){
//conexion odbc		
		$dsn = "NWIND"; 
		$usuario = "";
		$clave="";
		$ODBC=odbc_connect($dsn, $usuario, $clave);
		if (!$ODBC){
		exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
		
		
// consulta la cantidad de registros para ese dia en la base de access
		$sql="SELECT count(*) as total, USERID 
		FROM CHECKINOUT 
		WHERE (((CHECKINOUT.CHECKTIME)>#$fecha_americana# AND (CHECKINOUT.CHECKTIME)<#$fecha_access2#))
		GROUP BY  CHECKINOUT.USERID
		ORDER BY USERID;"; 
		$contador=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));

$bandera=0;


		while (odbc_fetch_row($contador)){  
			$id=odbc_result($contador,"USERID");
			$cantidad_odbc=odbc_result($contador,"total");        

			$registro=getMarcacion($id, $fecha_americana);
			$row_registro = mysql_fetch_assoc($registro);
			$cantidad_mysql = mysql_num_rows($registro);
			 

			// comparamos la cantidad de registros
			if($cantidad_odbc<=$cantidad_mysql){
			}else{
			$bandera=1;
			}
		}
return $bandera;
}


//fecha con la que se trabaja, la actual o la seteada
if(isset($_GET['fecha'])){
	$fecha=$_GET['fecha'];
	$fecha_americana=date( "Y-m-d", strtotime($_GET['fecha']));
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}else{
	$fecha= date("d-m-Y");
	$fecha_americana=date("Y-m-d");
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}

//consulto si debo actualizar la pagina
//Descomentar esta linea para que tome el ODBC y la actualizacion
//$bandera=actualizar($fecha_americana,$fecha_access2);


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Actualizo registro
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['actualizar'])&& $bandera==1){
$bandera=1;

$update=getUpdates();
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
		$parametros=getParametros($hora, $tipo);
		$row_parametros = mysql_fetch_assoc($parametros);
		$cantidad=mysql_num_rows($parametros);

		//SI NO COINCIDE CON NINGUNO VA 0
		if($cantidad<0){
			$id_parametros=0;
		}else{
			$id_parametros=$row_parametros['id_parametros'];
		}

		//INGRESO EL REGISTRO
		insertMarcadaAccess($CHECKTIME, $USERID, $CHECKTYPE, $id_parametros);			
	}else{
		echo "No hay nuevos registros";
		$bandera=0;
	}								
					
}while (odbc_fetch_row($checkinout));

	if($bandera==1){
		//GUARDO REGISTRO DE LA ULTIMA FECHA
		$ultima_fecha=date( "Y-m-d H:m:s", strtotime($CHECKTIME));
		$fecha_hoy=date("Y-m-d H:m:s");

		insertUpdate($ultima_fecha, $USERID, $fecha_hoy, $i);
						
		echo "Los datos se han cargado correctamente";
	}
}
?>
			$id_parametros=0;
		}else{
			$id_parametros=$row_parametros['id_parametros'];
		}

		//INGRESO>