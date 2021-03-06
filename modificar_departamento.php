<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."departamentos_model.php");  
include_once($url['models_url']."encargados_model.php");

//seleccion del departamento
$departamento		= getDepartamento($_GET['id']);
$row_departamento 	= mysql_fetch_assoc($departamento);

$encargado			= getEncargados();
$row_encargado		= mysql_fetch_assoc($encargado);
$numero_encargado	= mysql_num_rows($encargado);

$action				= $_GET['action'];
$input_action		= "";

if($action==0){
	$input_action="disabled";
}


?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="departamentos.php">
<table class="table table-hover">
	<input type="hidden" name="id" class="span4" value="<?php echo $row_departamento['id_departamento'];?>">
	
	<tr>
		<td>Departamento</td>
		<td><input type="text" name="departamento" class="form-control" value="<?php echo $row_departamento['departamento'];?>" <?php echo $input_action; ?> maxlength="32" required></td>
	</tr>

	<?php if($numero_encargado>0){ ?>
	<tr>
		<td>Encargado</td>
		<td><select class='chosen-select form-control' tabindex="2" <?php echo $input_action; ?>  name="encargados">
				<?php do{ ?>
					<option></option>
					<?php if($row_departamento['id_encargado']==$row_encargado['id_encargado']){ ?>  
						<option value="<?php echo $row_encargado['id_encargado']?>" selected>
							<?php echo $row_encargado['apellido']?> <?php echo $row_encargado['nombre']?>
						</option>
					<?php }else{ ?>
						<option value="<?php echo $row_encargado['id_encargado']?>">
							<?php echo $row_encargado['apellido']?> <?php echo $row_encargado['nombre']?>
						</option>						
					<?php } ?>
				<?php }while($row_encargado = mysql_fetch_array($encargado)); ?>
			</select>
		</td>
	</tr>
	<?php }?>

<?php if($action==0){?>
	<tr>
		<td>Estado</td>
		<td>
		<input type="radio" name="estado" id="baja" value="0" checked data-on="success" data-off="danger" >
		 Baja
		</td>
	</tr>
	
	
	<tr>
		<td></td>
		<td>
			<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="delete" value="1" rel='tooltip' title="Dar de baja al departamento <?php echo $row_departamento['departamento'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
			<A class="btn btn-danger"  HREF="departamentos.php" rel='tooltip' title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A>
		</td>
	</tr>  

</table>
</form>

<a href="#myModal" role="button" class="btn btn-default" id="opener" data-toggle="modal"><i class="icon-question-sign"></i></a>
 
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
    <div class="modal-content">
		<div class="modal-header">
			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
			<h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3>
		</div>
		<div class="modal-body">
			<p>El departamento eliminado no se mostrara más en las planillas de horarios.<p> 
			<p>El departamento no se borra de la base de datos solo se cambia su estado, se puede recuperar el departamento si se elimina.</p>
		</div>
	</div>	
	</div>
</div>

<?php }else{?>
<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" rel='tooltip' title="Editar departamento <?php echo $row_departamento['departamento'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="departamentos.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 
<?php }?>





</center>
</div>


<?php include_once("footer.php");?>