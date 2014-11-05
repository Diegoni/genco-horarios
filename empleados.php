<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."mensajes_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."convenios_model.php");

/*--------------------------------------------------------------------
----------------------------------------------------------------------
			ABM usuarios
----------------------------------------------------------------------			
--------------------------------------------------------------------*/

//modifica al usuario segun el formulario de modificar.php
if(isset($_GET['modificar'])){
	$datos=array('usuario'=>$_GET['usuario'],
								'nombre'=>$_GET['nombre'],
								'apellido'=>$_GET['apellido'],
								'dni'=>$_GET['dni'],
								'cuil1'=>$_GET['cuil1'],
								'cuil2'=>$_GET['cuil2'],
								'cuil3'=>$_GET['cuil3'],
								'estado'=>1,
								'empresa'=>$_GET['empresa'],
								'departamento'=>$_GET['departamento'],
								'convenio'=>$_GET['convenio'],
								'legajo'=>$_GET['legajo'],
								'fecha_ingreso'=>$_GET['fecha_ingreso'],
								'id'=>$_GET['id']);

	updateUsuario($datos);
	echo getMensajes('update', 'ok', 'Usuario', $_GET['usuario']);
}

//da de baja al usuario segun el formulario de eliminar.php
if(!(empty($_GET['eliminar']))){
	deleteUsuario($_GET['id']);
	echo getMensajes('delete', 'ok', 'Usuario', $_GET['id']);
}


if(isset($_GET['nuevo'])){

// Comprobamos si el usuario esta registrado 

	$usuario=getUsuarios($_GET['usuario'], 'usuario');
	$row_usuario = mysql_fetch_assoc($usuario);
	$numero_usuarios = mysql_num_rows($usuario);
	
	if($numero_usuarios>0){
		$usuario=getUsuarios($_GET['legajo'], 'legajo');
		$row_usuario = mysql_fetch_assoc($usuario);
		$numero_usuarios = mysql_num_rows($usuario);
	}

	if($numero_usuarios>0){ 
		echo getMensajes('insert', 'error', 'Usuario', $_GET['usuario']);	
	}else{ 
		$datos=array('usuario'=>$_GET['usuario'],
								'nombre'=>$_GET['nombre'],
								'apellido'=>$_GET['apellido'],
								'dni'=>$_GET['dni'],
								'cuil1'=>$_GET['cuil1'],
								'cuil2'=>$_GET['cuil2'],
								'cuil3'=>$_GET['cuil3'],
								'estado'=>1,
								'empresa'=>$_GET['empresa'],
								'departamento'=>$_GET['departamento'],
								'convenio'=>$_GET['convenio'],
								'legajo'=>$_GET['legajo'],
								'fecha_ingreso'=>$_GET['fecha_ingreso']);
	
		insertUsusario($datos);
		echo getMensajes('insert', 'ok', 'Usuario', $_GET['usuario']);	
	}
}


/*--------------------------------------------------------------------
----------------------------------------------------------------------
			Consulta para traer los usuarios
----------------------------------------------------------------------			
--------------------------------------------------------------------*/


//si no hay busqueda los trae a todos

	$usuario=getUsuarios('all');
	$row_usuario = mysql_fetch_assoc($usuario);
	$numero_filas = mysql_num_rows($usuario);



//seleccion de departamento para formulario de busqueda
$departamento=getDepartamentos();
$row_departamento = mysql_fetch_assoc($departamento);
mysql_query("SET NAMES 'utf8'");


$departamento2=getDepartamentos();
$row_departamento2 = mysql_fetch_assoc($departamento2);
$numero_departamentos = mysql_num_rows($departamento2);


//para empresas
$empresa=getEmpresas();
$row_empresa = mysql_fetch_assoc($empresa);
$numero_empresas = mysql_num_rows($empresa);


$convenio=getConvenios();
$row_convenio = mysql_fetch_assoc($convenio);
$numero_convenio = mysql_num_rows($convenio);




?>
<div class="row">
<div class="span12">
<center>


<div ALIGN=left class="well">
	<a href='#' class='show_hide btn btn-primary' rel='tooltip' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
	<a class='btn btn-default' href="javascript:imprSelec('muestra')" ><i class="icon-print"></i> Imprimir</a>
	<button class="btn btn-default" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
	<div class="pull-right"><h4>Empleados</h4></div>
