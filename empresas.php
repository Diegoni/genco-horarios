<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."empresas_model.php");
include_once($models_url."mensajes_model.php");


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM empresas
----------------------------------------------------------------------			
--------------------------------------------------------------------*/
if(isset($_GET['delete'])){
	deleteEmpresa($_GET['id']);
	echo getMensajes('delete', 'ok', 'Empresa', $_GET['id']);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	updateEmpresa($_GET['empresa'],
								$_GET['cod_empresa'],
								$_GET['cuil1'],
								$_GET['cuil2'],
								$_GET['cuil3'],
								1,
								$_GET['id']);
	echo getMensajes('update', 'ok', 'Empresa', $_GET['empresa']);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$empresa=getEmpresas($_GET['empresa'], 'empresa');
	$row_empresa = mysql_fetch_assoc($empresa);
	$numero_empresas = mysql_num_rows($empresa);
	
	if($numero_empresas>0){
		echo getMensajes('insert', 'error', 'Empresa', $_GET['empresa']);
	}else{
		insertEmpresa($_GET['empresa'],
									$_GET['cod_empresa'],
									$_GET['cuil1'],
									$_GET['cuil2'],
									$_GET['cuil3'],
									1);
		echo getMensajes('insert', 'ok', 'Empresa', $_GET['empresa']);
	}
}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer las empresas
----------------------------------------------------------------------			
--------------------------------------------------------------------*/

//si no hay busqueda los trae a todos
if(isset($_GET['buscar'])){
	$empresa=getEmpresas($_GET['empresa'], 'empresa'); 
}else{
	$empresa=getEmpresas();
}
	$row_empresa = mysql_fetch_assoc($empresa);
	$numero_empresas = mysql_num_rows($empresa);



?>
<div class="row">
<div class="span12">
<center>

<!-- si hay modificacion o eliminacion de usuario se da aviso que se realizado exitosamente -->
<?php if(isset($_GET['modificar'])){
	echo getMensajes('update', 'ok', 'Empresa', $_GET['empresa']);
}else if(isset($_GET['eliminar'])){
	echo getMensajes('delete', 'ok', 'Empresa', $_GET['empresa']);
}?>

<div ALIGN=left class="well">
<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
<a href="javascript:imprSelec('muestra')" class='btn'><i class="icon-print"></i> Imprimir</a>
<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
<div class="pull-right"><h4>Empresas</h4></div>
</div>
<br>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="span9">
		
<form class="form-inline" action="empresas.php">
<table class="table table-hover">

	<tr>
		<td>Empresa</td>
		<td><input type="text" name="empresa" class="span4" placeholder="ingrese Empresa" required></td>
	</tr>

	<tr>
		<td>Cod</td>
		<td><input type="text" name="cod_empresa" class="span4" placeholder="ingrese codigo de empresa" required></td>
	</tr>

	<tr>
		<td>Cuil</td>
		<td>
			<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="span1" required>-
			<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="span2" required>-
			<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="span1" required>
		</td>
	</tr>

	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" title="Alta empresa"><i class="icon-plus-sign-alt"></i> Alta</button>
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
	<td>Empresa</td>
	<td>Codigo</td>
	<td>CUIL</td>
	<td>Estado</td>
	<td>Operación</td>
	</tr>
<thead>
<tbody>
<?php do{ ?>
<tr>
<td><?php echo $row_empresa['empresa'];?></td>
<td><?php echo $row_empresa['cod_empresa'];?></td>
<td><?php echo $row_empresa['cuil'];?></td>
<td>
		<?php if ($row_empresa['id_estado']==0) {?>
		baja
	<?php } else { ?>
		activa
	<?php } ?>
</td>
<td><A class="btn btn-primary" title="Editar empresa" HREF="modificar_empresa.php?id=<?php echo $row_empresa['id_empresa'];?>&action=1"><i class="icon-edit"></i></A>
	<?php if ($row_empresa['id_estado']==0) {?>
	<A type="submit" class="btn btn-danger disabled"  title="La empresa ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
	<?php } else { ?>
	<A type="submit" class="btn btn-danger"  title="Dar de baja" HREF="modificar_empresa.php?id=<?php echo $row_empresa['id_empresa'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
	<?php } ?>
	</td>
</tr>
<?php }while ($row_empresa = mysql_fetch_array($empresa)) ?>
</tbody>

</table>
</div>
</center>
</div>

<?php include_once("footer.php");?>