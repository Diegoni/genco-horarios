<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."usuarios_sistema_model.php");  
include_once($url['models_url']."tipos_model.php");

//seleccion del usuario
$usuarios			= getUsuario_sistema($_GET['id']);
$row_usuario 		= mysql_fetch_assoc($usuarios);
$numero_usuario		= mysql_num_rows($usuarios);

$tipos				= getTipos_usuario();
$row_tipo			= mysql_fetch_assoc($tipos);
$numero_tipo		= mysql_num_rows($tipos);

$action				= $_GET['action'];
$input_action		= "";

if($action==0){
	$input_action="disabled";
}


?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="usuarios.php">
<table class="table table-hover">
	<input type="hidden" name="id" class="span4" value="<?php echo $row_usuario['usuario_id'];?>">
	
	<tr>
		<td>Usuario</td>
		<td><input type="text" name="usuario" class="form-control" value="<?php echo $row_usuario['usuario_nombre'];?>" <?php echo $input_action; ?> maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td><input type="text" name="email" class="form-control" value="<?php echo $row_usuario['usuario_email'];?>" <?php echo $input_action; ?> maxlength="32" required></td>
	</tr>
	
	
	<tr>
		<td>Enviar email update</td>
		<td><input type="checkbox" name="email_update" <?php if($row_usuario['email_update']==1){ echo "checked";}?> data-on="success" data-off="danger" ></td>
	</tr>

	<tr>
		<td>Tipo</td>
		<td><select class='chosen-select form-control' tabindex="2" name="id_tipo" required>
			<option></option>
				<?php do{ ?>
					<?php if($row_usuario['id_tipousuario']==$row_tipo['id_tipo_usuario']){ ?>
						<option value="<?php echo $row_tipo['id_tipo_usuario']?>" selected>
							<?php echo $row_tipo['tipo_usuario']?>
						</option>
					<?php }else{ ?>
						<option value="<?php echo $row_tipo['id_tipo_usuario']?>">
							<?php echo $row_tipo['tipo_usuario']?>
						</option>
					<?php } ?>
				<?php }while($row_tipo = mysql_fetch_array($tipos)); ?>
			</select>
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
			<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="delete" value="1" rel='tooltip' title="Dar de baja al usuario <?php echo $row_usuario['usuario_nombre'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
			<A class="btn btn-danger"  HREF="usuarios.php" rel='tooltip' title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A>
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
			<p>El usuario eliminado tendra más acceso al sistema.<p> 
			<p>El usuario no se borra de la base de datos solo se cambia su estado, se puede recuperar el usuario si se elimina.</p>
		</div>
	</div>	
	</div>
</div>

<?php }else{?>
<tr>
<td></td>
<td>
	<button type="submit" class="btn btn-primary" name="modificar" value="1" rel='tooltip' title="Editar usuario <?php echo $row_usuarioi['usuario_nombre'];?>"><i class="icon-edit"></i> Editar</button>
	<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="usuarios.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 
<?php }?>





</center>
</div>


<?php include_once("footer.php");?>