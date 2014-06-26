<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
	
include_once("menu.php"); 
include_once($models_url."usuarios_model.php"); 
include_once($models_url."otrahora_model.php"); 
include_once($models_url."marcadas_model.php");
set_time_limit(120); 

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$totalotras=0;
$fecha=date("d-m-Y");

$usuarios=getUsuarios();
$row_usuarios = mysql_fetch_assoc($usuarios);

$usuarios2=getUsuarios();
$row_usuarios2 = mysql_fetch_assoc($usuarios2);


$tipootra=getTipootra();
$row_tipootra = mysql_fetch_assoc($tipootra);



//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Funciones php
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->


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
	$diferencia = strtotime($finish) - strtotime($init);
	$diferencia=round($diferencia/60);
	$diferencia=$diferencia/60;
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



//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Busqueda de fechas
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['buscar'])){

$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));

$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);

# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

	$marcacion=getMarcaciones(NULL, $fecha_inicio, $fecha_final);
	$row_marcacion = mysql_fetch_assoc($marcacion);   
	$cantidad_marcacion = mysql_num_rows($marcacion);

		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));



# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

		$otrahora=getOtrahora(NULL, $fecha_inicio, $fecha_final);
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
				Para poner disabled si no hay fecha
----------------------------------------------------------------------			
--------------------------------------------------------------------*/


$cadena="";
$classcadena="";

if(!isset($fecha_inicio)){ 
	$cadena="disabled title='Seleccione período de tiempo'"; 
	$classcadena="class='disabled' title='Seleccione período de tiempo'";
} 

?>
<div class="row">

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->

	<table class="table table-striped">
	<tr class="success">
	<td>
		<b>Período de tiempo</b>
	</td>
	
	<td>
		<form class="form-inline" action="usuario2.php" name="ente">
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker2').focus();"><i class="icon-calendar"></i></span>
			<input value="" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" autocomplete="off" required>
		</div></b>
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker').focus();"><i class="icon-calendar"></i></span>
			<input value=""	type="text" name="fecha_final" id="datepicker" placeholder="fecha final" autocomplete="off" required>
		</div></b>
		<button type="submit" class="btn" title="Buscar" name="buscar" value="1"><i class="icon-search"></i> Buscar</button>
		</form>
	</td>
	
	<td>
		<b>Usuario</b>
	</td>
	
	<td>
		<select
		data-placeholder="Seleccione un usuario..." class="chosen-select" tabindex="2"		
		onChange="javascript:window.location.href='usuario.php?id='+this.value+'&buscar=<?php echo 1;?>&fecha_final=<?php echo $fecha_final; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>';"
		name="id" <?php echo $cadena;?> required>
		<?php do{ ?>
		<option value="<?php echo $row_usuarios['id_usuario']?>"><?php echo $row_usuarios['usuario']?></option>
		<?php } while($row_usuarios=mysql_fetch_array($usuarios));?>
		</select>
	</td>
	
	
	<td>
	<div class="btn-group">
	  <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="icon-cogs"></i>
		<span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu">
		<li <?php echo $classcadena;?>><a href="usuario.php?id=<?php echo $id_usuario;?>&buscar=<?php echo 1;?>&fecha_final=<?php echo $fecha_final; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>"  title="Refresh" <?php if(!isset($fecha_final)){ ?> disabled<?php } ?>><i class="icon-refresh"></i> Refresh</a></li>
		<li <?php echo $classcadena;?>><a href="javascript:imprSelec('muestra')"><i class="icon-print"></i> Imprimir</a></li>
		<li <?php echo $classcadena;?>><a onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</a></li>
		<li><a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i> Ayuda</a></li>
	  </ul>
	</div>
	</td>

	</tr>
	</table>
	
	<!-- Ayuda -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3>
	</div>
	<div class="modal-body">
	<p>Esta tabla muestra la sumatoria de las horas trabajadas para cada usuario en un intervalo de fechas.</p>
	<p>Si un usuario se desea eliminar o agregar a la lista se puede hacer desde la edición de usuarios.</p>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Aceptar</button>
	</div>
	</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	


