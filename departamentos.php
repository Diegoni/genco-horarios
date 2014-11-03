<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."departamentos_model.php");   
include_once($url['models_url']."encargados_model.php");
include_once($url['models_url']."mensajes_model.php"); 


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM de departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['delete'])){
	deleteDepartamento($_GET['id']);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	$datos=array(
				'id'			=> $_GET['id'],
				'departamento'	=> $_GET['departamento'],
				'id_encargado'	=> $_GET['encargados']);
	
	updateDepartamento($datos);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	
	$departamento			= getDepartamentos($_GET['departamento'], 'departamento');
	$row_departamento 		= mysql_fetch_assoc($departamento);
	$numero_departamentos 	= mysql_num_rows($departamento);
	
	
	if($numero_departamentos>0){
		echo getMensajes('insert', 'error', 'Departamento', $_GET['departamento']);
	}else{
		insertDepartamento($_GET['departamento']);
		echo getMensajes('insert', 'ok', 'Departamento', $_GET['departamento']);
	}
}


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer los departamentos
----------------------------------------------------------------------			
--------------------------------------------------------------------*/


//si no hay busqueda los trae a todos
if(isset($_GET['buscar'])){
	$departamento			= getDepartamentos($_GET['departamento'], 'departamento');
}else{
	$departamento			= getDepartamentos();
}			
	$row_departamento		= mysql_fetch_assoc($departamento);
	$numero_departamentos	= mysql_num_rows($departamento);
	
	$encargado				= getEncargados();
	$row_encargado			= mysql_fetch_assoc($encargado);
	$numero_encargado		= mysql_num_rows($encargado);
	
?>
<div class="row">
<div class="span12">
<center>

<!-- si hay modificacion o eliminacion de departamento se da aviso que se realizado exitosamente -->
<?php if(isset($_GET['modificar'])){
	echo getMensajes('update', 'ok', 'Departamento', $_GET['departamento']);
}else if(isset($_GET['delete'])){
	echo getMensajes('delete', 'ok', 'Departamento', $_GET['departamento']);
}?>

<div ALIGN=left class="well">
	<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
	<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	<div class="pull-right"><h4>Departamentos</h4></div>
</div>
<br>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="well">
		
<form class="form-inline" action="departamentos.php">
	<table class="table table-hover">
	
	<tr>
		<td>Departamento</td>
		<td><input type="text" name="departamento" class="form-control" placeholder="ingrese departamento" required></td>
	</tr>
	
	<?php if($numero_encargado>0){ ?>
	<tr>
		<td>Encargados</td>
		<td><select class="chosen-select form-control" tabindex="2" name="encargados" multiple>
				<?php do{ ?> 
				<option value="<?php echo $row_encargado['id_encargado']?>"><?php echo $row_encargado['apellido']?> <?php echo $row_encargado['nombre']?></option>
				<?php }while($row_encargado = mysql_fetch_array($encargado)); ?>
			</select>
		</td>
	</tr>
	<?php }?>
	
	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" title="Alta departamento"><i class="icon-plus-sign-alt"></i> Alta</button>
		<A class="show_hide btn btn-danger"  title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
	</tr>  

	</table>
</form><br>
</div>
</div>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Tabla de usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div id="muestra">
<table border="1" class="table table-hover" id="example">

<!-- Cabecera -->
<thead>
	<tr class="success">
		<td>Departamento</td>
		<td>Estado</td>
		<td>Operación</td>
	</tr>
</thead>

<tbody>
	<?php do{ ?>
	<tr>
		<td><?php echo $row_departamento['departamento'];?></td>
		<td>
			<?php if ($row_departamento['id_estado']==0) {?>
				baja
			<?php } else { ?>
				activo
			<?php } ?>
		</td>
		<td><A class="btn btn-primary" title="Editar departamento" HREF="modificar_departamento.php?id=<?php echo $row_departamento['id_departamento'];?>&action=1"><i class="icon-edit"></i></A>
			<?php if ($row_departamento['id_estado']==0) {?>
			<A type="submit" class="btn btn-danger disabled"  title="El departamento partamento ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
			<?php } else { ?>
			<A type="submit" class="btn btn-danger"  title="Dar de baja" HREF="modificar_departamento.php?id=<?php echo $row_departamento['id_departamento'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
			<?php } ?>
		</td>
	</tr>
	<?php }while ($row_departamento = mysql_fetch_array($departamento )) ?>
</tbody>


</table>
</div>
</center>
</div>


<?php include_once("footer.php");?>