<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($url['$models_url']."convenios_model.php");   
include_once($url['$models_url']."convenio_turnos_model.php");
include_once($url['models_url']."mensajes_model.php");
include_once("helpers.php");

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nuevo convenio turno
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if(isset($_GET['insert'])){
	$lunes=0;
	$martes=0;
	$miercoles=0;
	$jueves=0;
	$viernes=0;
	$sabado=0;
	$domingo=0;
	$redondeo=0;
	$id_estado=1;
	
	if(isset($_GET['lunes'])){
		$lunes=1;
	}
	if(isset($_GET['martes'])){
		$martes=1;
	}
	if(isset($_GET['miercoles'])){
		$miercoles=1;
	}	
	if(isset($_GET['jueves'])){
		$jueves=1;
	}	
	if(isset($_GET['viernes'])){
		$viernes=1;
	}	
	if(isset($_GET['sabado'])){
		$sabado=1;
	}	
	if(isset($_GET['domingo'])){
		$domingo=1;
	}	
	if(isset($_GET['redondeo'])){
		$redondeo=1;
	}	
	
	$datos=array(	'id_convenio'=>$_GET['id'],
					'entrada'=>$_GET['entrada'],
					'salida'=>$_GET['salida'],
					'limite'=>$_GET['limite'],
					'id_estado'=>$id_estado,
					'lunes'=>$lunes,
					'martes'=>$martes,
					'miercoles'=>$miercoles,
					'jueves'=>$jueves,
					'viernes'=>$viernes,
					'sabado'=>$sabado,
					'domingo'=>$domingo,
					'redondeo'=>$redondeo	
	);		
		
	insertConvenioturno($datos);
	echo getMensajes('insert', 'ok', 'Convenio Turno', $_GET['convenio']);
}


/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Modificar Turno
----------------------------------------------------------------------
--------------------------------------------------------------------*/	


if(isset($_GET['edit'])){
	$lunes=0;
	$martes=0;
	$miercoles=0;
	$jueves=0;
	$viernes=0;
	$sabado=0;
	$domingo=0;
	$redondeo=0;
	$id_estado=1;
	
	if(isset($_GET['lunes'])){
		$lunes=1;
	}
	if(isset($_GET['martes'])){
		$martes=1;
	}
	if(isset($_GET['miercoles'])){
		$miercoles=1;
	}	
	if(isset($_GET['jueves'])){
		$jueves=1;
	}	
	if(isset($_GET['viernes'])){
		$viernes=1;
	}	
	if(isset($_GET['sabado'])){
		$sabado=1;
	}	
	if(isset($_GET['domingo'])){
		$domingo=1;
	}	
	if(isset($_GET['redondeo'])){
		$redondeo=1;
	}	
	
	$datos=array(	'id_convenio_turno'=>$_GET['id_convenio_turno'],
					'entrada'=>$_GET['entrada'],
					'salida'=>$_GET['salida'],
					'limite'=>$_GET['limite'],
					'id_estado'=>$id_estado,
					'lunes'=>$lunes,
					'martes'=>$martes,
					'miercoles'=>$miercoles,
					'jueves'=>$jueves,
					'viernes'=>$viernes,
					'sabado'=>$sabado,
					'domingo'=>$domingo,
					'redondeo'=>$redondeo	
	);		
		
	updateConvenioturno($datos);
	echo getMensajes('update', 'ok', 'Convenio Turno', $_GET['convenio']);
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Turno
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['delete'])){
	deleteConvenioturno($_GET['delete']);
	echo getMensajes('delete', 'ok', 'Turno', $_GET['delete']);
}	 
/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Inicializacion de variables y consultas
----------------------------------------------------------------------
--------------------------------------------------------------------*/	


	$total=0;
	$sabados=0;
	$semana=0;
	$semanal=0;
	$salida_sabado=0;

	
	$convenio=getConvenio($_GET['id']);
	$row_convenio = mysql_fetch_assoc($convenio);
	
	$convenios=getConvenios();
	$row_convenios =  mysql_fetch_assoc($convenios);
	
	$convenio_turno=getConvenioturnos($_GET['id'], 'id_convenio');
	$row_convenio_turno = mysql_fetch_assoc($convenio_turno);
	$cantidad_turno=mysql_num_rows($convenio_turno);



