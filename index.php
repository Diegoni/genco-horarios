<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}

include_once("menu.php"); 
include_once($url['models_url']."marcadas_model.php"); 
include_once($url['models_url']."parametros_model.php"); 
include_once($url['models_url']."turnos_model.php"); 
include_once($url['models_url']."tipos_model.php"); 
include_once($url['models_url']."updates_model.php"); 
include_once($url['models_url']."usuarios_model.php"); 
include_once($url['models_url']."departamentos_model.php"); 
include_once($url['models_url']."otrahora_model.php"); 
include_once($url['models_url']."logs_model.php");
include_once($url['models_url']."temps_model.php"); 

$bandera=0;
if(isset($_GET['fecha'])){
	$fecha=$_GET['fecha'];
	$fecha_americana=date( "Y-m-d", strtotime($_GET['fecha']));
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}else{
	$fecha= date("d-m-Y");
	$fecha_americana=date("Y-m-d");
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Filtros para busqueda en la tabla
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['empleado'])){
	$usuario=getUsuarios($_GET['empredo'],'usuario');
	$row_usuario = mysql_fetch_assoc($usuario);
}else{
	$usuario=getUsuarios();
	$row_usuario = mysql_fetch_assoc($usuario);
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Modificar parametros
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
	if (isset($_POST['parametros'])){
		$parametros2=getParametros();
		$row_parametros2 = mysql_fetch_assoc($parametros2);
		$numero_parametros2 = mysql_num_rows($parametros2);
		
		do {
			$registro=array(
							'id_turno'		=> $_POST['id_turno'.$row_parametros2['id_parametros']],
							'id_tipo'		=> $_POST['id_tipo'.$row_parametros2['id_parametros']],
							'inicio'		=> $_POST['inicio'.$row_parametros2['id_parametros']],
							'final'			=> $_POST['final'.$row_parametros2['id_parametros']],
							'considerar'	=> $_POST['considerar'.$row_parametros2['id_parametros']],
							'id_parametro'	=> $row_parametros2['id_parametros']
			);
			updatePrametro($registro);
		}while ($row_parametros2 = mysql_fetch_array($parametros2));
		echo
		"<div class='alert alert-success'>
  			<button type='button' class='close' data-dismiss='alert'>&times;</button>
  			Los parametros se han actualizado correctamente
		</div>
		 ";
	}


?>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div class="row">
	<div class="col-md-12">
	<div class="row">
		
		
		<div class="col-md-2">
			<center>
				<b class="block-title">Marcaciones del día</b>
			</center>
		</div>
		
		
		<div class="col-md-2">
			<center>
				<label rel='tooltip' title="Fecha con la que se esta trabajando" rel="tooltip">
					<?php echo  $fecha;?>
				</label>
			</center>
		</div>
		
		
		<div class="col-md-4">
			<center>
			<form class="form-inline" action="index.php" name="ente">
			
			<div class="form-group">
				<div class="input-group">
					<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
						<span class="add-on">
							<i class="icon-calendar"></i>
						</span>
					</div>
					<input class="form-control" type="text" name="fecha" id="datepicker" placeholder="ingrese fecha" onkeypress="return false" autocomplete="off" required>
		    	</div>
		  	</div>
			
			<div class="form-group">
				<button type="submit" class="btn btn-primary btn-lg" rel='tooltip' title="Buscar marcaciones">
					<i class="icon-search"></i>
				</button>
			</div>
			</form>
			</center>
		</div>
		
		
		<div class="col-md-4">
			<center>
			<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
			<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
		
			
			<div class="btn-group">
			  <a class="btn btn-default dropdown-toggle" data-toggle="dropdown" href="#">
				<i class="icon-cogs"></i>
				<span class="caret"></span>
			  </a>
			  <ul class="dropdown-menu">
				<li><a href="#openModal" rel='tooltip' title="Parametros de configuración"><i class="icon-time"></i> Parametros</a></li>
				<li><a href="index.php?fecha=<?php echo $fecha;?>" rel='tooltip' title="Volver a cargar el sitio" ><i class="icon-refresh"></i> Refresh</a></li>
				<?php if($bandera==1){ ?>
					<form class="form-inline" action="index.php" name="importar">
					<input type="hidden" name="fecha" value="<?php echo $fecha;?>">
					<li><button type="submit" rel='tooltip' title="Actualice la base de datos" name="actualizar" value="1"><i class="icon-download-alt"></i> Actualizar</button></li>
					<form class="form-inline" action="index.php" name="importar">
				<?php }else{?>
					<li class="disabled"><a href="" rel='tooltip' title="Los datos ya estan actalizados" name="actualizar" value="1"><i class="icon-download-alt"></i> Actualizar</a></li>
				<?php }?>
				<!--<li><a href='#' class='show_hide' rel='tooltip' title='Más detalles en la búsqueda'><i class="icon-chevron-sign-down"></i> Búsqueda</a></li>-->
				<li><a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i> Ayuda</a></li>
				</form>
			  </ul>
			</div>
			</center>
			
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
			<p>Esta tabla muestra todas las marcaciones que se hicieron para una fecha determinada.</p>
			<p>Las marcaciones que aparecen en rojo tienen algún tipo de conflicto.</p>
			<p>Las marcaciones que aparecen en verde están modificadas y las que aparecen en amarillo están dada de alta por el sistema.</p>
			<p>Las marcaciones se pueden editar desde la columna “Editar”.</p>
			<p>Se pueden agregar otro tipo de horas desde “Otros”.</p>
			<p>Para ver las de un usuario determinado, solo debe seleccionar al usuario.</p>
		</div>
		<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
		</div>
	</div>
	</div>
</div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla temporales
----------------------------------------------------------------------			
--------------------------------------------------------------------->  

<?php
if(isset($_GET['fecha'])){	
	$marcacion		= getMarcaciones(NULL, $fecha_americana);
	$row_marcacion	= mysql_fetch_assoc($marcacion);
	
	$otrahora		= getOtrahoras($fecha_americana);
	$row_otrahora	= mysql_fetch_assoc($otrahora);
	$cantidad_otra	= mysql_num_rows($otrahora);
	
	$query_create	= "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
	$res_create		= mysql_query($query_create) or die(mysql_error());
	
	$query_create	= "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date, id_archivo int)";
	$res_create		= mysql_query($query_create) or die(mysql_error());
		
	do{
		/*
		$query_ins	= "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
		$res_ins	= mysql_query($query_ins) or die(mysql_error());*/
		if($array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_id_marcada']!=""){
			$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_cantidad']	= $array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_cantidad']+1;
		}else{
			$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_cantidad']	= 1;
		}
		
		$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_id_marcada']	= $row_marcacion['id_marcada'];
		$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_entrada']		= $row_marcacion['entrada'];
		$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_id_usuario']	= $row_marcacion['id_usuario'];
		$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_id_parametros']	= $row_marcacion['id_parametros'];
		$array_marcacion[$row_marcacion['id_usuario'].'_'.$row_marcacion['id_parametros'].'_id_estado']		= $row_marcacion['id_estado'];
		
	}while ($row_marcacion = mysql_fetch_array($marcacion));
	
	if($cantidad_otra>0){	
		do{
			$query_ins	= "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]', '$row_otrahora[id_archivo]')";
			$res_ins	= mysql_query($query_ins) or die(mysql_error());
		}while ($row_otrahora = mysql_fetch_array($otrahora));
	}
}
?>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla 
----------------------------------------------------------------------			
--------------------------------------------------------------------->  
	
         
<div class="row">
<div class="col-md-12">

