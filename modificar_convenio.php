<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."convenios_model.php");

//seleccion del usuario
$convenio=getConvenio($_GET['id']);   
$row_convenio = mysql_fetch_assoc($convenio);

?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="convenios.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="span4" value="<? echo $row_convenio['id_convenio'];?>">

<tr>
		<td>Convenio</td>
		<td><input type="text" name="convenio" placeholder="ingrese convenio" value="<? echo $row_convenio['convenio'];?>"required></td>
</tr>
<tr>
		<td>Horas semana</td>
		<td><input type="number" name="semana" placeholder="hs diarias que trabaja semanalmente" value="<? echo $row_convenio['semana'];?>" required></td>
</tr>
<tr>
		<td>Horas sábado</td>
		<td><input type="number" name="sabado" placeholder="hs que debe trabajar los sábados" value="<? echo $row_convenio['sabado'];?>" required></td>
</tr>
<tr>
		<td>Horario salida sábado</td>
		<td><input type="number" name="salida_sabado" placeholder="hora debe salir el sábado" value="<? echo $row_convenio['salida_sabado'];?>" required></td>
</tr>

<tr>
<td>Estado</td>
<td>
<input type="radio" name="estado" id="alta" value="1" checked>
 Alta
<input type="radio" name="estado" id="baja" value="0">
 Baja
</td>
</tr>


<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" title="Editar convenio <? echo $row_convenio['convenio'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  title="Cancelar la edición" HREF="empresas.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 






</center>
</div>


<? include_once("footer.php");?>