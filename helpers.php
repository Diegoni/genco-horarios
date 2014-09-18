<?php
error_reporting(0);


include_once("config/database.php");
include_once($models_url."logs_model.php"); 	

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Funciones php
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
function devuelve_icono($extension, $iconos_url){
	if(	$extension=='.png' || 
		$extension=='.gif' ||
		$extension=='.jpg' ||
		$extension=='.jpeg'){
		$icono=$iconos_url."imagen.png";	
	}else if(	
		$extension=='.xls' || 
		$extension=='.xlsx'){
		$icono=$iconos_url."excel.png";	
	}else if(	
		$extension=='.doc' || 
		$extension=='.docx'){
		$icono=$iconos_url."word.png";	
	}else if(	
		$extension=='.ppt' || 
		$extension=='.pptx'){
		$icono=$iconos_url."ppt.png";	
	}else if(	
		$extension=='.pdf'){
		$icono=$iconos_url."ppt.png";
	}else if(	
		$extension=='.rar'){
		$icono=$iconos_url."rar.png";
	}else if(	
		$extension=='.zip'){
		$icono=$iconos_url."zip.png";
	}else{
		$icono=$iconos_url."guardar.png";
	}
	
	return $icono;
	
}


function devuelveArrayFechasEntreOtrasDos($fechaInicio, $fechaFin){
	$arrayFechas=array();
	$fechaMostrar = $fechaInicio;

	while(strtotime($fechaMostrar) <= strtotime($fechaFin)) {
	$arrayFechas[]=$fechaMostrar;
	$fechaMostrar = date("Y-m-d", strtotime($fechaMostrar . " + 1 day"));
	}

	return $arrayFechas;
} 

function redondear_minutos($hora){
	$horas=date("H", strtotime($hora));
	$minutos=date("i", strtotime($hora));

	$query="SELECT *
			FROM `limite` 
			ORDER BY limite";   
	$limite=mysql_query($query) or die(mysql_error());
	$row_limite = mysql_fetch_assoc($limite);


	do{
	if($minutos<$row_limite['limite']){
		$minutos=$row_limite['redondeo'];
		$horas=$horas+$row_limite['suma_hora'];
		return "$horas:$minutos";
		break;
	}
	}while($row_limite= mysql_fetch_array($limite));


}

function intervalo_tiempo($init,$finish)
{
	$diferencia = segundos_a_hora($finish) - segundos_a_hora($init);
	$diferencia = round($diferencia/60);
	$diferencia = $diferencia/60;
	
	if($diferencia<0){
		$diferencia="ERROR";
	}
	
    return $diferencia;
}

function pasar_hora($num){
	$num=$num*60;
	$hora_cd = $num*0.01666666667; //hora sin decimales
	$hora = floor($num*0.01666666667);//hora sin decimales
	$resto = $hora_cd-$hora;
	$minutos = round($resto*60);
	if($minutos<10){
		$minutos="0".$minutos;
	}
	$final= "".$hora.":".$minutos."";	
	
	return $final;
}


function segundos_a_hora($hora) { 
    list($h, $m) = explode(':', $hora); 
    return ($h * 3600) + ($m * 60) ; 
} 


function pasar_hora_resta($num){
	$signo=1;
	if($num<0){
		$num=$num*-1;
		$signo=0;
	}
	$num=$num*60;
	$hora_cd = $num*0.01666666667; //hora sin decimales
	$hora = floor($num*0.01666666667);//hora sin decimales
	$resto = $hora_cd-$hora;
	$minutos = round($resto*60);
	if($minutos<10){
		$minutos="0".$minutos;
	}
	$final= "".$hora.":".$minutos."";	
	
	return array($final,$signo);
}

function devuelve_dia($fecha){
	$i = strtotime($fecha); 
	$nro = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)));
	switch ($nro) {
	case 0:
         $dia="Domingo";
         break;
	case 1:
         $dia="Lunes";
         break;
	case 2:
         $dia="Martes";
         break;
	case 3:
         $dia="Miércoles";
         break;
	case 4:
         $dia="Jueves";
         break;
	case 5:
         $dia="Viernes";
         break;
	case 6:
         $dia="Sábado";
         break;
	}
	
	return $dia;
}

