<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php"); 
include_once($url['models_url']."marcadas_model.php"); 
include_once($url['models_url']."otrahora_model.php"); 
include_once($url['models_url']."logs_model.php"); 


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$id_usuario=$_GET['id'];
$totalotras=0;
$fecha=date("d-m-Y");

$usuario=getUsuario($id_usuario);
$row_usuario = mysql_fetch_assoc($usuario);

$usuarios=getUsuarios(); 
$row_usuarios = mysql_fetch_assoc($usuarios);





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
 




//----------------------------------------------------------------------
//----------------------------------------------------------------------
//			Busqueda de fechas y creacion de tablas temporales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['buscar'])){


if($_GET['buscar']==1){
$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));
}else{
$fecha_inicio=date('01-m-Y', strtotime($_GET['fecha']));
$ultimoDia = getUltimoDiaMes(date('Y', strtotime($_GET['fecha'])),date('m', strtotime($_GET['fecha'])));
$fecha_final=$ultimoDia.date('-m-Y', strtotime($_GET['fecha']));
}
}else{
$fecha=date("d-m-Y");
$fecha_inicio=date('01-m-Y', strtotime($fecha));
$ultimoDia = getUltimoDiaMes(date('Y', strtotime($fecha)),date('m', strtotime($fecha)));
$fecha_final=$ultimoDia.date('-m-Y', strtotime($fecha));

}



$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);

# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

		$marcacion=getMarcaciones($id_usuario, $fecha_inicio, $fecha_final);
		$row_marcacion = mysql_fetch_assoc($marcacion);   
		$cantidad_marcacion = mysql_num_rows($marcacion);
		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));



# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

		$otrahora=getOtrahora($id_usuario, $fecha_inicio, $fecha_final);
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			





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
		<form class="form-inline" action="usuario.php" name="ente">
		<input type="hidden" name="id" value="<? echo $id_usuario?>">
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker2').focus();"><i class="icon-calendar"></i></span>
			<input value="<?= date('d-m-Y', strtotime($fecha_inicio)); ?>" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" autocomplete="off" required>
		</div></b>
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker').focus();"><i class="icon-calendar"></i></span>
			<input value="<? echo date('d-m-Y', strtotime($fecha_final)); ?>"	type="text" name="fecha_final" id="datepicker" placeholder="fecha final" autocomplete="off" required>
		</div></b>
		<button type="submit" class="btn btn-default" title="Buscar" name="buscar" value="1"><i class="icon-search"></i></button>
		</form>
	</td>
	
	<td>
		<select 
		onChange="javascript:window.location.href='usuario.php?id='+this.value+'&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>';"
		name="id" <?= $cadena;?> required>
		<? do{ 
		if($id_usuario==$row_usuarios['id_usuario']){
		?>
		<option value="<? echo $row_usuarios['id_usuario']?>" selected><? echo $row_usuarios['usuario']?></option>
		<?}else{?>
		<option value="<? echo $row_usuarios['id_usuario']?>"><? echo $row_usuarios['usuario']?></option>
		<?} 
		} while($row_usuarios=mysql_fetch_array($usuarios));?>
		</select>
	</td>	
	
	<td>
		
	<div class="btn-group">
	  <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="icon-cogs"></i>
		<span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu">
		<li <?= $classcadena;?>><a href="usuario.php?id=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>"  title="Refresh" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-refresh"></i> Refresh</a></li>
		<li <?= $classcadena;?>><a href="javascript:imprSelec('muestra')"><i class="icon-print"></i> Imprimir</a></li>
		<li <?= $classcadena;?>><a href="exportar/usuario.php?id=<?= $id_usuario;?>&nombre=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>" title="Exportar" target="_blank" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-upload-alt"></i> Exportar</a></li>
		<li <?= $classcadena;?>><a href="#" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-table"></i> Tabla</a></li>
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
	<p>Esta tabla muestra todas las marcaciones de un usuario entre un intervalo de fechas.</p>
	<p>En la parte inferior de la pantalla muestra la suma del total de las horas.</p>
	<p>Desde esta tabla también se pueden editar las marcaciones.</p>
	<p>Todas las tablas se pueden imprimir y exportar a Excel.</p>
	</div>
	<div class="modal-footer">
	<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
	</div>
	</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	


