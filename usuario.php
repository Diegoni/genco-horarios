<?php include_once("menu.php"); 
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
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->

	<table class="table table-striped table-hover">
	<tr class="success">
	<td>
		<b>Periodo de tiempo</b>
	</td>
	<td>
		<form class="form-inline" action="usuario.php" name="ente">
		<input type="hidden" name="id" value="<? echo $id_usuario?>">
		<b><div class="input-prepend">
			<span class="add-on"><i class="icon-calendar"></i></span>
			<input <? if(isset($fecha_inicio)){?>
			value="<? echo date('d-m-Y', strtotime($fecha_inicio)); ?>"
			<?}?>
			type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" autocomplete="off" required>
		</div></b>
		<b><div class="input-prepend">
			<span class="add-on"><i class="icon-calendar"></i></span>
			<input <? if(isset($fecha_final)){?>
			value="<? echo date('d-m-Y', strtotime($fecha_final)); ?>"
			<?}else{?>
			value="<? echo $fecha;?>" 
			<?}?>
			type="text" name="fecha_final" id="datepicker" placeholder="fecha final" autocomplete="off" required>
		</div></b>
		<button type="submit" class="btn" title="Buscar" name="buscar" value="1"><i class="icon-search"></i></button>
		</form>
	<td>
		<select 
		onChange="javascript:window.location.href='usuario.php?id='+this.value+'&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>';"
		name="id" <? if(!isset($fecha_inicio)){?> disabled title="Seleccione periodo de tiempo"<? } ?> required>
		<? do{ 
		if($id_usuario==$row_usuarios['id_usuario']){
		?>
		<option value="<? echo $row_usuarios['id_usuario']?>" selected><? echo $row_usuarios['nombre']?></option>
		<?}else{?>
		<option value="<? echo $row_usuarios['id_usuario']?>"><? echo $row_usuarios['nombre']?></option>
		<?} 
		} while($row_usuarios=mysql_fetch_array($usuarios));?>
		</select>
	</td>	
	</td>
	<td>
		<a href="usuario.php?id=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>"" class="btn" title="Refresh" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-refresh"></i></a>
		<a href="imprimir/usuario.php?id=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>" class="btn" title="Imprimir" target="_blank" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-print"></i></a>
		<a href="exportar/usuario.php?id=<?= $id_usuario;?>&nombre=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>" class="btn btn-primary" title="Exportar" target="_blank" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-upload-alt"></i></a>
		<a href="index.php" class="btn btn-success" title="Volver" ><i class="icon-circle-arrow-left"></i></a>
	</td>
	</tr>
	</table>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<? if(isset($_GET['buscar'])){?>
<table border="1" class="tablad">
<tbody>
<tr>
	<th title="Nombre de los usuarios">Nombre</th>
	<td><? echo $row_usuario['nombre']?></td>
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

<div id="target">
<table  id="table" class="sortable">
<thead>
	<th title="Fecha"><h3>Fecha</h3></th>
	<th title="Nombre"><h3>Nombre</h3></th>
	<th title="Sin definir"><h3>sd</h3></th>
	<th title="Mañana - Entrada"><h3>m-e</h3></th>
	<th title="Mañana - Salida"><h3>m-s</h3></th>
	<th title="Tarde - Entrada"><h3>t-e</h3></th>
	<th title="Tarde - Salida"><h3>t-s</h3></th>
	<th title="Calculo de horas laborales"><h3>Horas</h3></th>
	<th title="Otro tipo"><h3>otros</h3></th>
	<th title="Editar las entradas"><h3>editar</h3></th>
</thead>

<tbody>
<? foreach($arrayFechas as $valor){?>
	<tr>
		
		<td><? echo date( "d-m-Y", strtotime($valor));?></td>
		<td><? echo $row_usuario['nombre']?></td>
		<? 
		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));
			?>

			<?
			if($cantidad_parametros==0){?>
				<td><p class="insert_access"> - </p></td>
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
				<td><p class="duplicado" title="Registro duplicado, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
			<?}else{
				if($i==1){
					$me=date('H:i', strtotime($redondear_minutos));
				} else if($i==2){ 
					$ms=date('H:i', strtotime($redondear_minutos));
				} else if($i==3){ 
					$te=date('H:i', strtotime($redondear_minutos));
				} else if($i==4){ 
					$ts=date('H:i', strtotime($redondear_minutos));
				}
				
				if($row_marcacion['id_estado']==3){
					$query="SELECT * 
					FROM log_auditoria_marcada
					WHERE
					id_marcada='$row_marcacion[id_marcada]'";   
				$log_auditoria_marcada=mysql_query($query) or die(mysql_error());
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
			
				?>
				<td><p class="modificado" title="Registro modificado, original :<? echo date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));?>"><? echo date('H:i', strtotime($row_marcacion['entrada']));?> - <? echo date('H:i', strtotime($redondear_minutos));?></p></td>
				<?}else if($row_marcacion['id_estado']==2){?>
				<td><p class="insert_php" title="Registro dado de alta por sistema"><? echo date('H:i', strtotime($row_marcacion['entrada']));?> - <? echo date('H:i', strtotime($redondear_minutos));?></p></td>
				<?}else if($row_marcacion['id_parametros']==0){?>
				<td><p class="duplicado" title="Registro sin definir, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?> - <? echo date('H:i', strtotime($redondear_minutos));?></p></td>
				<?}else{?>
				<td><p class="insert_access"><? echo date('H:i', strtotime($row_marcacion['entrada']));?> - <? echo date('H:i', strtotime($redondear_minutos));?></p></td>
				<?}?>
			<?}//cierra el else?>
		<?}//cierra el for?>
		<? if($me>0 && $ms>0){
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
		<td><? echo $m." + ".$t." = ".$subtotal; ?></td>
		<? } else {?>
		<td> - </td>
		<?}
		$query="SELECT * 
				FROM tempotra 
				INNER JOIN tipootra ON(tempotra.id_tipootra=tipootra.id_tipootra)
				INNER JOIN nota ON(tempotra.id_nota=nota.id_nota)
				WHERE
				id_usuario='$row_usuario[id]'";   
			$otrahora=mysql_query($query) or die(mysql_error());
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
			if($cantidad>0){
		?>
		<td><p class="insert_access"><a href="#" class="btn" title="<? echo $row_otrahora['nota'];?>" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id']?>&fecha=<?echo $fecha_americana?>')"><? echo $row_otrahora['tipootra'];?> : <? echo $row_otrahora['horas'];?></a></p></td>
		<?}else{?>
		<td><p class="insert_access"><a href="#" class="btn" title="Agregar" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id']?>&fecha=<?echo $fecha_americana?>')"><i class="icon-plus-sign-alt"></i></a></p></td>
		<?}?>
	<td><a href="#" class="btn" title="Parametros" onClick="abrirVentana('edit.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $valor?>')"><i class="icon-edit-sign"></i></a></td>
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
<table class="tablad">
<tr>
	<td>Total de horas</td>
	<th><? echo $total;?></th>
</tr>
</table>	
</div>	
 
<!--Controles de la tabla-->            
	<div id="controls">
	<div id="perpage">
		<select onchange="sorter.size(this.value)">
		<option value="5">5</option>
			<option value="10" selected="selected">10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
		<span>Cantidad por Pagina</span>
	</div>
	<div id="navigation">
		<img src="imagenes/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
		<img src="imagenes/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
		<img src="imagenes/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
		<img src="imagenes/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
	</div>
	<div id="text">Mostrando pagina <span id="currentpage"></span> de <span id="pagelimit"></span></div>
    <p><br /></p>
    <p>&nbsp; </p>
    </div>

<!--script de la tabla, ver si se cambia de lugar-->	
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",1);
  </script>   

</center>
</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->

</body>
<?}else{
echo "Seleccione periodo de tiempo";

}?>