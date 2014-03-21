<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
	
include_once("menu.php"); 

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$id_usuario=$_GET['id'];
$totalotras=0;
$fecha=date("d-m-Y");

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.nombre as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE id_usuario='$id_usuario'";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.nombre as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuarios=mysql_query($query) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.nombre as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuarios2=mysql_query($query) or die(mysql_error());
$row_usuarios2 = mysql_fetch_assoc($usuarios2);


$query="SELECT *				
		FROM `tipootra` 
		ORDER BY tipootra.id_tipootra";   
$tipootra=mysql_query($query) or die(mysql_error());
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
         $dia="Miercoles";
         break;
	case 4:
         $dia="Jueves";
         break;
	case 5:
         $dia="Viernes";
         break;
	case 6:
         $dia="Sabado";
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
		fecha <= '$fecha_final'";   
		$otrahora=mysql_query($query) or die(mysql_error());
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
	$cadena="disabled title='Seleccione periodo de tiempo'"; 
	$classcadena="class='disabled' title='Seleccione periodo de tiempo'";
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
	<form class="form-inline" action="usuario2.php" name="ente">
		<input type="hidden" name="id" value="<? echo $id_usuario?>">
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker2').focus();"><i class="icon-calendar"></i></span>
			<input <? if(isset($fecha_inicio)){?>
			value="<? echo date('d-m-Y', strtotime($fecha_inicio)); ?>"
			<?}?>
			type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" autocomplete="off" required>
		</div></b>
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker').focus();"><i class="icon-calendar"></i></span>
			<input <? if(isset($fecha_final)){?>
			value="<? echo date('d-m-Y', strtotime($fecha_final)); ?>"
			<?}else{?>
			value="<? echo $fecha;?>" 
			<?}?>
			type="text" name="fecha_final" id="datepicker" placeholder="fecha final" autocomplete="off" required>
		</div></b>
		<button type="submit" class="btn" title="Buscar" name="buscar" value="1"><i class="icon-search"></i></button>
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
		<li <?= $classcadena;?>><a href="exportar/usuario2.php?id=<?= $id_usuario;?>&nombre=<?= $id_usuario;?>&buscar=<?= 1;?>&fecha_final=<?= $fecha_final; ?>&fecha_inicio=<?= $fecha_inicio; ?>" title="Exportar" target="_blank" <? if(!isset($fecha_final)){ ?> disabled<? } ?>><i class="icon-upload-alt"></i> Exportar</a></li>
		<li class="divider"></li>
		<li><a href="genco-usuarios/index.php" title="Usuarios"><i class="icon-folder-open"></i> Usuarios</a></li>
		<li><a href="index.php" title="Inicio" ><i class="icon-home"></i> Inicio</a></li>	
	  </ul>
	</div>
	</td>
	
	
	
	</tr>
	</table>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	


<? if(isset($_GET['buscar'])){
if($fecha_inicio>$fecha_final){
	echo 	"<div class='alert alert-error'> 
			<button type='button' class='close' data-dismiss='alert'>&times;</button>
			La fecha de inicio es mayor a la fecha final, corríjalo por favor</div>";
}else{

?>
<div id="target">
<div id="muestra">

<table   border="1" id="table" class="sortable">
<thead>
	<th title="Legajo del usuario"><h3>Legajo</h3></th>
	<th title="Usuario"><h3>Usuario</h3></th>
	<th title="Fecha de inicio"><h3>desde</h3></th>
	<th title="Fecha final"><h3>hasta</h3></th>
	<th title="Horas normales"><h3>Horas</h3></th>
	<? do{ ?>
	<th title=""><h3><?= $row_tipootra['tipootra'];?></h3></th>
	<? }while($row_tipootra=mysql_fetch_array($tipootra))?>
	<th title="Horas que van al 50%"><h3>50%</h3></th>
	<th title="Horas que van al 100%"><h3>100%</h3></th>
</thead>

<tbody>
<?
//recorremos todos los usuarios
do{
$total=0;
$totalotras;
$id_usuario=$row_usuarios2['id_usuario'];
?>
<tr>
	<td><?= $row_usuarios2['legajo'];?></td>
	<td><?= $row_usuarios2['usuario'];?></td>
	<td><?= $fecha_inicio;?></td>
	<td><?= $fecha_final;?></td>
<?
foreach($arrayFechas as $valor){
	
		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i
				AND id_usuario=$id_usuario";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			// Esta funcion redondea segun los la tabla limites de la base de datos, se pidio que se sacara
			//$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));

			if($cantidad_parametros==0){
				 
				if($i==1){
					$me=0;
				} else if($i==2){ 
					$ms=0;
				} else if($i==3){ 
					$te=0;
				} else if($i==4){ 
					$ts=0;
				}

			}else{
						
				if($i==1){
					$me=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==2){ 
					$ms=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==3){ 
					$te=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==4){ 
					$ts=date('H:i', strtotime($row_marcacion['entrada']));
				}
						
			}//cierra el else
		}//cierra el for
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
		
} 
if($total>0){ ?>
<td><?= pasar_hora($total); ?></td>
<? } else { ?>
<td> - </td>

<?}			$query="SELECT *				
					FROM `tipootra` 
					ORDER BY tipootra.id_tipootra";   
			$tipootra2=mysql_query($query) or die(mysql_error());
			$row_tipootra2 = mysql_fetch_assoc($tipootra2);
	
	$total_otrahora=0;
	do{
	$suma_otrahora=0;
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
	do{
	$suma_otrahora=$suma_otrahora+$row_otrahora['horas'];
	}while($row_otrahora=mysql_fetch_array($otrahora));
	$total_otrahora=$total_otrahora+$suma_otrahora;
	if($suma_otrahora==0){ ?>
	<td> - </td>		
	<?}else{?>
	<td><?= $suma_otrahora;?></td>		
<? 	}
	}while($row_tipootra2=mysql_fetch_array($tipootra2));

	if($total_otrahora==0){ ?>
	<td> - </td>		
	<?}else{?>
	<td><?= $total_otrahora;?></td>		
<? 	} ?>
		<td> - </td>	
		</tr>
<?}while($row_usuarios2=mysql_fetch_array($usuarios2));


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
</div>

 
           
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
<?}//cierra el if de fechas inicio>final
}else{
echo 	"<div class='alert alert-info'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		Seleccione periodo de tiempo
		</div>";

}?>