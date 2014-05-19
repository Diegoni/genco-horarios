<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."usuarios_model.php");
include_once($models_url."mensajes_model.php");
include_once($models_url."departamentos_model.php");
include_once($models_url."empresas_model.php");
include_once($models_url."convenios_model.php");

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
								'estado'=>$_GET['estado'],
								'empresa'=>$_GET['empresa'],
								'departamento'=>$_GET['departamento'],
								'convenio'=>$_GET['convenio'],
								'legajo'=>$_GET['legajo'],
								'fecha_ingreso'=>$_GET['fecha_ingreso'],
								'id'=>$_GET['id']);

	updateUsuario($datos);
}

//da de baja al usuario segun el formulario de eliminar.php
if(!(empty($_GET['eliminar']))){
	deleteUsuario($_GET['id']);
}


if(isset($_GET['nuevo'])){

// Comprobamos si el usuario esta registrado 

	$usuario=getUsuarios($_GET['usuario'], 'usuario');
	$row_usuario = mysql_fetch_assoc($usuario);
	$numero_usuarios = mysql_num_rows($usuario);
	if($nuevo_usuario>0){
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
								'estado'=>$_GET['estado'],
								'empresa'=>$_GET['empresa'],
								'departamento'=>$_GET['departamento'],
								'convenio'=>$_GET['convenio'],
								'legajo'=>$_GET['legajo'],
								'fecha_ingreso'=>$_GET['fecha_ingreso'],
								'id'=>$_GET['id']);
	
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
<div class="span9">
<center>

<!-- si hay modificacion o eliminacion de usuario se da aviso que se realizado exitosamente -->
<? 
if($_GET['modificar']==1){
	echo getMensajes('updete', 'ok', 'Usuario', $_GET['usuario']);
}else if($_GET['eliminar']==1){
	echo getMensajes('delete', 'ok', 'Usuario', $_GET['usuario']);
} 
?>

<div ALIGN=left>
<a href='#' class='show_hide btn btn-primary' title='Añadir registro'><i class="icon-plus-sign-alt"></i> Nuevo</a>
<a href="javascript:imprSelec('muestra')" class='btn'><i class="icon-print"></i> Imprimir</a>
<button class="btn" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>
</div>
<br>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario nuevo usuario
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<div class='slidingDiv'>
<div class="span9">
		
<!-- Formulario de alta usuario -->
<form class="form-inline" action="usuarios.php" >
<table class="table table-hover">
<tr>
<td>Usuario</td>
<td><input type="text" name="usuario" class="span4" placeholder="ingrese Usuario" required></td>
</tr>

<tr>
<td>Nombre</td>
<td><input type="text" name="nombre" class="span4" placeholder="ingrese Nombre" required></td>
</tr>

<tr>
<td>Apellido</td>
<td><input type="text" name="apellido" class="span4" placeholder="ingrese Apellido" required></td>
</tr>

<tr>
<td>DNI</td>
<td><input type="text" name="dni" class="span4" onkeypress="return isNumberKey(event)" maxlength="8" placeholder="ingrese DNI" required></td>
</tr>

<tr>
<td>Fecha ingreso</td>
<td><input type="text" name="fecha_ingreso" id="datepicker" class="span4" placeholder="ingrese fecha" autocomplete="off" required></td>
</tr>

<tr>
<td>CUIL</td>
<td>
	<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="span1" required>-
	<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="span2" required>-
	<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="span1" required>-
</td>
</tr>

<tr>
<td>Legajo</td>
<td><input type="text" name="legajo" class="span4" onkeypress="return isNumberKey(event)" placeholder="Legajo" required></td>
</tr>

<tr>
<td>Empresa</td>
<td><select class="span4" name="empresa" required>
		<option></option>
	<? do{ ?>	
		<option value="<? echo $row_empresa['id_empresa'];?>"><? echo $row_empresa['empresa'];?></option>
	<? }while ($row_empresa = mysql_fetch_array($empresa)) ?>
	</select>
</td>
</tr>  

<tr>
<td>Departamento</td>
<td><select class="span4" name="departamento" required>
		<option></option>
	<? do{ ?>	
		<option value="<? echo $row_departamento2['id_departamento'];?>"><? echo $row_departamento2['nombre'];?></option>
	<? }while ($row_departamento2 = mysql_fetch_array($departamento2)) ?>
	</select>
</td>
</tr>  


<tr>
<td>Convenio</td>
<td><select class="span4" name="convenio" required>
		<option></option>
	<? do{ ?>	
		<option value="<? echo $row_convenio['id_convenio'];?>"><? echo $row_convenio['convenio'];?></option>
	<? }while ($row_convenio = mysql_fetch_array($convenio)) ?>
	</select>
</td>
</tr>  

<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="nuevo" value="1"><i class="icon-plus-sign-alt"></i> Alta</button>
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
	<td>Usuario</td>
	<td>Legajo</td>
	<td>Depar.</td>
	<td>Estado</td>
	<td>Operación</td>
	</tr>
</thead>

<tbody>
<?do{ ?>
<tr>
<td><? echo $row_usuario['usuario'];?></td>
<td><? echo $row_usuario['legajo'];?></td>
<td><? echo $row_usuario['departamento'];?></td>
<td>
		<?if ($row_usuario['id_estado']==0) {?>
		baja
	<? } else { ?>
		activo
	<? } ?>
</td>
<td><A class="btn btn-primary" title="Editar usuario" HREF="modificar.php?id=<? echo $row_usuario['id_usuario'];?>"><i class="icon-edit"></i></A>
	<?if ($row_usuario['id_estado']==0) {?>
	<A type="submit" class="btn btn-danger disabled"  title="El usuario ya esta dado de baja"><i class="icon-minus-sign"></i></i></A>
	<? } else { ?>
	<A type="submit" class="btn btn-danger"  title="Dar de baja" HREF="eliminar.php?id=<? echo $row_usuario['id_usuario'];?>"><i class="icon-minus-sign"></i></i></A>
	<? } ?>
	</td>
</tr>
<? }while ($row_usuario = mysql_fetch_array($usuario)) ?>
</tbody>

</table>
</div>



</center>
</div>

<? include_once("footer.php");?>
