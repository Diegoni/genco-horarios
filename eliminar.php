<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."convenios_model.php");

//Para seleccionar el cliente
$usuario		= getUsuario($_GET['id']);   
$row_usuario	= mysql_fetch_assoc($usuario);

//para empresa
$empresa		= getEmpresas();
$row_empresa	= mysql_fetch_assoc($empresa);
$numero_empresas= mysql_num_rows($empresa);

//Para los departamentos
$departamento	= getDepartamentos();
$row_departamento = mysql_fetch_assoc($departamento);
$numero_filas	= mysql_num_rows($departamento);

//para convenio
$convenio		= getConvenios();
$row_convenio 	= mysql_fetch_assoc($convenio);
$numero_convenio = mysql_num_rows($convenio);


?>
<div class="span9">
<center>

<!-- Formulario de baja usuario -->



<form class="form-inline" action="empleados.php">
<table class="table table-hover">
	<tr>
	<input type="hidden" name="id" class="form-control" value="<?php echo $row_usuario['id_usuario'];?>">
	
	
	<tr>
		<td>Nombre</td>
		<td><input type="text" name="usuario" class="form-control" value="<?php echo $row_usuario['usuario'];?>" disabled></td>
	</tr>
	
	<tr>
		<td>Nombre</td>
		<td><input type="text" name="nombre" class="form-control" value="<?php echo $row_usuario['nombre'];?>" disabled></td>
	</tr>
	
	<tr>
		<td>Apellido</td>
		<td><input type="text" name="apellido" class="form-control" value="<?php echo $row_usuario['apellido'];?>" disabled></td>
	</tr>
	
	<tr>
		<td>DNI</td>
		<td><input type="text" name="dni" onkeypress="return isNumberKey(event)" maxlength="8" class="form-control" value="<?php echo $row_usuario['dni'];?>" disabled></td>
	</tr>
	
	<tr>
		<td>Cuil</td>
		<td>
			<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="form-control" value="<?php echo substr($row_usuario['cuil'], 0, 2);?>" disabled>-
			<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="form-control" value="<?php echo substr($row_usuario['cuil'], 3, 8);?>" disabled>-
			<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="form-control" value="<?php echo substr($row_usuario['cuil'], 12, 1);?>" disabled>
		</td>
	</tr>
	
	<tr>
		<td>Legajo</td>
		<td><input type="text" name="legajo" class="form-control" onkeypress="return isNumberKey(event)" value="<?php echo $row_usuario['legajo'];?>" disabled></td>
	</tr>
	
	<tr>
		<td>Estado</td>
		<td>
		<input type="radio" name="estado" id="alta" value="0" checked data-on="success" data-off="danger" >
		 Baja
		</td>
	</tr>
	
	<tr>
		<td>Empresa</td>
		<td><select class="form-control" name="empresa" disabled>
			<?php 	do{ 
				if ($row_usuario['id_empresa']==$row_empresa['id_empresa']){?>	
				 <option value="<?php echo $row_empresa['id_empresa'];?>" selected><?php echo $row_empresa['empresa'];?></option>
			<?php	 }else{ ?>
			  <option value="<?php echo $row_empresa['id_empresa'];?>"><?php echo $row_empresa['empresa'];?></option>
			<?php }?>
			<?php } while ($row_empresa = mysql_fetch_array($empresa))?>
			</select>
		</td>
	</tr>  
	
	<tr>
		<td>Departamento</td>
		<td><select class="form-control" name="departamento" disabled>
			<!-- Busca los departamentos y selecciona el que es del cliente -->
			<?php 	do{ 
				if ($row_usuario['id_departamento']==$row_departamento['id_departamento']){?>	
				 <option value="<?php echo $row_departamento['id_departamento'];?>" selected><?php echo $row_departamento['nombre'];?></option>
			<?php	 }else{ ?>
			  <option value="<?php echo $row_departamento['id_departamento'];?>"><?php echo $row_departamento['nombre'];?></option>
			<?php }
				}while ($row_departamento = mysql_fetch_array($departamento))?>
			</select>
		</td>
	</tr>  
	
	<tr>
		<td>Convenio</td>
		<td><select class="form-control" name="convenio" disabled>
			<?php 	do{ 
				if ($row_usuario['id_convenio']==$row_convenio['id_convenio']){?>	
				 <option value="<?php echo $row_convenio['id_convenio'];?>" selected><?php echo $row_convenio['convenio'];?></option>
			<?php	 }else{ ?>
			  <option value="<?php echo $row_convenio['id_convenio'];?>"><?php echo $row_convenio['convenio'];?></option>
			<?php }?>
			<?php } while ($row_convenio = mysql_fetch_array($convenio))?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td></td>
		<td>
		<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="eliminar" value="1" rel='tooltip' title="Dar de baja al usuario <?php echo $row_usuario['nombre'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
		<A class="btn btn-danger"  HREF="empleados.php" rel='tooltip' title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A>
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
			<center><h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3></center>			
		</div>
		<div class="modal-body">
			<p>El usuario eliminado no se mostrara más en las planillas de horarios.<p> 
			<p>El usuario no se borra de la base de datos solo se cambia su estado, se puede recuperar el usuario si se elimina.</p>
		</div>
	</div>	
	</div>
</div>

</center>
</div>


<?php include_once("footer.php");?>