<?php if(isset($_GET['fecha'])){ ?>
<div id="muestra">
	<table class="table table-hover" id="example">
		<thead>
			<th rel='tooltip' title="Legajo de los usuarios">Legajo</th>
			<th rel='tooltip' title="Nombre de los usuarios">Nombre</th>
			<th rel='tooltip' title="Departamento al que pertenecen">Sector</th>
			<th rel='tooltip' title="sin definir">sd</th>
			<th rel='tooltip' title="Mañana - Entrada">m-e</th>
			<th rel='tooltip' title="Mañana - Salida">m-s</th>
			<th rel='tooltip' title="Tarde - Entrada">t-e</th>
			<th rel='tooltip' title="Tarde - Salida">t-s</th>
			<th rel='tooltip' title="Otro tipo">Otros</th>
		</thead>
	
		<tbody>
	<?php do{?>
			<tr>
				<td><?php echo $row_usuario['legajo']?></td>
				<td><a href="usuario.php?id=<?php echo $row_usuario['id_usuario']?>&fecha=<?php echo $fecha;?>&buscar=2" class="ayuda-boton btn btn-default"><?php echo $row_usuario['usuario']?></a></td>
				<td><?php echo $row_usuario['departamento']?></td>
					<?php 
					for ($i = 0; $i <= 4; $i++) {
							
						//$marcacion				= getTemp($row_usuario['id_usuario'], $i);
						//$row_marcacion			= mysql_fetch_assoc($marcacion);
						//$cantidad_parametros	= mysql_num_rows($marcacion);
						
						$row_marcacion	= array(
							'id_marcada'	=> $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_id_marcada'],
							'entrada'		=> $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_entrada'],
							'id_usuario'	=> $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_id_usuario'],
							'id_parametros'	=> $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_id_parametros'],
							'id_estado'		=> $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_id_estado']
						);
						
						if($row_marcacion['entrada']!=""){
							$cantidad_parametros = $array_marcacion[$row_usuario['id_usuario'].'_'.$i.'_cantidad'];	
						}else{
							$cantidad_parametros = 0;
						}
						
						$registro			= tipoMarcacion($row_marcacion, $cantidad_parametros);
					?>
				<td>
					<p class="<?php echo $registro['label_class']; ?>" rel='tooltip' title="<?php echo $registro['label_title']; ?>">
						<a class="<?php echo $registro['a_class']; ?>" onClick="abrirVentana('edit.php?id=<?php echo $row_usuario['id_usuario']?>&fecha=<?php echo $fecha_americana?>')">
							<?php echo $registro['marcacion']; ?>
						</a>
					</p>
				</td>
					<?php }//cierra el for?>
					<?php
						$otrahora=getTempotra($row_usuario['id_usuario']);
						$row_otrahora = mysql_fetch_assoc($otrahora);
						$cantidad=mysql_num_rows($otrahora);
						
						$registro=tipoOtra($row_otrahora, $cantidad);
					?>
				<td>
					<p class="<?php echo $registro['label_class']; ?>">
						<a class="<?php echo $registro['a_class']; ?>" rel='tooltip' title="<?php echo $registro['a_title']; ?>" onClick="abrirVentana('edit_otros.php?id=<?php echo $row_usuario['id_usuario']?>&fecha=<?php echo $fecha_americana?>')">
							<?php echo $registro['marcacion']; ?>
						</a>
					</p>
				</td>
					
			</tr>
		<?php }while ($row_usuario = mysql_fetch_array($usuario));
		
		//elimino las tablas temporaria
		$query_drop = "DROP TABLE temp";
		$res_drop = mysql_query($query_drop) or die(mysql_error());
		
		$query_drop = "DROP TABLE tempotra";
		$res_drop = mysql_query($query_drop) or die(mysql_error());
		
		?>
		</tbody>
	</table>
<?php }else{ ?>
	<div class='alert alert-info'>
		<button type='button' class='close' data-dismiss='alert'>&times;</button>
			Seleccione fecha
	</div>
	<br> 	
<?php } ?>
</div>
</div>



