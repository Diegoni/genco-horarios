<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");    
include_once($url['models_url']."convenios_model.php");   
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."mensajes_model.php");

	$convenio_turno=getConvenioturno($_GET['id']);
	$row_convenio_turno = mysql_fetch_assoc($convenio_turno);


?>

<div class="well">
	<?php do{ ?>
	<form name="franja" action="convenios_turno.php" method="get">
		<table class="table">
			<tr>
				<td>Convenio</td>
				<td>
					<?php
					$convenio=getConvenio($row_convenio_turno['id_convenio']);
					$row_convenio = mysql_fetch_assoc($convenio);
					?>
					<input name="id" type="hidden" value="<?php echo $row_convenio['id_convenio']?>" />
					<input name="convenio" type="text" value="<?php echo $row_convenio['convenio']?>" readonly/>
					
				</td>
			</tr>
			<tr>
				<td>Lunes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['lunes']==1){echo "checked";}?> name="lunes" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Martes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['martes']==1){echo "checked";}?> name="martes" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Miercoles</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['miercoles']==1){echo "checked";}?> name="miercoles" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Jueves</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['jueves']==1){echo "checked";}?> name="jueves" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Viernes</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['viernes']==1){echo "checked";}?> name="viernes" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Sabado</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['sabado']==1){echo "checked";}?> name="sabado" data-on="success" data-off="danger" /></td>
			</tr>
			<tr>
				<td>Domingo</td>
				<td><input type="checkbox" <?php if($row_convenio_turno['domingo']==1){echo "checked";}?> name="domingo" data-on="success" data-off="danger" /></td>
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
					  	<input type="text"  id="entrada" placeholder="ingrese entrada" onkeypress="return false" name="entrada" required value="<?php echo date("H:i", strtotime($row_convenio_turno['entrada']));?>" />
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
					  	<input type="text"  id="salida" name="salida" placeholder="ingrese salida" onkeypress="return false" required value="<?php echo date("H:i", strtotime($row_convenio_turno['salida']));?>" />
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
					  	<input type="number" name="limite" step="1" max="30" min="1" placeholder="ingrese límite" value="<?php echo $row_convenio_turno['limite'];?>" >
					</div>
    			</td>
			</tr>
			<tr>				
				<td></td>
				<td>
					<input name="id_convenio_turno" type="hidden" value="<?php echo $_GET['id']?>">
					<?php if(isset($action)){ ?>
						<button onclick="return confirm('Esta seguro que quiere eliminar el turno?');" name="delete" value="1" type="submit" class="btn btn-primary"><i class="icon-minus-sign"></i> Eliminar</button>
						<a href="#" onclick="history.go(-1); return false;" class="btn btn-danger"><i class="icon-ban-circle"></i> Cancelar</a>						
					<?php }else{ ?>				
						<button name="edit" value="1" type="submit" class="btn btn-primary"><i class="icon-edit"></i> Editar</button>
						<a href="#" onclick="history.go(-1); return false;" class="btn btn-danger"><i class="icon-ban-circle"></i> Cancelar</a>
					<?php } ?>
				</td>
			</tr>
		</table>
	</form>
	<?php }while($row_convenio_turno=mysql_fetch_assoc($convenio_turno)); ?>
</div>		
	
	<?php include_once("footer.php");?>
