<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
	
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php"); 
include_once($url['models_url']."otrahora_model.php"); 
include_once($url['models_url']."marcadas_model.php");
include_once("helpers.php");
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
	$cadena="disabled rel='tooltip' title='Seleccione período de tiempo'"; 
	$classcadena="class='disabled' rel='tooltip' title='Seleccione período de tiempo'";
} 

?>
<div class="row">
	<div class="col-md-12">

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->

	<div class="row">
	<div class="col-md-2">
		<b class="block-title">Período de tiempo</b>
	</div>
	
	<div class="col-md-2">
		<form class="form-inline" action="usuario2.php" name="ente">
		<div class="form-group">
    		<div class="input-group">
	      		<div class="input-group-addon" onclick="document.getElementById('datepicker2').focus();">
	      			<span class="add-on"><i class="icon-calendar"></i></span>
	      		</div>
	      		<input value="" class="form-control" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" autocomplete="off" required>
    		</div>
  		</div>
	</div>
	
	<div class="col-md-2">
		<div class="form-group">
    		<div class="input-group">
	      		<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
	      			<span class="add-on"><i class="icon-calendar"></i></span>
	      		</div>
	      		<input value=""	class="form-control" type="text" name="fecha_final" id="datepicker" placeholder="fecha final" autocomplete="off" required>
    		</div>
  		</div>
	</div>
	
	<div class="col-md-2">
		<button type="submit" class="btn btn-primary btn-lg" rel='tooltip' title="Buscar marcaciones" name="buscar" value="1"><i class="icon-search"></i></button>
		</form>
	</div>
	
	<div class="col-md-1">
		<b class="block-title">Usuario</b>
	</div>
	
	<div class="col-md-2">
		<select
		data-placeholder="Seleccione un usuario..." class="chosen-select form-control" tabindex="2"		
		onChange="javascript:window.location.href='usuario.php?id='+this.value+'&buscar=<?php echo 1;?>&fecha_final=<?php echo $fecha_final; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>';"
		name="id" <?php echo $cadena;?> required>
		<?php do{ ?>
		<option value="<?php echo $row_usuarios['id_usuario']?>"><?php echo $row_usuarios['usuario']?></option>
		<?php } while($row_usuarios=mysql_fetch_array($usuarios));?>
		</select>
	</div>
	
	
	<div class="col-md-1">
		<div class="btn-group">
			<a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-cogs"></i>
				<span class="caret"></span>
			</a>
			<ul class="dropdown-menu">
				<li <?php echo $classcadena;?>><a href="usuario.php?id=<?php echo $id_usuario;?>&buscar=<?php echo 1;?>&fecha_final=<?php echo $fecha_final; ?>&fecha_inicio=<?php echo $fecha_inicio; ?>"  rel='tooltip' title="Refresh" <?php if(!isset($fecha_final)){ ?> disabled<?php } ?>><i class="icon-refresh"></i> Refresh</a></li>
				<li <?php echo $classcadena;?>><a href="javascript:imprSelec('muestra')"><i class="icon-print"></i> Imprimir</a></li>
				<li <?php echo $classcadena;?>><a onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</a></li>
				<li><a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i> Ayuda</a></li>
			</ul>
		</div>
	</div>

	</div>
	<div class="divider"></div>
	
	
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
			<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
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

<table class="table">
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
	<th rel='tooltip' title="Legajo del usuario">Legajo</th>
	<th rel='tooltip' title="Usuario">Usuario</th>
	<th rel='tooltip' title="Fecha de ingreso">Ingreso</th>
	<th rel='tooltip' title="Hora que debe cumplir en el mes">Normales</th>
	<th rel='tooltip' title="Resultado de Horas mensuales-Horas trabajadas">50%</th>
	<th rel='tooltip' title="Horas extras que van al 100%, feriados, domingos y sábado pasado el convenio">100%</th>
	<?php do{ ?>
	<th rel='tooltip' title=""><?php echo $row_tipootra['tipootra'];?></th>
	<?php }while($row_tipootra=mysql_fetch_array($tipootra))?>
	<th rel='tooltip' title="Total de horas trabajadas en el mes">Total</th>
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
					$me=date('H:i', strtotime($row_marcacion['entrada']));
					}
				} else if($i==2){ 
					$canms=$canms+1;
					if($canms==1){
					$ms=date('H:i', strtotime($row_marcacion['entrada']));

					}
				} else if($i==3){ 
					$cante=$cante+1;
					if($cante==1){
					$te=date('H:i', strtotime($row_marcacion['entrada']));
					}
				} else if($i==4){ 
					$cants=$cants+1;
					if($cants==1){
					$ts=date('H:i', strtotime($row_marcacion['entrada']));
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
		if($config['aplicar_redondeo']==1){
			$subtotal=segundos_a_hora(redondear_minutos(pasar_hora($subtotal)))/60/60;	
		}
		
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
		<td rel='tooltip' title="Horas que el empleado debe recuperar para alcanzar el minimo de horas trabajadas">- <?php echo $resta;?></td>	
		<?php }else{ ?>
		<td rel='tooltip' title="Suma total de las horas extra al 50%"><p  class="dia label label-info"><?php echo $resta;?></p></td>	
		<?php } ?>
	
		<?php if($total_cien+$total_otrahora_cien>0){?>
		<td rel='tooltip' title="Suma total de las horas extra al 100%, suma de horas trabajadas domingos, sábado pasado el convenio o feriados"	><p  class="dia label label-info"><?php echo pasar_hora($total_cien+$total_otrahora_cien);?></p></td>
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