<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."temps_model.php");
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."otrahora_model.php");
include_once("helpers.php");


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

	if(isset($_GET['id'])){
		$id_usuario=$_GET['id'];	
	}else{
		$id_usuario=0;	
	}
	
	$totalotras=0;
	$fecha=date("d-m-Y");
	
	$usuario=getUsuario($id_usuario);
	$row_usuario = mysql_fetch_assoc($usuario);
	
	$usuarios=getUsuarios();
	$row_usuarios = mysql_fetch_assoc($usuarios);

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Valores iniciales convenios
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
	$convenio_turno=getConvenioturnos($row_usuario['id_convenio'], 'id_convenio');
	$row_convenio_turno = mysql_fetch_assoc($convenio_turno);
	$cantidad_turno=mysql_num_rows($convenio_turno);
	
	if($cantidad_turno<1){
		echo "
		<script>
			alert('El convenio al que está asociado este usuario no tiene turnos, debe completar los turnos para que los cálculos se realicen correctamente.');
		</script>";
		
		echo "
		<div class='alert alert-error'>
		  <button type='button' class='close' data-dismiss='alert'>&times;</button>
		  <h4>Atención!</h4>
		  Agregar turnos <a href='convenios_turno.php?id=".$row_usuario['id_convenio']."'> aqui</a>
		</div>";
	}else{
		
		
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

	$marcacion = getMarcaciones($id_usuario, $fecha_inicio, $fecha_final);
	$row_marcacion = mysql_fetch_assoc($marcacion);   
	$cantidad_marcacion = mysql_num_rows($marcacion);
	
	$otrahora=getOtrahora($id_usuario, $fecha_inicio, $fecha_final);
	$row_otrahora = mysql_fetch_assoc($otrahora);
		
	# Creo y completo tabla temporal para otras
	$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date, id_archivo int)";
	$res_create = mysql_query($query_create) or die(mysql_error());
	
	# Creo y completo tabla temporal para horas
	$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
	$res_create = mysql_query($query_create) or die(mysql_error());
		
	do{
		$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
		$res_ins = mysql_query($query_ins) or die(mysql_error());
	}while ($row_marcacion = mysql_fetch_array($marcacion));

	
	do{
		$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]', '$row_otrahora[id_archivo]')";
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
<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div class="row well">
<div class="col-md-12">

	<div class="row">
		<div class="col-md-1">
			<b>Usuario</b>
		</div>
		<div class="col-md-2">
			<select 
			data-placeholder="Seleccione un usuario..." class="chosen-select form-control" tabindex="2"
			onChange="javascript:window.location.href='usuario.php?id='+this.value+'&buscar=<?php  echo 1;?>&fecha_final=<?php echo $fecha_final; ?>&fecha_inicio=<?php  echo $fecha_inicio; ?>';"
			name="id" <?php echo $cadena;?> required>
			<option value=""></option>
			<?php   do{ 
			if($id_usuario==$row_usuarios['id_usuario']){
			?>
			<option value="<?php   echo $row_usuarios['id_usuario']?>" selected><?php   echo $row_usuarios['usuario']?></option>
			<?php  }else{?>
			<option value="<?php   echo $row_usuarios['id_usuario']?>"><?php   echo $row_usuarios['usuario']?></option>
			<?php  } 
			} while($row_usuarios=mysql_fetch_array($usuarios));?>
			</select>
		</div>	
		
		<div class="col-md-2">
			<b>Periodo de tiempo</b>
		</div>
		
		<div class="col-md-2">
			<form class="form-inline" action="usuario.php" name="ente">
			<input type="hidden" name="id" value="<?php   echo $id_usuario?>">
			<div class="form-group">
    			<div class="input-group">
      				<div class="input-group-addon" onclick="document.getElementById('datepicker2').focus();">
      					<span class="add-on">
      						<i class="icon-calendar"></i>
      					</span>
      				</div>
      				<input value="<?php echo date('d-m-Y', strtotime($fecha_inicio)); ?>" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" class="form-control" autocomplete="off" required>
    			</div>
  			</div>
		</div>
		<div class="col-md-2">
			<div class="form-group">
    			<div class="input-group">
      				<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
      					<span class="add-on">
      						<i class="icon-calendar"></i>
      					</span>
      				</div>
      				<input value="<?php   echo date('d-m-Y', strtotime($fecha_final)); ?>"	type="text" name="fecha_final" id="datepicker" placeholder="fecha final" class="form-control" autocomplete="off" required>
    			</div>
  			</div>
		</div>
		
		<div class="col-md-1">
			<button type="submit" class="btn btn-default" title="Buscar" name="buscar" value="1">
				<i class="icon-search"></i> Buscar
			</button>
			</form>
		</div>
		
		<div class="col-md-2">
			<center>
			<div class="btn-group">
				<a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
					<i class="icon-cogs"></i>
					<span class="caret"></span>
				</a>
				<ul class="dropdown-menu">
				<li <?php echo  $classcadena;?>><a href="usuario.php?id=<?php echo  $id_usuario;?>&buscar=<?php echo  1;?>&fecha_final=<?php echo  $fecha_final; ?>&fecha_inicio=<?php echo  $fecha_inicio; ?>"  title="Refresh" <?php   if(!isset($fecha_final)){ ?> disabled<?php   } ?>><i class="icon-refresh"></i> Refresh</a></li>
				<li <?php echo  $classcadena;?>>
					<a href="usuario_reporte.php?
					id=<?php echo $id_usuario;?>&
					nombre=<?php echo $id_usuario;?>&
					buscar=<?php echo  1;?>&
					fecha_final=<?php echo  $fecha_final; ?>&
					fecha_inicio=<?php echo $fecha_inicio; ?>" 
					title="Exportar" target="_blank" <?php if(!isset($fecha_final)){ ?> disabled <?php } ?> >
					<i class="icon-print"></i> Imprimir</a></li>
				<li <?php echo  $classcadena;?>>
					<a href="exportar/usuario.php?
					id=<?php echo $id_usuario;?>&
					nombre=<?php echo $id_usuario;?>&
					buscar=<?php echo  1;?>&
					fecha_final=<?php echo  $fecha_final; ?>&
					fecha_inicio=<?php echo $fecha_inicio; ?>" 
					title="Exportar" target="_blank" <?php if(!isset($fecha_final)){ ?> disabled <?php } ?> >
					<i class="icon-upload-alt"></i> Exportar</a></li>
				<li <?php echo  $classcadena;?>><a href="#" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-table"></i> Tabla</a></li>
				<li><a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i> Ayuda</a></li>
				</ul>
			</div>
			</center>
		</div>
		
	</div>
	
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
</div>
</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<div class="row">
<div class="col-md-12">

	<?php  
	
	if(isset($fecha_inicio) && empty($id_usuario)){
	echo " <div class='alert alert-info'>
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				Falta seleccionar usuario
		 	</div>
		 	<br>";
	} else if(isset($_GET['buscar'])){
	if($fecha_inicio>$fecha_final){
		echo 	"<div class='alert alert-error'> 
				<button type='button' class='close' data-dismiss='alert'>&times;</button>
				La fecha de inicio es mayor a la fecha final, corríjalo por favor</div>";
	}else{
	
	
	?>

	<div id="muestra">
	<table border="1" class="table">
	<tbody>
	<tr>
		<th title="Nombre de los usuarios">Nombre</th>
		<td><?php   echo $row_usuario['usuario']?></td>
		<th title="Departamento al que pertenecen">Sector</th>
		<td><?php   echo $row_usuario['departamento']?></td>
		<th title="Legajo de los usuarios">Legajo</th>
		<td><?php   echo $row_usuario['legajo']?></td>
	</tr>
	<tr>
		<th title="Fecha inicio">Fecha Inicio</th>
		<td><?php   echo date('d-m-Y', strtotime($fecha_inicio))?></td>
		<th title="Fecha final">Fecha Final</th>
		<td><?php   echo date('d-m-Y', strtotime($fecha_final))?></td>
		<th title="Cantidad de marcaciones">Cantidad</th>
		<td><?php   echo $cantidad_marcacion;?></td>
	</tr>
	</tbody>
	</table>
	<br>
	
	<div class="carga">
	<p>Cargando datos</p>
	<img  src="imagenes/loading.gif" />
	</div>
</div>
</div>
</div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla marcaciones
----------------------------------------------------------------------			
--------------------------------------------------------------------->	

<div class="row">
<div class="col-md-12">

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
		<?php if($config['mostar_marcada']==1 && $config['aplicar_redondeo']==1){ ?>
		<th title="Calculo de horas laborales">Horas</th>
		<?php } ?>
		<?php if($config['aplicar_redondeo']==1){ ?>
		<th title="Redondeo de horas">R</th>
		<?php } ?>
		<th title="Otro tipo">Otros</th>
	</thead>
	
	<tbody>
	<?php   
	$total_normales=0;
	$total_cien=0;
	$total_cincuenta=0;
	$subtotal=0;
	$total=0;
	$limite=5;
	
	
	$total_redondeo=0;
	$total_cien_redondeo=0;
	$total_cincuenta_redondeo=0;
	$hora_redondeo=0;
	$minuto_redondeo=0;
	
	foreach($arrayFechas as $valor){?>
		<tr>	
			<?php  
				list ($clase, $title, $esferiado) = esferiado($valor);
				$dia=devuelve_dia($valor);
					
				if($dia=="Domingo" || $esferiado==1){
				}else{
					if($dia!="Sábado"){
						$total_normales=$total_normales+$row_usuario['semana'];
					}else{
						$total_normales=$total_normales+$row_usuario['sabado'];
					}
				}
			?>
			
			<td><p class="<?php echo  $clase;?>" title="<?php echo  $title;?>"><?php echo  $valor;?></p></td>
			<td><p class="dia"><?php echo  $dia;?></p></td>
	
			<?php   
				$me=0;
				$ms=0;
				$te=0;
				$ts=0;
	
				for ($i = 0; $i <= 4; $i++) {
					
					$marcacion=getTempFecha($valor, $i);
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
								
					$registro=tipoMarcacion($row_marcacion, $cantidad_parametros); ?>
			<td class="td_center">
				<p class="<?php echo $registro['label_class']; ?>" title="<?php echo $registro['label_title']; ?>">
					<a class="<?php echo $registro['a_class']; ?>" onClick="abrirVentana('edit.php?id=<?php echo $row_usuario['id_usuario']?>&fecha=<?php echo $valor?>')">
						<?php echo $registro['marcacion']; ?>
					</a>
				</p>
			</td>
			<?php 	
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
				
					if($config['aplicar_redondeo']==1){
						$total=$total+segundos_a_hora(redondear_minutos(pasar_hora($subtotal)))/60/60;	
					}else{
						$total=$total+$subtotal;	
					}
				
				}else{
					$subtotal=0;
				}
				
			if($subtotal>0){ ?>
				<td><?php   echo pasar_hora($m)." + ".pasar_hora($t) ?></td>
					
				<?php if($config['mostar_marcada']==1 && $config['aplicar_redondeo']==1){ ?>
					<td><?php   echo pasar_hora($subtotal); ?></td>	
				<?php } ?>  
				
				<?php if($config['aplicar_redondeo']==1){ ?>
					<td><?php echo redondear_minutos(pasar_hora($subtotal)); ?></td>
						<?php $subtotal=segundos_a_hora(redondear_minutos(pasar_hora($subtotal)))/60/60; ?>
				<?php } ?> 
			<?php } else {?>
				<td> - </td>
				<?php if($config['mostar_marcada']==1 && $config['aplicar_redondeo']==1){ ?>
					<td> - </td>
				<?php } ?>  
				
				<?php if($config['aplicar_redondeo']==1){ ?>
					<td> - </td>
				<?php } ?> 
	
			<?php  }
			
				$otrahora= getTempFechaOtra($valor, $id_usuario);
				$row_otrahora = mysql_fetch_assoc($otrahora);
				$cantidad=mysql_num_rows($otrahora);
				
				if($cantidad>0){
					$totalotras=$totalotras+$row_otrahora['horas'];
				}
				
				$registro=tipoOtra($row_otrahora, $cantidad);
			?>
			<td>
				<p class="<?php echo $registro['label_class']; ?>">
					<a class="<?php echo $registro['a_class']; ?>" title="<?php echo $registro['a_title']; ?>" onClick="abrirVentana('edit_otros.php?id=<?php echo $row_usuario['id_usuario']?>&fecha=<?php echo $valor?>')">
						<?php echo $registro['marcacion']; ?>
					</a>
				</p>
			</td>
		<?php  
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
			
		?>	
		</tr>
	<?php   }
	
	//elimino las tablas temporaria
	$query_drop = "DROP TABLE temp";
	$res_drop = mysql_query($query_drop) or die(mysql_error());
	
	$query_drop = "DROP TABLE tempotra";
	$res_drop = mysql_query($query_drop) or die(mysql_error());
	
	?>
	
	</tbody>
	</table>
	</div>
</div>




<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla totales
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<hr>
<div class="row">
<div class="col-md-8 col-md-offset-2">
<h2>Totales</h2>
	<?php  
		list ($resta, $signo) = pasar_hora_resta($total_cincuenta);
		if($signo==0){
			$porcentaje_cien=$total_normales;
		}else{
			$porcentaje_cien=$total+round($totalotras,2);
		}
	?>
	
	<b>Horas a cumplir:</b> <?php echo pasar_hora($total_normales);?>
	<div class="progress">
	 	<div class="progress-bar" title="Suma total de las horas normales" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $total_normales*100/$porcentaje_cien?>%;">
			<?php   echo pasar_hora($total_normales);?>
	  	</div>
	</div>
	
	<?php if($total>0){ ?>
		<b>Horas normales:</b> <?php   echo pasar_hora($total);?>
		<div class="progress">
		 	<div class="progress-bar progress-bar-success" title="Suma total de las horas normales" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $total*100/$porcentaje_cien?>%;">
		    	<?php   echo pasar_hora($total);?>
		  	</div>
		</div>
	<?php } ?>
		
	
	<?php if(round($totalotras,2)>0){ ?>
		<b>Total de otras horas:</b> <?php echo round($totalotras,2);?>
		<div class="progress">
		 	<div class="progress-bar progress-bar-success" title="Es la suma de las 'otros', son horas de enfermedad, accidente, ausencias, otros" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo round($totalotras,2)*100/$porcentaje_cien?>%;">
		    	<?php   echo round($totalotras,2);?>
		  	</div>
		</div>
	<?php } ?>
	
	
	<?php if(pasar_hora($total_cien)>0){ ?>
		<b>Horas extra 100% :</b> <?php echo pasar_hora($total_cien);?>
		<div class="progress">
		 	<div class="progress-bar progress-bar-success progress-bar-striped" title="Suma total de las horas extra al 100%, suma de horas trabajadas domingos, sabado pasado el convenio o feriados" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $total_cien*100/$porcentaje_cien?>%;">
		   		<?php echo pasar_hora($total_cien);?>
		  	</div>
		</div>
	<?php } ?>
	
	<?php
	
	 if($signo==0){
	 	$suma_final=$total_normales-$resta;
		$title='Horas que el empleado debe recuperar para alcanzar el minimo de horas trabajadas';
		$final_title='horas a favor de la empresa';
		$progress='danger';
	 }else{
	 	$suma_final=pasar_hora($total+round($totalotras,2))-$resta;
		$title='Suma total de las horas extra al 50%';
		$final_title='horas extra al 50%';
		$progress='warning';
	 }
	?>
	
	<b>Final:</b> <?php echo $resta." ".$final_title?>
	<div class="progress">
 	<div class="progress-bar progress-bar-info" title="<?php echo $title?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo $suma_final*100/$porcentaje_cien?>%;">
    <?php echo pasar_hora($suma_final);?>
  	</div>
  	
  	<div class="progress-bar progress-bar-<?php echo $progress?>" title="<?php echo $title?>" role="progressbar" aria-valuenow="60" aria-valuemin="0" aria-valuemax="100" style="width: <?php echo 100-$suma_final*100/$porcentaje_cien?>%;">
    <?php echo $resta;?>
  	</div>
	</div>
<?php  }//cierra el if de fechas inicio>final
}else{/*
echo 	"<div class='alert alert-info'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
		Seleccione período de tiempo
		</div>";
*/
}

?>

</div><!--cierra el class="span12" -->
</div><!--cierra el row -->

<div class="row">
<div class="span12">


<?php   include_once("footer.php");?> 


</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->


</body>


<script type="text/javascript">
	function cargar(){
		opener.location.reload();
		window.close();
	}
</script>