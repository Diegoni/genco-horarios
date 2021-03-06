<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."encargados_model.php");
include_once($url['models_url']."mensajes_model.php");


if(isset($_GET['nuevo'])){
	$datos=array(
				'email'			=> $_GET['email'],
				'id_encargado'	=> $_GET['id']);
	
	insertEncargado_email($datos);
	
	echo getMensajes('insert', 'ok', 'Email', $_GET['email']);
}

if(isset($_GET['delete'])){
	deleteEncargado_email($_GET['registro']);
}

//seleccion del usuario
$encargado			= getEncargado($_GET['id']);
$row_encargado		= mysql_fetch_assoc($encargado);

$action				= $_GET['action'];
$input_action		= "";

if($action==0){
	$input_action="readonly";
}


?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="encargados.php">
<input type="hidden" name="id" class="span4" value="<?php echo $row_encargado['id_encargado'];?>">

<table class="table table-hover">

	<tr>
		<td>Apellido</td>
		<td><input type="text" name="apellido" class="form-control" value="<?php echo $row_encargado['apellido'];?>" <?php echo $input_action; ?> maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Nombre</td>
		<td><input type="text" name="nombre" class="form-control" value="<?php echo $row_encargado['nombre'];?>" <?php echo $input_action; ?> maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td>
			<input type="text" name="email_1" class="form-control" value="<?php echo $row_encargado['email_1'];?>" <?php echo $input_action; ?> maxlength="64" required>
		</td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td>
			<input type="text" name="email_2" class="form-control" value="<?php echo $row_encargado['email_2'];?>" <?php echo $input_action; ?> maxlength="64">
		</td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td>
			<input type="text" name="email_3" class="form-control" value="<?php echo $row_encargado['email_3'];?>" <?php echo $input_action; ?> maxlength="64">
		</td>
	</tr>
	
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
		<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="delete" value="1" rel='tooltip' title="Dar de baja al encargado <?php echo $row_encargado['nombre'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
		<A class="btn btn-danger" HREF="encargados.php" rel='tooltip' title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A></td>
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
			<p>El encargado eliminado no se mostrara más en las planillas de horarios.<p> 
			<p>El encargado no se borra de la base de datos solo se cambia su estado, se puede recuperar el encargado si se elimina.</p>
		</div>
		
		<div class="modal-footer">
			<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
		</div>
	</div>
	</div>
</div>

<?php }else{?>
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="modificar" value="1" rel='tooltip' title="Editar encargado <?php echo $row_encargado['nombre'];?>"><i class="icon-edit"></i> Editar</button>
		<!--<a href="#" class="show_hide btn btn-default" rel='tooltip' title="Añadir registro"><i class="icon-plus-sign-alt"></i> Nuevo correo</a>-->
		<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="encargados.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr> 

</table>
</form> 
<?php }?>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="well">
		
<form class="form-inline" action="modificar_encargado.php">
	<input type="hidden" name="id" value="<?php echo $row_encargado['id_encargado'];?>">
	<input type="hidden" name="action" value="1">
	<table class="table table-hover">
	
	<tr>
		<td>Email</td>
		<td><input type="email" name="email" class="form-control" placeholder="ingrese email" required></td>
	</tr>
	
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" rel='tooltip' title="Alta email"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger" rel='tooltip' title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr>  

	</table>
</form><br>
</div>
</div>




</center>
</div>


<?php include_once("footer.php");?>