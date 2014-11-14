<?php
session_start();
include_once("control_usuario.php");
include_once("menu.php");
include_once($url['models_url']."convenios_model.php");

//seleccion del usuario
$convenio=getConvenio($_GET['id']);   
$row_convenio = mysql_fetch_assoc($convenio);

?>
<div class="col-md-9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="convenios.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="form-control" value="<?php echo $row_convenio['id_convenio'];?>">

<tr>
		<td>Convenio</td>
		<td><input type="text" class="form-control" name="convenio" placeholder="ingrese convenio" value="<?php echo $row_convenio['convenio'];?>" maxlength="32" required></td>
</tr>

<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" rel='tooltip' title="Editar convenio <?php echo $row_convenio['convenio'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="convenios.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 






</center>
</div>


<?php include_once("footer.php");?>