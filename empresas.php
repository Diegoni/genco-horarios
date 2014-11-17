<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."mensajes_model.php");


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
	$datos=array(
			'empresa'		=> $_GET['empresa'],
			'cod_empresa'	=> $_GET['cod_empresa'],
			'cuil1'			=> $_GET['cuil1'],
			'cuil2'			=> $_GET['cuil2'],
			'cuil3'			=> $_GET['cuil3'],
			'id_estado'		=> 1,
			'id'			=> $_GET['id']);	
	
	updateEmpresa($datos);
	echo getMensajes('update', 'ok', 'Empresa', $_GET['empresa']);
}

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['nuevo'])){
	$empresa			= getEmpresas($_GET['empresa'], 'empresa');
	$row_empresa		= mysql_fetch_assoc($empresa);
	$numero_empresas	= mysql_num_rows($empresa);
	
	if($numero_empresas>0){
		echo getMensajes('insert', 'error', 'Empresa', $_GET['empresa']);
	}else{
		$datos=array(
			'empresa'		=> $_GET['empresa'],
			'cod_empresa'	=> $_GET['cod_empresa'],
			'cuil1'			=> $_GET['cuil1'],
			'cuil2'			=> $_GET['cuil2'],
			'cuil3'			=> $_GET['cuil3'],
			'id_estado'		=> 1);
		
		insertEmpresa($datos);
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
	$empresa = getEmpresas($_GET['empresa'], 'empresa'); 
}else{
	$empresa = getEmpresas();
}
	$row_empresa		= mysql_fetch_assoc($empresa);
	$numero_empresas	= mysql_num_rows($empresa);



?>

<div class="row">
<div class="col-md-12">
	<p class="block-title">Empresas</p>
	<div>
		<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
		<a href="javascript:imprSelec('muestra')" class='btn btn-default'><i class="icon-print"></i> Imprimir</a>
		<button class="btn btn-default" onclick="tableToExcel('example', 'Empresas')"><i class="icon-download-alt"></i> Excel</button>
	</div>
<div class="divider"></div>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo departamento
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
		
<form class="form-inline" action="empresas.php">
<table class="table table-hover">

	<tr>
		<td>Empresa</td>
		<td><input type="text" name="empresa" class="form-control" placeholder="ingrese Empresa" maxlength="32" required></td>
	</tr>

	<tr>
		<td>Cod</td>
		<td><input type="text" name="cod_empresa" class="form-control" placeholder="ingrese codigo de empresa" maxlength="32" required></td>
	</tr>

	<tr>
		<td>Cuil</td>
		<td>
			<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="form-control" required>-
			<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="form-control" required>-
			<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="form-control" required>
		</td>
	</tr>

	<tr>
		<td></td>
		<td>
		<button type="submit" class="btn btn-primary" name="nuevo" value="1" rel='tooltip' title="Alta empresa"><i class="icon-plus-sign-alt"></i> Alta</button>
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
		<td><A class="btn btn-primary" rel='tooltip' title="Editar empresa" HREF="modificar_empresa.php?id=<?php echo $row_empresa['id_empresa'];?>&action=1"><i class="icon-edit"></i></A>
			<?php if ($row_empresa['id_estado']==0) {?>
			<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="La empresa ya esta dada de baja"><i class="icon-minus-sign"></i></i></A>
			<?php } else { ?>
			<A type="submit" class="btn btn-danger"  rel='tooltip' title="Dar de baja" HREF="modificar_empresa.php?id=<?php echo $row_empresa['id_empresa'];?>&action=0"><i class="icon-minus-sign"></i></i></A>
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