<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Footer
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class="row">
<div class="span12">

<?php include_once("footer.php");?>

</div><!--cierra el class="span12" -->
</div><!--cierra el row -->


</div><!--cierra el class="container"-->

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Parametros
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div id="openModal" class="modalDialog">
	<div>
		<a href="#closes" rel='tooltip' title="Cerrar" class="closes">X</a>
		<h4>Parámetros de configuración</h4>
		<p>Estos son los valores que filtran las entradas y salidas</p>
		<p>
	<?php
	$parametros=getParametros();
	$row_parametros = mysql_fetch_assoc($parametros);
	?>
	
	<div class="container; celeste">
	<form action="index.php" method="post" > 
	<table class="table table-hover">
		<thead>
		<tr>
			<td>Turno</td>
			<td>Tipo</td>
			<td>Desde</td>
			<td>Hasta</td>
			<td>Considerar</td>
			<td>Minutos</td>
		</tr>
		</thead>
	<?php
	do{ ?>	
	<tr>
		<td>
			<?php
			$turno=getTurnos();
			$row_turno = mysql_fetch_assoc($turno);
			do{
				if($row_turno['id_turno']==$row_parametros['id_turno']){?>
					<input type="hidden" value="<?php echo $row_turno['id_turno']?>" name="id_turno<?php echo $row_parametros['id_parametros']?>">
					<?php echo $row_turno['turno'];
				} 
			}while ($row_turno = mysql_fetch_array($turno))
			?>
		</td>
		<td><?php
			$tipo=getTipos();
			$row_tipo = mysql_fetch_assoc($tipo);
			do{
			if($row_tipo['id_tipo']==$row_parametros['id_tipo']){
			?>
			<input type="hidden" value="<?php echo $row_tipo['id_tipo']?>" name="id_tipo<?php echo $row_parametros['id_parametros']?>">
			<?php echo $row_tipo['tipo']?>
			<?php }
			}while ($row_tipo = mysql_fetch_array($tipo))
			?>
		</td>
		<td><input type="time" class="form-control" name="inicio<?php echo $row_parametros['id_parametros']?>" id="inicio<?php echo $row_parametros['id_parametros']?>" value="<?php echo $row_parametros['inicio']?>" required></td>
		<td><input type="time" class="form-control" name="final<?php echo $row_parametros['id_parametros']?>"  id="final<?php echo $row_parametros['id_parametros']?>"value="<?php echo $row_parametros['final']?>" required></td>
		<script type="text/javascript">
			$('#inicio<?php echo $row_parametros['id_parametros']?>').timepicker({
  				 showAnim: 'blind'
			});
			
			$('#final<?php echo $row_parametros['id_parametros']?>').timepicker({
  				 showAnim: 'blind'
			});
		</script>
		<td><input type="range" class="form-control" name="considerar<?php echo $row_parametros['id_parametros']?>" value="<?php echo $row_parametros['considerar']?>" min="1" max="30" id="slider<?php echo $row_parametros['id_parametros']?>" onchange="printValue('slider<?php echo $row_parametros['id_parametros']?>','rangeValue<?php echo $row_parametros['id_parametros']?>')" required></td>
		<td><input id="rangeValue<?php echo $row_parametros['id_parametros']?>" type="text" class="form-control" disabled></td>
			
	</tr>
	<?php 	}while ($row_parametros = mysql_fetch_array($parametros))?>
	<tr>
		<td colspan="6">
			<center>
			<input type="hidden" name="id" value="<?php echo $id?>">
			<input type="submit" class="btn btn-default" name="parametros" value="Modificar"  id="parametros">
			<a class="btn btn-danger" href="" rel='tooltip' title="no guarda los cambios realizados" onClick="cerrarse()">Cancelar</a>
			</center>
		</td>
	</tr>
	</table>
	
	</div>
	</form>
	</div> 
		
		</p>
	</div>
</div>