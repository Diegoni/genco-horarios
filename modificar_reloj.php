<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."relojes_model.php");  

//seleccion del usuario
$relojes	= getReloj($_GET['id']);
$row_reloj	= mysql_fetch_assoc($relojes);

?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="relojes.php" name="relojes">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="form-control" value="<?php echo $row_reloj['id_reloj'];?>">


<tr>
	<td>Reloj</td>
	<td><input type="text" name="reloj" class="form-control" value="<?php echo $row_reloj['reloj'];?>" maxlength="32" required></td>
</tr>

<tr>
	<td>IP</td>
	<td><input type="text" name="ip" class="form-control" value="<?php echo $row_reloj['ip'];?>" onblur="ValidateIPaddress(document.relojes.ip)" required></td>
</tr>

<tr>
	<td>Puerto</td>
	<td><input type="text" name="puerto" class="form-control" value="<?php echo $row_reloj['puerto'];?>" onkeypress="return isNumberKey(event)" maxlength="4"  required></td>
</tr>

<tr>
	<td>Color</td>
	<td><input type="text" name="color" class="form-control" value="<?php echo $row_reloj['color'];?>" maxlength="7" required></td>
</tr>


<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="update" value="1" rel='tooltip' title="Editar registro"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="relojes.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 






</center>
</div>


<?php include_once("footer.php");?>