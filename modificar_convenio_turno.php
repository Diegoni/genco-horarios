<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($models_url."convenios_model.php");   
include_once($models_url."convenio_turnos_model.php");
include_once($models_url."mensajes_model.php");


	$convenio_turno=getConvenioturno($_GET['id']);
	$row_convenio_turno = mysql_fetch_assoc($convenio_turno);
	
	
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


?>

<div class="well">
	<?php do{ ?>
	<form name="franja" method="get">
		<table class="table">
			<tr>
				<td>Convenio</td>
				<td>
					<?php
					$convenio=getConvenio($row_convenio_turno['id_convenio']);
					$row_convenio = mysql_fetch_assoc($convenio);
					?>
					<input name="id" type="hidden" value="<?php echo $row_convenio['idconvenio']?>" />
					<input name="convenio" type="text" value="<?php echo $row_convenio['convenio']?>" readonly/>
					
				</td>
			</tr>
			<tr>
				<td>Lunes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['lunes']==1){echo "checked";}?> name="lunes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Martes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['martes']==1){echo "checked";}?> name="martes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Miercoles</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['miercoles']==1){echo "checked";}?> name="miercoles" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Jueves</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['jueves']==1){echo "checked";}?> name="jueves" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Viernes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['viernes']==1){echo "checked";}?> name="viernes" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Sabado</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['sabado']==1){echo "checked";}?> name="sabado" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>
				<td>Domingo</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['domingo']==1){echo "checked";}?> name="domingo" data-on="success" data-off="danger"/></td>
			</tr>
			<tr>				
				<td>Redondeo</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['redondeo']==1){echo "checked";}?> checked data-on="success" data-off="danger" name="redondeo" /></td>
			</tr>
			<tr>
				<td>Entrada</td>
				<td>
					<div class="input-prepend">
						<span class="timepicker_button_trigger add-on"><i class="icon-time"></i></span>
					  	<input type="text"  id="entrada" placeholder="ingrese entrada" onkeypress="return false" name="entrada" required value="<?php echo date("H:i", strtotime($row_convenio_turno['entrada']));?>"/>
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
					  	<input type="text"  id="salida" name="salida" placeholder="ingrese salida" onkeypress="return false" required value="<?php echo date("H:i", strtotime($row_convenio_turno['salida']));?>"/>
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
					  	<input type="number" name="limite" step="1" max="30" min="1" placeholder="ingrese límite" value="<?php echo $row_convenio_turno['limite'];?>">
					</div>
    			</td>
			</tr>
			<tr>				
				<td></td>
				<td>
					<a href="convenios.php" class="btn btn-danger">Volver</a>
					<button name="insert" value="1" type="submit" class="btn btn-default">Aceptar</button></td>
			</tr>
		</table>
	</form>
	<?php }while($row_convenio_turno=mysql_fetch_assoc($convenio_turno)); ?>
</div>		
	