function getUltimoDiaMes($elAnio,$elMes) {
  return date("d",(mktime(0,0,0,$elMes+1,1,$elAnio)-1));
}

function esferiado($valor){

	$query="SELECT * 
			FROM feriado 
			WHERE 
			DATE_FORMAT(dia, '%Y-%m-%d') = '$valor'";   
			$feriado=mysql_query($query) or die(mysql_error());
			$row_feriado = mysql_fetch_assoc($feriado);   
	$cantidad_feriado = mysql_num_rows($feriado);
	
	if($cantidad_feriado>0){
		$i="label label-important";
		$j=$row_feriado['feriado'];
		$k=1;
		return array($i,$j,$k);
	} else{
		$i="";
		$j="";
		$k=0;
		return array($i,$j,$k);
	}

}

function gestorErrores($numerr, $menserr, $nombrearchivo, $numlinea, $vars){
	$tipoerror= array(	E_WARNING=>'Alerta', 
						E_NOTICE=> 'Nota', 
						E_USER_ERROR=>'Error de Usuario', 
						E_USER_WARNING=> 'Alerta de Usuario',
						E_USER_NOTICE=>'Nota de Usuario');
	$errores_usuario= array(
						E_USER_ERROR, 
						E_USER_WARNING, 
						E_USER_NOTICE);
	$fecha=date("H:i d/m/Y");
	
	$err="<registro>\n";
	$err.="\t<tipo>".$tipoerror[$numerr]."</tipo>\n";
	$err.="\t<mensaje>".$menserr."</mensaje>\n";
	$err.="\t<archivo>".$nombrearchivo."</archivo>\n";
	$err.="\t<num_linea>".$numlinea."</num_linea>\n";
	$err.="\t<fecha>".$fecha."</fecha>\n";
	$err.="</registro>\n\n";
	
	error_log($err,3,"user_error_log");
}

$gestor_error_antiguo=set_error_handler("gestorErrores");//manejador de errores

function tipoMarcacion($row_marcacion, $cantidad_parametros){
	if($cantidad_parametros==0){//sin marcacion
		$registro['label_class']='insert_access';
		$registro['label_title']='';
		$registro['a_class']='default';
		$registro['marcacion']='-';
		
	}else if($cantidad_parametros>1){//mas de un registro
			$registro['label_class']='label label-important';
			$registro['label_title']='Registro duplicado, por favor modificarlo';
			$registro['a_class']='error';
			$registro['marcacion']=date('H:i', strtotime($row_marcacion['entrada']));
			
		}else{
			$registro['marcacion']=date('H:i', strtotime($row_marcacion['entrada']));
			
			if($row_marcacion['id_estado']==3){//marcación modificada
				$log_auditoria_marcada=getLog($row_marcacion['id_marcada']);
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
					
				$registro['label_class']='label label-success';
				$registro['label_title']='Registro modificado, original :'.date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));
				$registro['a_class']='update';
				
			}else if($row_marcacion['id_estado']==2){//marcación dada de alta por el sistema
				$registro['label_class']='label';
				$registro['label_title']='Registro dado de alta por sistema';
				$registro['a_class']='insert';
				
			}else if($row_marcacion['id_parametros']==0){//marcacion con error
				$registro['label_class']='label label-important';
				$registro['label_title']='Registro sin definir, por favor modificarlo';
				$registro['a_class']='error';
				
			}else{//marcacion normal
				$registro['label_class']='insert_access';
				$registro['label_title']='';
				$registro['a_class']='default';
			}
		}
	
	return $registro;
}

function tipoOtra($row_otrahora, $cantidad){
	if($cantidad>0){
		$registro['label_class']='insert_access';
		$registro['a_class']='btn btn-default';
		$registro['a_title']=$row_otrahora['nota'];
		$registro['marcacion']=$row_otrahora['tipootra']." : ".$row_otrahora['horas'];
		
		if($row_otrahora['id_archivo']!=0){
			$registro['marcacion'].="<i class='icon-paper-clip'></i>";	
		}
		
	}else{
		$registro['label_class']='insert_access';
		$registro['a_class']='btn btn-default';
		$registro['a_title']='Agregar';
		$registro['marcacion']="<i class='icon-plus-sign-alt'></i>";
	}
	
	return $registro;
}
?>
 