?>


<div align="left">
<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
<select 
		data-placeholder="Seleccione un usuario..." class="chosen-select" tabindex="2"
		onChange="javascript:window.location.href='convenios_turno.php?id='+this.value"
		name="id" <?php echo $cadena;?> required>
		<option value=""></option>
		<?php   do{ 
		if($_GET['id']==$row_convenios['id_convenio']){
		?>
		<option value="<?php   echo $row_convenios['id_convenio']?>" selected><?php   echo $row_convenios['convenio']?></option>
		<?php  }else{?>
		<option value="<?php   echo $row_convenios['id_convenio']?>"><?php echo $row_convenios['convenio']?></option>
		<?php  } 
		} while($row_convenios=mysql_fetch_array($convenios));?>
</select>
</div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
				Formulario nuevo convenio turno
----------------------------------------------------------------------
--------------------------------------------------------------------->	

<div class='slidingDiv'>
	<form name="franja" method="get">
		<table class="table">
			<tr>
				<td>Convenio</td>
				<td>
					<input name="id" type="hidden" value="<?php echo $row_convenio['id_convenio']?>" />
					<input name="convenio" type="text" value="<?php echo $row_convenio['convenio']?>" readonly/>
					
				</td>
			</tr>
			<tr>
				<td>Lunes</td>
				<td><input class="" type="checkbox" name="lunes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Martes</td>
				<td><input type="checkbox" name="martes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Miercoles</td>
				<td><input type="checkbox" name="miercoles" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Jueves</td>
				<td><input type="checkbox" name="jueves" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Viernes</td>
				<td><input type="checkbox" name="viernes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Sabado</td>
				<td><input type="checkbox" name="sabado" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Domingo</td>
				<td><input type="checkbox" name="domingo" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>				
				<td>Redondeo</td>
				<td><input type="checkbox" checked data-on="success" data-off="danger" name="redondeo" /></td>
			</tr>
			<tr>
				<td>Entrada</td>
				<td>
					<div class="input-prepend">
						<span class="timepicker_button_trigger add-on"><i class="icon-time"></i></span>
					  	<input type="text"  id="entrada" placeholder="ingrese entrada" onkeypress="return false" name="entrada" required/>
					</div>
					
			        <script type="text/javascript">
						$('#entrada').timepicker({

        				showOn: 'button',
        				button: '.timepicker_button_trigger'
					});
			        </script>
				</td>
			</tr>
			<tr>
				<td>Salida</td>
				<td>
					<div class="input-prepend">
						<span class="salida_button add-on"><i class="icon-time"></i></span>
					  	<input type="text"  id="salida" name="salida" placeholder="ingrese salida" onkeypress="return false" required />
					</div>
			        <script type="text/javascript">
						$('#salida').timepicker({
        				showOn: 'button',
        				button: '.salida_button'
					});
			        </script>
				</td>
			</tr>
			
			<tr>				
				<td>Límite</td>
				<td>
					<div class="input-prepend">
						<span class="salida_button add-on"><i class="icon-wrench"></i></span>
					  	<input type="number" name="limite" step="1" max="30" min="1" placeholder="ingrese límite">
					</div>
    			</td>
			</tr>
			<tr>				
				<td></td>
				<td>
					<button name="insert" value="1" type="submit" class="btn btn-primary"><i class="icon-plus-sign-alt"></i> Alta</button>
					<A class="show_hide btn btn-danger"  title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A>
				</td>
			</tr>
		</table>
	</form>
</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
				Tabla de convenio turno
----------------------------------------------------------------------
--------------------------------------------------------------------->

