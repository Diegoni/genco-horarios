<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."usuarios_model.php");
include_once($models_url."empresas_model.php");
include_once($models_url."departamentos_model.php");
include_once($models_url."convenios_model.php");

//Para seleccionar el cliente
$usuario=getUsuario($_GET['id']);   
$row_usuario = mysql_fetch_assoc($usuario);

//para empresa
$empresa=getEmpresas();
$row_empresa = mysql_fetch_assoc($empresa);
$numero_empresas = mysql_num_rows($empresa);

//Para los departamentos
$departamento=getDepartamentos();
$row_departamento = mysql_fetch_assoc($departamento);
$numero_filas = mysql_num_rows($departamento);

//para convenio
$convenio=getConvenios();
$row_convenio = mysql_fetch_assoc($convenio);
$numero_convenio = mysql_num_rows($convenio);


?>
<div class="span9">
<center>

<!-- Formulario de baja usuario -->



<form class="form-inline" action="usuarios.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="span4" value="<? echo $row_usuario['id_usuario'];?>">


<tr>
<td>Nombre</td>
<td><input type="text" name="usuario" class="span4" value="<? echo $row_usuario['usuario'];?>" disabled></td>
</tr>

<tr>
<td>Nombre</td>
<td><input type="text" name="nombre" class="span4" value="<? echo $row_usuario['nombre'];?>" disabled></td>
</tr>

<tr>
<td>Apellido</td>
<td><input type="text" name="apellido" class="span4" value="<? echo $row_usuario['apellido'];?>" disabled></td>
</tr>

<tr>
<td>DNI</td>
<td><input type="text" name="dni" onkeypress="return isNumberKey(event)" maxlength="8" class="span4" value="<? echo $row_usuario['dni'];?>" disabled></td>
</tr>

<tr>
<td>Cuil</td>
<td>
	<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="span1" value="<? echo substr($row_usuario['cuil'], 0, 2);?>" disabled>-
	<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="span2" value="<? echo substr($row_usuario['cuil'], 3, 8);?>" disabled>-
	<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="span1" value="<? echo substr($row_usuario['cuil'], 12, 1);?>" disabled>
</td>
</tr>

<tr>
<td>Legajo</td>
<td><input type="text" name="legajo" class="span4" onkeypress="return isNumberKey(event)" value="<? echo $row_usuario['legajo'];?>" disabled></td>
</tr>

<tr>
<td>Estado</td>
<td>
<input type="radio" name="estado" id="alta" value="0" checked>
 Baja
</td>
</tr>

<tr>
<td>Empresa</td>
<td><select class="span4" name="empresa" disabled>
	<? 	do{ 
		if ($row_usuario['id_empresa']==$row_empresa['id_empresa']){?>	
		 <option value="<? echo $row_empresa['id_empresa'];?>" selected><? echo $row_empresa['empresa'];?></option>
	<?	 }else{ ?>
	  <option value="<? echo $row_empresa['id_empresa'];?>"><? echo $row_empresa['empresa'];?></option>
	<? }?>
	<? } while ($row_empresa = mysql_fetch_array($empresa))?>
	</select>
</td>
</tr>  

<tr>
<td>Departamento</td>
<td><select class="span4" name="departamento" disabled>
	<!-- Busca los departamentos y selecciona el que es del cliente -->
	<? 	do{ 
		if ($row_usuario['id_departamento']==$row_departamento['id_departamento']){?>	
		 <option value="<? echo $row_departamento['id_departamento'];?>" selected><? echo $row_departamento['nombre'];?></option>
	<?	 }else{ ?>
	  <option value="<? echo $row_departamento['id_departamento'];?>"><? echo $row_departamento['nombre'];?></option>
	<? }
		}while ($row_departamento = mysql_fetch_array($departamento))?>
	</select>
</td>
</tr>  

<tr>
<td>Convenio</td>
<td><select class="span4" name="convenio" disabled>
	<? 	do{ 
		if ($row_usuario['id_convenio']==$row_convenio['id_convenio']){?>	
		 <option value="<? echo $row_convenio['id_convenio'];?>" selected><? echo $row_convenio['convenio'];?></option>
	<?	 }else{ ?>
	  <option value="<? echo $row_convenio['id_convenio'];?>"><? echo $row_convenio['convenio'];?></option>
	<? }?>
	<? } while ($row_convenio = mysql_fetch_array($convenio))?>
	</select>
</td>
</tr>

<tr>
<td></td>
<td>
<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="eliminar" value="1" title="Dar de baja al usuario <? echo $row_usuario['nombre'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
<A class="btn btn-danger"  HREF="usuarios.php" title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A>
</td>
</tr>  


</table>
</form>

<a href="#myModal" role="button" class="btn" id="opener" data-toggle="modal"><i class="icon-question-sign"></i></a>
 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3>
</div>
<div class="modal-body">
<p>El usuario eliminado no se mostrara más en las planillas de horarios.<p> 
<p>El usuario no se borra de la base de datos solo se cambia su estado, se puede recuperar el usuario si se elimina.</p>
</div>
<div class="modal-footer">
<button class="btn" data-dismiss="modal" aria-hidden="true">Aceptar</button>
</div>
</div>
 



</center>
</div>


<? include_once("footer.php");?>