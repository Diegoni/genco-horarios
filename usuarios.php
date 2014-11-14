<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."usuarios_sistema_model.php");
include_once($url['models_url']."tipos_model.php");     
include_once($url['models_url']."mensajes_model.php"); 


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM de departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['delete'])){
	deleteUsuario_sistema($_GET['id']);
}


//modifica el pass
if(isset($_GET['changePass'])){
	$datos=array(
				'id'			=> $_GET['id'],
				'pass'			=> $_GET['pass']);
	changePass($datos);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	$datos=array(
				'id'			=> $_GET['id'],
				'usuario'		=> $_GET['usuario'],
				'email'			=> $_GET['email'],
				'email_update'	=> $_GET['email_update'],
				'id_tipo'		=> $_GET['id_tipo']);
	
	updateUsuario_sistema($datos);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$datos=array(
				'usuario'		=> $_GET['usuario'],
				'email'			=> $_GET['email'],
				'email_update'	=> $_GET['email_update'],
				'id_tipo'		=> $_GET['id_tipo'],
				'pass'			=> $_GET['pass']);

	insertUsusario_sistema($datos);
	echo getMensajes('insert', 'ok', 'Usuario', $_GET['usuario']);

}


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer los usuarios
----------------------------------------------------------------------			
--------------------------------------------------------------------*/


//si no hay busqueda los trae a todos

	$usuarios				= getUsuarios_sistema();
	$row_usuario			= mysql_fetch_assoc($usuarios);
	$numero_usuarios		= mysql_num_rows($usuarios);
	
	$tipos				= getTipos_usuario();
	$row_tipo		 	= mysql_fetch_assoc($tipos);
	$numero_tipos		= mysql_num_rows($tipos);
		
?>
<!-- si hay modificacion o eliminacion de departamento se da aviso que se realizado exitosamente -->
<?php if(isset($_GET['modificar'])){
	echo getMensajes('update', 'ok', 'Usuario', $_GET['usuario']);
}else if(isset($_GET['delete'])){
	echo getMensajes('delete', 'ok', 'Usuario', $_GET['usuario']);
}?>

<div class="row">
<div class="col-md-12">
	<p class="block-title">Usuarios</p>
	<div>
		<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
		
<form class="form-inline" action="usuarios.php">
	<table class="table table-hover">
	
	<tr>
		<td>Usuario</td>
		<td><input type="text" name="usuario" class="form-control" placeholder="Ingrese usuario" maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td><input type="text" name="email" class="form-control" placeholder="Ingrese email" maxlength="64"></td>
	</tr>
	
	<tr>
		<td>Pass</td>
		<td><input type="password" name="pass" class="form-control" placeholder="Ingrese pass" maxlength="64" pattern=".{4,}" required></td>
	</tr>
	
	
	<tr>
		<td>Enviar email update</td>
		<td><input type="checkbox" name="email_update" data-on="success" data-off="danger" ></td>
	</tr>
	
	<tr>
		<td>Tipo</td>
		<td><select class='chosen-select form-control' tabindex="2" name="id_tipo" required>
			<option></option>
				<?php do{ ?>
					<option value="<?php echo $row_tipo['id_tipo_usuario']?>">
						<?php echo $row_tipo['tipo_usuario']?>
					</option>						
				<?php }while($row_tipo = mysql_fetch_array($tipos)); ?>
			</select>
		</td>
	</tr>
		
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" rel='tooltip' title="Alta departamento"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  rel='tooltip' title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr>  

	</table>
</form>
<div class="divider"></div>
</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Tabla de usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div id="muestra">
<table class="table table-hover" id="example">

<!-- Cabecera -->
<thead>
	<tr>
		<td>Usuario</td>
		<td>Email</td>
		<td>Tipo</td>
		<td>Operación</td>
	</tr>
</thead>

<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_usuario['usuario_nombre'];?></td>
		<td><?php echo $row_usuario['usuario_email'];?></td>
		<td><?php echo $row_usuario['tipo_usuario'];?></td>
		<td>
			<A class="btn btn-primary" rel='tooltip' title="Editar usuario" HREF="modificar_usuario.php?id=<?php echo $row_usuario['usuario_id'];?>&action=1"><i class="icon-edit"></i></A>
			<?php if ($row_usuario['id_estado']==0) {?>
				<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="El usuario ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
			<?php } else { ?>
				<A type="submit" class="btn btn-danger"  rel='tooltip' title="Dar de baja" HREF="modificar_usuario.php?id=<?php echo $row_usuario['usuario_id'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
			<?php } ?>
			<button type="button" class="btn btn-default" data-toggle="modal" data-target="#myModal<?php echo $row_usuario['usuario_id'];?>">
  				<i class="icon-lock"></i>
			</button>

			<!-- Modal -->
			<div class="modal fade" id="myModal<?php echo $row_usuario['usuario_id'];?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
				<div class="modal-dialog">
					<div class="modal-content">
						<div class="modal-header">
							<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
							<h4 class="modal-title" id="myModalLabel">Cambiar pass a <?php echo $row_usuario['usuario_nombre'];?></h4>
						</div>
						<div class="modal-body">
							<form class="form-horizontal" action="usuarios.php" role="form" onSubmit="return validarPasswd()">
								<div class="form-group">
									<label for="pass" class="col-sm-2 control-label">Pass</label>
									<div class="col-sm-10">
	      								<input type="password" class="form-control" name="pass" id="pass" pattern=".{4,}" placeholder="ingrese pass" maxlength="32">
	    							</div>
	  							</div>
	  							<div class="form-group">
									<label for="pass2" class="col-sm-2 control-label">Pass</label>
									<div class="col-sm-10">
	      								<input type="password" class="form-control" name="pass" id="pass2" pattern=".{4,}" placeholder="repita pass" maxlength="32">
	    							</div>
	  							</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<input type="hidden" name="id" value="<?php echo $row_usuario['usuario_id']; ?>">
										<button type="submit" class="btn btn-default" name="changePass" value="1">Cambiar pass</button>
									</div>
								</div>
  							</form>
						</div>
		      		</div>
				</div>
			</div>
		</td>
	</tr>
	<?php }while ($row_usuario = mysql_fetch_array($usuarios )) ?>
</tbody>


</table>
</div>
</center>
</div>


<?php include_once("footer.php");?>