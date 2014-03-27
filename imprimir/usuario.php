<?php include_once("../config/database.php"); ?>

<html>
<head>
<title>Sistema de horario Genco</title>
<!--BEGIN META TAGS-->
<META NAME="keywords" CONTENT="">
<META NAME="description" CONTENT="Sistema de horario Genco by TMS Group">
<META NAME="rating" CONTENT="General">
<META NAME="ROBOTS" CONTENT="ALL">
<!--END META TAGS-->

<!-- Charset tiene que estar en utf-8 para que tome ñ y acentos -->
<meta http-equiv="Content-type" content="text/html; charset="utf-8" />
<style>
.datagrid table { border-collapse: collapse; text-align: left; width: 100%; } .datagrid {font: normal 12px/150% Arial, Helvetica, sans-serif; background: #fff; overflow: hidden; border: 1px solid #006699; -webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; }.datagrid table td, .datagrid table th { padding: 3px 10px; }.datagrid table thead th {background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; color:#FFFFFF; font-size: 15px; font-weight: bold; border-left: 1px solid #0070A8; } .datagrid table thead th:first-child { border: none; }.datagrid table tbody td { color: #00557F; border-left: 1px solid #E1EEF4;font-size: 12px;font-weight: normal; }.datagrid table tbody .alt td { background: #E1EEf4; color: #00557F; }.datagrid table tbody td:first-child { border-left: none; }.datagrid table tbody tr:last-child td { border-bottom: none; }.datagrid table tfoot td div { border-top: 1px solid #006699;background: #E1EEf4;} .datagrid table tfoot td { padding: 0; font-size: 12px } .datagrid table tfoot td div{ padding: 2px; }.datagrid table tfoot td ul { margin: 0; padding:0; list-style: none; text-align: right; }.datagrid table tfoot  li { display: inline; }.datagrid table tfoot li a { text-decoration: none; display: inline-block;  padding: 2px 8px; margin: 1px;color: #FFFFFF;border: 1px solid #006699;-webkit-border-radius: 3px; -moz-border-radius: 3px; border-radius: 3px; background:-webkit-gradient( linear, left top, left bottom, color-stop(0.05, #006699), color-stop(1, #00557F) );background:-moz-linear-gradient( center top, #006699 5%, #00557F 100% );filter:progid:DXImageTransform.Microsoft.gradient(startColorstr='#006699', endColorstr='#00557F');background-color:#006699; }.datagrid table tfoot ul.active, .datagrid table tfoot ul a:hover { text-decoration: none;border-color: #00557F; color: #FFFFFF; background: none; background-color:#006699;}div.dhtmlx_window_active, div.dhx_modal_cover_dv { position: fixed !important; }
</style>
</head>
<?
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$id_usuario=$_GET['id'];
$fecha=date("d-m-Y");

$query="SELECT 	usuario.id_usuario,
				usuario.nombre as nombre,
				usuario.legajo as legajo,
				departamento.nombre as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE id_usuario='$id_usuario'";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

$query="SELECT 	usuario.id_usuario,
				usuario.nombre as nombre,
				usuario.legajo as legajo,
				departamento.nombre as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE usuario.id_estado=1
		ORDER BY usuario.nombre";   
$usuarios=mysql_query($query) or die(mysql_error());
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

$query="SELECT * 
		FROM marcada 
		WHERE 
		DATE_FORMAT(entrada, '%Y-%m-%d') >= '$fecha_inicio' AND
		DATE_FORMAT(entrada, '%Y-%m-%d') <= '$fecha_final' AND
		id_usuario='$id_usuario' AND
		id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());
		$row_marcacion = mysql_fetch_assoc($marcacion);   
$cantidad_marcacion = mysql_num_rows($marcacion);

		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));



# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query="SELECT * 
		FROM otrahora 
		WHERE 
		fecha >= '$fecha_inicio' AND
		fecha <= '$fecha_final' AND
		id_usuario='$id_usuario'";   
		$otrahora=mysql_query($query) or die(mysql_error());
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			
}


?>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<body onload="window.print();">
<center>

<div class="datagrid">
<table>
<tr>
	<td><h1>Reporte Horario</h1></td>
	<td><center><img src="../imagenes/genco.jpg"  width='191' height='69'></center></td>
</tr>
</table>
</div>


<? if(isset($_GET['buscar'])){?>
<div class="datagrid">
<table border="1">
<tbody>
<tr>
	<th>Nombre</th>
	<td><? echo $row_usuario['nombre']?></td>
	<th title="Departamento al que pertenecen">Sector</th>
	<td><? echo $row_usuario['departamento']?></td>
	<th>Legajo</th>
	<td><? echo $row_usuario['legajo']?></td>
</tr>
<tr>
	<th>Fecha Inicio</th>
	<td><? echo date('d-m-Y', strtotime($fecha_inicio))?></td>
	<th>Fecha Final</th>
	<td><? echo date('d-m-Y', strtotime($fecha_final))?></td>
	<th>Cantidad</th>
	<td><? echo $cantidad_marcacion;?></td>
</tr>
</tbody>
</table>
</div>
<br>

<div class="datagrid">
<table border="1">
	<th><h3>Dia</h3></th>
	<th><h3>Fecha</h3></th>
	<th><h3>m-e</h3></th>
	<th><h3>m-s</h3></th>
	<th><h3>t-e</h3></th>
	<th><h3>t-s</h3></th>
	<th><h3>Horas</h3></th>
	<th><h3>otros</h3></th>


<tbody>
<? 
foreach($arrayFechas as $valor){?>

	<tr>
		<td><?= devuelve_dia($valor);?></td>
		<td><?= $valor;?></td>
		
		<? 
		for ($i = 1; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			// Esta funcion redondea segun los la tabla limites de la base de datos, se pidio que se sacara
			//$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));
			?>

			<?
			if($cantidad_parametros==0){?>
				<td> - </td>
				<? 
				if($i==1){
					$me=0;
				} else if($i==2){ 
					$ms=0;
				} else if($i==3){ 
					$te=0;
				} else if($i==4){ 
					$ts=0;
				}
				?>
			<?}else if($cantidad_parametros>1){?>
				<td><? echo date('H:i', strtotime($row_marcacion['entrada']));?></td>
			<?}else{
						
				if($i==1){
					$me=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==2){ 
					$ms=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==3){ 
					$te=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==4){ 
					$ts=date('H:i', strtotime($row_marcacion['entrada']));
				}
				
				if($row_marcacion['id_estado']==3){
					$query="SELECT * 
					FROM log_auditoria_marcada
					WHERE
					id_marcada='$row_marcacion[id_marcada]'";   
				$log_auditoria_marcada=mysql_query($query) or die(mysql_error());
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
			
				?>
				<td><p class="modificado" title="Registro modificado, original :<? echo date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));?>"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_estado']==2){?>
				<td><p class="insert_php" title="Registro dado de alta por sistema"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_parametros']==0){?>
				<td><p class="duplicado" title="Registro sin definir, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
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
		
		//transformamos el numero resultante en hora y minutos
		
		?>
		<td><? echo pasar_hora($subtotal); ?></td>
		<? } else {?>
		<td> - </td>
		<?}
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
			}
		?>
		<td><? echo $row_otrahora['horas'];?> <? echo $row_otrahora['tipootra'];?></td>	
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
<table class="tablad">
<tr>
	<td>Total de otras</td>
	<th><? echo round($totalotras,2);?></th>
	<td>Total de horas</td>
	<th><? echo pasar_hora($total);?></th>
</tr>
</table>	
</div>
 

</center>
</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->

</body>
<?}?>