<div class="well">
	<?php if($cantidad_turno<1){ ?>
	<div class="alert">
  		<button type="button" class="close" data-dismiss="alert">&times;</button>
  		<strong>No hay turnos ingresados!</strong> Por favor agregue uno.
  		<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	</div>	
	<?php }else{?>
		<table class="table table-hover">
			<thead>
			<tr>
				<td class="table-center">Redondeo</td>
				<td>Entrada</td>
				<td>Salida</td>
				<td>Turno</td>
				<td>Límite</td>
				<td title="Lunes" class="table-center">L</td>
				<td title="Martes" class="table-center">M</td>
				<td title="Miércoles" class="table-center">M</td>
				<td title="Jueves" class="table-center">J</td>
				<td title="Viernes" class="table-center">V</td>
				<td title="Sábado" class="table-center">S</td>
				<td title="Domingo" class="table-center">D</td>
				<td>Intervalo</td>
				<td>Semanal</td>
				<td></td>
			</tr>
			</thead>
			<tbody>
			<?php do{ 
				$cantidad=0;
				?>
			<tr>
				<td class="table-center">
					<?php if($row_convenio_turno['redondeo']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
					};?>
				</td>
				<td><?php echo date("H:i", strtotime($row_convenio_turno['entrada'])) ;?></td>
				<td><?php echo date("H:i", strtotime($row_convenio_turno['salida'])) ;?></td>
				<td><?php echo $row_convenio_turno['turno']?></td>
				<td><?php echo $row_convenio_turno['limite'];?> min</td>
				<td class="table-center">
					<?php if($row_convenio_turno['lunes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['martes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['miercoles']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['jueves']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['viernes']==1){
						echo "<span class='label label-verde'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['sabado']==1){
						echo "<span class='label label-info'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td class="table-center">
					<?php if($row_convenio_turno['domingo']==1){
						echo "<span class='label label-important'><i class='icon-ok'></i></span>";
						$cantidad=$cantidad+1;
					};?>
				</td>
				<td>
					<?php 
						$e=date("H:i", strtotime($row_convenio_turno['entrada']));
						$s=date("H:i", strtotime($row_convenio_turno['salida']));
						$m=intervalo_tiempo($e,$s);
						echo pasar_hora($m);
						
						
						if($row_convenio_turno['sabado']==1){
							$sabados=$sabados+$m;
							if($s>$salida_sabado){
								$salida_sabado=$s;
							}
						}else{
							$semana=$semana+$m;
						}
						$semanal=$cantidad*$m;
						$total=$total+$semanal;
						
					?>
				</td>
				<td>
					<?php echo pasar_hora($semanal);?>
				</td>		
				<td>
					<a href="modificar_convenio_turno.php?id=<?php echo $row_convenio_turno['id_convenio_turno'];?>" class="btn btn-primary" title="Editar turno"><i class="icon-edit"></i></a>
					<a href="convenios_turno.php?delete=<?php echo $row_convenio_turno['id_convenio_turno'];?>&id=<?php echo $_GET['id']?>" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-danger"><i class="icon-minus-sign"></i></a>
				</td>

			</tr>
			<?php }while($row_convenio_turno=mysql_fetch_assoc($convenio_turno))?>
		
			<tr>
				<td colspan="13">Total de horas semanales</td>
				<th><?php echo pasar_hora($total);?></th>
				<td></td>
			</tr>
			<tr>
				<td colspan="13">Total de horas de lunes a viernes</td>
				<th><?php echo pasar_hora($semana);?></th>
				<td></td>
			</tr>
			<tr>
				<td colspan="13">Total de horas sabados</td>
				<th><?php echo pasar_hora($sabados);?></th>
				<td></td>
			</tr>
			</tbody>
		</table>
	<?php } ?>
</div>	

<?php 
$datos=array(
			'semana'=>$semana,
			'sabado'=>$sabados,
			'salida_sabado'=>$salida_sabado,
			'id_convenio'=>$_GET['id']
);
actualizarConvenio($datos);
include_once("footer.php");?>	
	
