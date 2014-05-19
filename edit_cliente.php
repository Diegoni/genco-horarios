<?php
include_once("head.php");      

	//asigno las variables
 	$id = $_GET['id'];
	
	//seleccion del movimiento
	$query="SELECT * FROM `log_auditoria_usuario` WHERE id_log_usuario='".$id."'";   
	$result=mysql_query($query) or die(mysql_error());
	mysql_query("SET NAMES 'utf8'");
	
	while ($rows = mysql_fetch_array($result))
 	{ ?>	
	<br>
	<div class="container; celeste">
	
	<table class="table table-hover">
	<tr>
	<td><b>Accion</b></td>
	<td><?echo $rows['Accion']?></td>
	</tr>
	<tr>
	<td></td>
	<td><b>Anterior</b></td>
	<td><b>Nuevo</b></td>
	</tr>
	<tr>
	<td><b>Nombre</b></td>
	<td><?echo $rows['nombre_old']?></td>
	<td><?echo $rows['nombre_new']?></td>
	</tr>
	<tr>
	<td><b>Legajo</b></td>
	<td><?echo $rows['legajo_old']?></td>
	<td><?echo $rows['legajo_new']?></td>
	</tr>
	<tr>
	<td><b>Dept.</b></td>
	<td><?echo $rows['departamento_iddepartamento_old']?></td>
	<td><?echo $rows['departamento_iddepartamento_new']?></td>
	</tr>
	<tr>
	<td><b>Estado</b></td>
	<td><?echo $rows['activo_old']?></td>
	<td><?echo $rows['activo_new']?></td>
	</tr>
	</table>
	<button type="button" onClick="window.close()" class="btn btn-info">Cerrar</button>
	
		
		
		
	</div> 
 	<? 	} // cierra el while ?>
 
