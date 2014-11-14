<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."encargados_model.php");
include_once($url['models_url']."emails_encargados_model.php");   
include_once($url['models_url']."mensajes_model.php"); 


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM de encargado
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
//modifica al encargado segun el formulario de modificar.php
if(isset($_GET['delete'])){
	deleteEncargado($_GET['id']);
}

//modifica al encargado segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	$datos=array(
				'nombre'		=> $_GET['nombre'],
				'apellido'		=> $_GET['apellido'],
				'email_1'		=> $_GET['email_1'],
				'email_2'		=> $_GET['email_2'],
				'email_3'		=> $_GET['email_3'],
				'id_encargado'	=> $_GET['id']);
				
	updateEncargado($datos);
}

//modifica al encargado segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$datos=array(
				'nombre'		=> $_GET['nombre'],
				'apellido'		=> $_GET['apellido']);
	
	$id_encargado=insertEncargado($datos);
	
	$datos=array(
				'email'			=> $_GET['email'],
				'id_encargado'	=> $id_encargado);
	
	insertEncargado_email($datos);
	
	echo getMensajes('insert', 'ok', 'Encagado', $_GET['nombre']);
}


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer los encagados
----------------------------------------------------------------------			
--------------------------------------------------------------------*/


//si no hay busqueda los trae a todos
if(isset($_GET['buscar'])){
	$encargado=getEncargados($_GET['encagado'], 'encargado');
}else{
	$encargado=getEncargados();
}			
	$row_encargado = mysql_fetch_assoc($encargado);
	$numero_encargados = mysql_num_rows($encargado);



?>
<!-- si hay modificacion o eliminacion de departamento se da aviso que se realizado exitosamente -->
<?php if(isset($_GET['modificar'])){
	echo getMensajes('update', 'ok', 'Encargado', $_GET['nombre']);
}else if(isset($_GET['delete'])){
	echo getMensajes('delete', 'ok', 'Encargado', $_GET['nombre']);
}?>

<div class="row">
<div class="col-md-12">
	<p class="block-title">Encargados</p>
	<div>
		<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo encargado
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
	
<form class="form-inline" action="encargados.php">
	<table class="table table-hover">
	
	<tr>
		<td>Apellido</td>
		<td><input type="text" name="apellido" class="form-control" placeholder="ingrese apellido" maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Nombre</td>
		<td><input type="text" name="nombre" class="form-control" placeholder="ingrese nombre" maxlength="32" required></td>
	</tr>
	
	<tr>
		<td>Email</td>
		<td><input type="email" name="email" class="form-control" placeholder="ingrese email" maxlength="64" required></td>
	</tr>
	
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" rel='tooltip' title="Alta encargado"><i class="icon-plus-sign-alt"></i> Alta</button>
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
	<td>Apellido</td>
	<td>Nombre</td>
	<td>Estado</td>
	<td>Operación</td>
</tr>
</thead>

<tbody>
<?php do{ ?>
<tr>
	<td><?php echo $row_encargado['apellido'];?></td>
	<td><?php echo $row_encargado['nombre'];?></td>
	<td>
			<?php if ($row_encargado['delete']==1) {?>
			baja
		<?php } else { ?>
			activo
		<?php } ?>
	</td>
	<td>
		<A class="btn btn-primary" rel='tooltip' title="Editar departamento" HREF="modificar_encargado.php?id=<?php echo $row_encargado['id_encargado'];?>&action=1"><i class="icon-edit"></i></A>
		<?php if ($row_encargado['delete']==1) {?>
		<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="El encargado partamento ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
		<?php } else { ?>
		<A type="submit" class="btn btn-danger"  rel='tooltip' title="Dar de baja" HREF="modificar_encargado.php?id=<?php echo $row_encargado['id_encargado'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
		<?php } ?>
	</td>
</tr>
<?php }while ($row_encargado = mysql_fetch_array($encargado )) ?>
</tbody>


</table>
</div>
</center>
</div>


<?php include_once("footer.php");?>
