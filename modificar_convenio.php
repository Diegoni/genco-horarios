<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."convenios_model.php");

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
<input type="hidden" name="id" class="span4" value="<?php echo $row_convenio['id_convenio'];?>">

<tr>
		<td>Convenio</td>
		<td><input type="text" name="convenio" placeholder="ingrese convenio" value="<?php echo $row_convenio['convenio'];?>" required></td>
</tr>

<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" title="Editar convenio <?php echo $row_convenio['convenio'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  title="Cancelar la edición" HREF="convenios.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 






</center>
</div>


<?php include_once("footer.php");?>