</div>
<br>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="well">
		
<!-- Formulario de alta usuario -->
<form class="form-inline" action="empleados.php" >
<table class="table table-hover">
<tr>
<td>Usuario</td>
<td><input type="text" name="usuario" class="form-control" placeholder="ingrese Usuario" required></td>
</tr>

<tr>
<td>Nombre</td>
<td><input type="text" name="nombre" class="form-control" placeholder="ingrese Nombre" required></td>
</tr>

<tr>
<td>Apellido</td>
<td><input type="text" name="apellido" class="form-control" placeholder="ingrese Apellido" required></td>
</tr>

<tr>
<td>DNI</td>
<td><input type="text" name="dni" class="form-control" onkeypress="return isNumberKey(event)" maxlength="8" placeholder="ingrese DNI" required></td>
</tr>

<tr>
<td>Fecha ingreso</td>
<td><input type="text" name="fecha_ingreso" id="datepicker" class="form-control" placeholder="ingrese fecha" autocomplete="off" required></td>
</tr>

<tr>
<td>CUIL</td>
<td>
	<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="form-control" required>-
	<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="form-control" required>-
	<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="form-control" required>
</td>
</tr>

<tr>
<td>Legajo</td>
<td><input type="text" name="legajo" class="form-control" onkeypress="return isNumberKey(event)" placeholder="Legajo" required></td>
</tr>

<tr>
<td>Empresa</td>
<td><select class="form-control" name="empresa" required>
		<option></option>
	<?php do{ ?>	
		<option value="<?php echo $row_empresa['id_empresa'];?>"><?php echo $row_empresa['empresa'];?></option>
	<?php }while ($row_empresa = mysql_fetch_array($empresa)) ?>
	</select>
</td>
</tr>  

<tr>
<td>Departamento</td>
<td><select class="form-control" name="departamento" required>
		<option></option>
	<?php do{ ?>	
		<option value="<?php echo $row_departamento2['id_departamento'];?>"><?php echo $row_departamento2['nombre'];?></option>
	<?php }while ($row_departamento2 = mysql_fetch_array($departamento2)) ?>
	</select>
</td>
</tr>  


<tr>
<td>Convenio</td>
<td><select class="form-control" name="convenio" required>
		<option></option>
	<?php do{ ?>	
		<option value="<?php echo $row_convenio['id_convenio'];?>"><?php echo $row_convenio['convenio'];?></option>
	<?php }while ($row_convenio = mysql_fetch_array($convenio)) ?>
	</select>
</td>
</tr>  

<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="nuevo" value="1"><i class="icon-plus-sign-alt"></i> Alta</button>
<A class="show_hide btn btn-danger"  rel='tooltip' title="Cancelar" href='#'><i class="icon-ban-circle"></i> Cancelar</A></td>
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
	<td>Usuario</td>
	<td>Legajo</td>
	<td>Depar.</td>
	<td>Estado</td>
	<td>Operación</td>
	</tr>
</thead>

<tbody>
<?php do{ ?>
<tr>
<td><?php echo $row_usuario['usuario'];?></td>
<td><?php echo $row_usuario['legajo'];?></td>
<td><?php echo $row_usuario['departamento'];?></td>
<td>
	<?php if ($row_usuario['id_estado']==0) {?>
		baja
	<?php } else { ?>
		activo
	<?php } ?>
</td>
<td><A class="btn btn-primary" rel='tooltip' title="Editar usuario" HREF="modificar.php?id=<?php echo $row_usuario['id_usuario'];?>"><i class="icon-edit"></i></A>
	<?php if ($row_usuario['id_estado']==0) {?>
	<A type="submit" class="btn btn-danger disabled"  rel='tooltip' title="El usuario ya esta dado de baja"><i class="icon-minus-sign"></i></i></A>
	<?php } else { ?>
	<A type="submit" class="btn btn-danger"  rel='tooltip' title="Dar de baja" HREF="eliminar.php?id=<?php echo $row_usuario['id_usuario'];?>"><i class="icon-minus-sign"></i></i></A>
	<?php } ?>
	</td>
</tr>
<?php }while ($row_usuario = mysql_fetch_array($usuario)) ?>
</tbody>

</table>
</div>



</center>
</div>

<?php include_once("footer.php");?>