<?

if(isset($fecha_inicio) && empty($id_usuario)){
echo "<div class='alert'><button type='button' class='close' data-dismiss='alert'>&times;</button>Falta seleccionar usuario</div><br>";
} else if(isset($_GET['buscar'])){
if($fecha_inicio>$fecha_final){
	echo 	"<div class='alert alert-error'> 
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			La fecha de inicio es mayor a la fecha final, corríjalo por favor</div>";
}else{


?>

<div id="muestra">
<table border="1" class="tablad">
<tbody>
<tr>
	<th title="Nombre de los usuarios">Nombre</th>
	<td><? echo $row_usuario['usuario']?></td>
	<th title="Departamento al que pertenecen">Sector</th>
	<td><? echo $row_usuario['departamento']?></td>
	<th title="Legajo de los usuarios">Legajo</th>
	<td><? echo $row_usuario['legajo']?></td>
</tr>
<tr>
	<th title="Fecha inicio">Fecha Inicio</th>
	<td><? echo date('d-m-Y', strtotime($fecha_inicio))?></td>
	<th title="Fecha final">Fecha Final</th>
	<td><? echo date('d-m-Y', strtotime($fecha_final))?></td>
	<th title="Cantidad de marcaciones">Cantidad</th>
	<td><? echo $cantidad_marcacion;?></td>
</tr>
</tbody>
</table>
<br>

<div class="carga">
<p>Cargando datos</p>
<img  src="imagenes/loading.gif" />
</div>

<table border="1" class="table table-hover" id="example">
<thead>
	
	<th title="Año/mes/día">Fecha</th>
	<th title="Fecha">Día</th>
	<th title="Sin definir">sd</th>
	<th title="Mañana - Entrada">m-e</th>
	<th title="Mañana - Salida">m-s</th>
	<th title="Tarde - Entrada">t-e</th>
	<th title="Tarde - Salida">t-s</th>
	<th title="Subtotales">Subtotal</th>
	<th title="Calculo de horas laborales">Horas</th>
	<th title="Otro tipo">Otros</th>
	<th title="Editar las entradas">Editar</th>
</thead>

<tbody>
<? 
foreach($arrayFechas as $valor){?>

	<tr>	
		<?list ($clase, $title,$esferiado) = esferiado($valor);
			$dia=devuelve_dia($valor);
		?>
		<td><p class="<?= $clase;?>" title="<?= $title;?>"><?= $valor;?></p></td>
		<td><p class="dia"><?= $dia;?></p></td>

		<? 
					$me=0;
					$ms=0;
					$te=0;
					$ts=0;

		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			if($cantidad_parametros>0){
				if($i==1){
					$me=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==2){ 
					$ms=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==3){ 
					$te=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==4){ 
					$ts=date('H:i', strtotime($row_marcacion['entrada']));
				}
			}
			
			
			
			// Esta funcion redondea segun los la tabla limites de la base de datos, se pidio que se sacara
			//$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));

			if($cantidad_parametros==0){ ?>
				<td><p class="insert_access"> - </p></td>
			<?}else if($cantidad_parametros>1){?>
				<td><p class="label label-important" title="Registro duplicado, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
			<?}else{	
		
		
				
				
				if($row_marcacion['id_estado']==3){
					
				$log_auditoria_marcada=getLog($row_marcacion['id_marcada']);
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
			
				?>
				<td><p class="label label-success" title="Registro modificado, original :<? echo date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));?>"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_estado']==2){?>
				<td><p class="label" title="Registro dado de alta por sistema"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_parametros']==0){?>
				<td><p class="label label-important" title="Registro sin definir, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else{?>
				<td><p class="insert_access"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}
			}//cierra el else
		}//cierra el for
		
		//Si la entrada y la salida de la mañana son mayores a 0 calculamos el intervalo de tiempo, el resultado es un numero
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
			if($subtotal>0){
		
				
		?>
		<td><? echo pasar_hora($m)." + ".pasar_hora($t) ?></td>
		<td><? echo pasar_hora($subtotal); ?></td>
		<? } else {?>
		<td> - </td>
		<? 	if(($me>0 || $ms>0 || $te>0 || $ts>0) && $subtotal==0) { ?>
			<td><p class="label label-important" title="Los registros no tienen los parámetros correctos"> - </p></td>
		<? 	}else{?>
			<td> - </td>
		<?	}
			}

		$query="SELECT * 
				FROM tempotra 
				INNER JOIN tipootra ON(tempotra.id_tipootra=tipootra.id_tipootra)
				INNER JOIN nota ON(tempotra.id_nota=nota.id_nota)
				WHERE
				id_usuario='$row_usuario[id_usuario]' AND
				fecha='$valor'";   
			$otrahora=mysql_query($query) or die(mysql_error());
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
			if($cantidad>0){
			$totalotras=$totalotras+$row_otrahora['horas'];
		?>
		<script type="text/javascript">
		function cargar(){
			opener.location.reload();
			window.close();
		}
		</script>
		<td><p class="insert_access"><a href="#" class="btn btn-default" title="<? echo $row_otrahora['nota'];?>" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $valor?>')"><? echo $row_otrahora['tipootra'];?> : <? echo $row_otrahora['horas'];?></a></p></td>
		<?}else{?>
		<td><p class="insert_access"><a href="#" class="btn btn-default" title="Agregar" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $valor?>')"><i class="icon-plus-sign-alt"></i></a></p></td>
		<?}?>
	<?
		$i=$subtotal+$row_otrahora['horas'];

		if($dia=="Domingo" || $esferiado==1 ){
			$total_cien=$total_cien+$i;	
		}else{
			if(devuelve_dia($valor)!="Sábado"){
				$i=$i-$row_usuario['semana'];
				$total_cincuenta=$total_cincuenta+$i;
			}else{
				$i=$i-$row_usuario['sabado'];
				$rest = substr($ms, 0, 2);
				if($rest>=$row_usuario['salida_sabado'] && $i>0){
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
	?>	
	<td><a href="#" class="btn btn-default" title="Parametros" onClick="abrirVentana('edit.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $valor?>')"><i class="icon-edit-sign"></i></a></td>
	</tr>
<? }

//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());

$query_drop = "DROP TABLE tempotra";
$res_drop = mysql_query($query_drop) or die(mysql_error());

?>

</tbody>
</table>
</div>


</center>
<center>
<br>
<div class="span12">
<table class="tablad">
<tr>
	
	<?
	list ($resta, $signo) = pasar_hora_resta($total_cincuenta);
	if($signo==0){?>
	<td title="Horas que el empleado debe recuperar para alcanzar el minimo de horas trabajadas">A favor de la empresa</td>	
	<?}else{?>
	<td title="Suma total de las horas extra al 50%">A favor del empleado</td>	
	<?}?>
	<th class="cincuenta"><?= $resta;?></th>
	<td title="Suma total de las horas extra al 100%, suma de horas trabajadas domingos, sabado pasado el convenio o feriados">Horas extra 100%</td>
	<th class="cien"><? echo pasar_hora($total_cien);?></th>
	<td title="Es la suma de las 'otros', son horas de enfermedad, accidente, ausencias, otros">Total de otras horas</td>
	<th><? echo round($totalotras,2);?></th>
	<td title="Suma total de las horas normales">Total de horas</td>
	<? $total=$total+$totalotras; ?>
	<th><? echo pasar_hora($total);?></th>
</tr>
</table>	
</div>
</center>



	

<?}//cierra el if de fechas inicio>final
}else{/*
echo 	"<div class='alert alert-info'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		Seleccione período de tiempo
		</div>";
*/
}

?>







<? include_once("footer.php");?> 


</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->


<!-- Modal -->


</body>