<?php
if(isset($_GET['buscar'])){
if($fecha_inicio>$fecha_final){
	echo 	"<div class='alert alert-error'> 
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			La fecha de inicio es mayor a la fecha final, corríjalo por favor</div>";
}else{

?>

<div id="muestra">

<div class="carga">
<p>Cargando datos</p>
<img  src="imagenes/loading.gif" />
</div>

<table  class="tablad" border="1">
	<tr>
		<th>Fecha inicio</th>
		<td><?php echo date( "d-m-Y", strtotime($fecha_inicio))?></td>
		<th>Fecha final</th>
		<td><?php echo date( "d-m-Y", strtotime($fecha_final))?></td>
	</tr>
</table>
<br>

<table class="table table-hover" border ="1" id="example">
<thead>
	<th title="Legajo del usuario">Legajo</th>
	<th title="Usuario">Usuario</th>
	<th title="Fecha de ingreso">Ingreso</th>
	<th title="Hora que debe cumplir en el mes">Normales</th>
	<th title="Resultado de Horas mensuales-Horas trabajadas">50%</th>
	<th title="Horas extras que van al 100%, feriados, domingos y sábado pasado el convenio">100%</th>
	<?php do{ ?>
	<th title=""><?php echo $row_tipootra['tipootra'];?></th>
	<?php }while($row_tipootra=mysql_fetch_array($tipootra))?>
	<th title="Total de horas trabajadas en el mes">Total</th>
</thead>

<tbody>
<?php
//recorremos todos los usuarios
do{
$total=0;
$totalotras=0;
$id_usuario=$row_usuarios2['id_usuario'];
$semana=$row_usuarios2['semana'];
$sabado=$row_usuarios2['sabado'];
$salida_sabado=$row_usuarios2['salida_sabado'];
$total_cincuenta=0;
$total_normales=0;
$total_cien=0;
$subtotal=0;
$otrahoras=array();

foreach($arrayFechas as $valor){

		list ($clase, $title, $esferiado) = esferiado($valor);
		$dia=devuelve_dia($valor);
		
		if($dia=="Domingo" || $esferiado==1){
		}else{
			if($dia!="Sábado"){
				$total_normales=$total_normales+$semana;
			}else{
				$total_normales=$total_normales+$sabado;
			}
		}

		$me=0;
		$ms=0;
		$te=0;
		$ts=0;
		
		$canme=0;
		$canms=0;
		$cante=0;
		$cants=0;
	
		$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_usuario=$id_usuario";   
		$marcacion=mysql_query($query) or die(mysql_error());
		$row_marcacion = mysql_fetch_assoc($marcacion);
		$cantidad_parametros=mysql_num_rows($marcacion);
		
		if($cantidad_parametros>0){
		do{
		$i=$row_marcacion['id_parametros'];
				if($i==1){
					$canme=$canme+1;
					if($canme==1){
						if($aplicar_redondeo==0){
							$me=date('H:i', strtotime($row_marcacion['entrada']));		
						}else{
							$me= date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));		
						}					
					}
				} else if($i==2){ 
					$canms=$canms+1;
					if($canms==1){
						if($aplicar_redondeo==0){
							$ms=date('H:i', strtotime($row_marcacion['entrada']));		
						}else{
							$ms= date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));		
						}
					}
				} else if($i==3){ 
					$cante=$cante+1;
					if($cante==1){
						if($aplicar_redondeo==0){
							$te=date('H:i', strtotime($row_marcacion['entrada']));		
						}else{
							$te= date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));		
						}
					}
				} else if($i==4){ 
					$cants=$cants+1;
					if($cants==1){
						if($aplicar_redondeo==0){
							$ts=date('H:i', strtotime($row_marcacion['entrada']));		
						}else{
							$ts= date('H:i', strtotime(redondear_minutos($row_marcacion['entrada'])));		
						}
					}
				}
		}while($row_marcacion=mysql_fetch_array($marcacion));	
		}
			
		
		 if($me>0 && $ms>0){
			$m=intervalo_tiempo($me,$ms);
			}else{
			$m=0;
			}
			
			if($te>0 && $ts>0){
			$t=intervalo_tiempo($te,$ts);
			}else{
			$t=0;
			}
			
			if($t>0 || $m>0){
			$subtotal=$m+$t;
			$total=$total+$subtotal;
			}else{
			$subtotal=0;
			}
			
		$i=$subtotal;

		if($dia=="Domingo" || $esferiado==1){
			$total_cien=$total_cien+$i;	
		}else{
			if($dia!="Sábado"){
				$i=$i-$semana;
				$total_cincuenta=$total_cincuenta+$i;
			}else{
				$i=$i-$sabado;
				$rest = substr($ms, 0, 2);
				
				if($rest>=$salida_sabado && $i>0){
					$total_cien=$total_cien+$i;	
				}else{
				$total_cincuenta=$total_cincuenta+$i;
				}
			}
			}

		
	list ($resta, $signo) = pasar_hora_resta($total_cincuenta);
	if($signo==0){
		$resta="-".$resta;
	}
		
}

	$tipootra2=getTipootra();
	$row_tipootra2 = mysql_fetch_assoc($tipootra2);
	
	$total_otrahora=0;
	$total_otrahora_cien=0;

	$i=0;
	do{
		$suma_otrahora=0;
		$total_cienotra=0;
		$subtotal=0;
		
		$id_tipootra=$row_tipootra2['id_tipootra'];
				
				$query="SELECT * 
					FROM tempotra 
					INNER JOIN tipootra ON(tempotra.id_tipootra=tipootra.id_tipootra)
					WHERE
					id_usuario='$id_usuario' AND
					tempotra.id_tipootra='$id_tipootra'";   
				$otrahora=mysql_query($query) or die(mysql_error());
				$row_otrahora = mysql_fetch_assoc($otrahora);
				$cantidad=mysql_num_rows($otrahora);
		
		if($cantidad>0){
			do{
				list ($clase, $title, $esferiado) = esferiado($row_otrahora['fecha']);
				$dia=devuelve_dia($row_otrahora['fecha']);
				
				if($dia=="Domingo" || $esferiado==1){
					$total_cienotra=$total_cienotra+$row_otrahora['horas'];
				}else{
					$suma_otrahora=$suma_otrahora+$row_otrahora['horas'];
				}
			}while($row_otrahora=mysql_fetch_array($otrahora));
			$total_otrahora=$total_otrahora+$suma_otrahora+$total_cienotra;
			$total_otrahora_cien=$total_otrahora_cien+$total_cienotra;
			$otrahoras[$i] = $suma_otrahora+$total_cienotra;
		}else{
			$otrahoras[$i] = "-";
		}
		$i=$i+1;		
	}while($row_tipootra2=mysql_fetch_array($tipootra2));
	
	$total_cincuenta=$total_cincuenta+$total_otrahora-$total_otrahora_cien;
		
	list ($resta, $signo) = pasar_hora_resta($total_cincuenta);
	?>
	
	<tr>
		<td><?php echo $row_usuarios2['legajo'];?></td>
		<td><?php echo $row_usuarios2['usuario'];?></td>

		<?php if($row_usuarios2['fecha_ingreso']!=0){ ?>
		<td><?php date( "d-m-Y", strtotime($row_usuarios2['fecha_ingreso']));?></td>
		<?php }else{ ?>
		<td> - </td>
		<?php } ?>

		<td><?php echo $total_normales;?></td>
	
		<?php if($signo==0){ ?>
		<td title="Horas que el empleado debe recuperar para alcanzar el minimo de horas trabajadas">- <?php echo $resta;?></td>	
		<?php }else{ ?>
		<td title="Suma total de las horas extra al 50%"><p  class="dia label label-info"><?php echo $resta;?></p></td>	
		<?php } ?>
	
		<?php if($total_cien+$total_otrahora_cien>0){?>
		<td title="Suma total de las horas extra al 100%, suma de horas trabajadas domingos, sábado pasado el convenio o feriados"	><p  class="dia label label-info"><?php echo pasar_hora($total_cien+$total_otrahora_cien);?></p></td>
		<?php }else{?>
		<td> - </td>
		<?php } ?>
		
		<?php foreach($otrahoras as $otra_hora){?>
		<td><?php echo $otra_hora;?></td>
		<?php }?>
			
		<?php $total=$total+$total_otrahora;
		if($total>0){ ?>
		<td><?php echo pasar_hora($total); ?></td>
		<?php } else { ?>
		<td> - </td>
		<?php } ?>
<?php }while($row_usuarios2=mysql_fetch_array($usuarios2));


//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());

$query_drop = "DROP TABLE tempotra";
$res_drop = mysql_query($query_drop) or die(mysql_error());

?>
<!--Controles de la tabla--> 
</tbody>
</table>
</div>





<?php }//cierra el if de fechas inicio>final
}else{
echo 	"<div class='alert alert-info'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		Seleccione período de tiempo
		</div>";

}?>


<?php include_once("footer.php");?>

</center>
</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->

</body>