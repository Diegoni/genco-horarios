<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."limites_model.php");  

//seleccion del usuario
$limite=getLimite($_GET['id']);
$row_limite = mysql_fetch_assoc($limite);

?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="limites.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="form-control" value="<?php echo $row_limite['id_limite'];?>">


<tr>
<td>Limite</td>
<td><input type="number" name="limite" class="form-control" value="<?php echo $row_limite['limite'];?>" required></td>
</tr>

<tr>
<td>Redondeo</td>
<td><input type="number" name="redondeo" class="form-control" value="<?php echo $row_limite['redondeo'];?>" required></td>
</tr>

<tr>
<td>Sumar hora</td>
<td><input type="checkbox" name="suma_hora" <?php if($row_limite['suma_hora']==1){ echo "checked";}?>></td>
</tr>


<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="update" value="1" rel='tooltip' title="Editar limite"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  rel='tooltip' title="Cancelar la edición" HREF="limites.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 






</center>
</div>


<?php include_once("footer.php");?>