<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."departamentos_model.php");  

//seleccion del usuario
$departamento=getDepartamento($_GET['id']);
$row_departamento = mysql_fetch_assoc($departamento);

$action=$_GET['action'];
$input_action="";
if($action==0){
	$input_action="readonly";
}


?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="departamentos.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="span4" value="<?php echo $row_departamento['id_departamento'];?>">


<tr>
<td>Departamento</td>
<td><input type="text" name="departamento" class="span4" value="<?php echo $row_departamento['nombre'];?>" <?php echo $input_action; ?> required></td>
</tr>

<?php if($action==0){?>
<tr>
<td>Estado</td>
<td>
<input type="radio" name="estado" id="baja" value="0" checked data-on="success" data-off="danger" >
 Baja
</td>
</tr>


<tr>
<td></td>
<td>
<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="delete" value="1" title="Dar de baja al departamento <?php echo $row_departamento['nombre'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
<A class="btn btn-danger"  HREF="departamentos.php" title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A></td>
</tr>  

</table>
</form>

<a href="#myModal" role="button" class="btn btn-default" id="opener" data-toggle="modal"><i class="icon-question-sign"></i></a>
 
<!-- Modal -->
<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
<div class="modal-header">
<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
<h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3>
</div>
<div class="modal-body">
<p>El departamento eliminado no se mostrara más en las planillas de horarios.<p> 
<p>El departamento no se borra de la base de datos solo se cambia su estado, se puede recuperar el departamento si se elimina.</p>
</div>
<div class="modal-footer">
<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
</div>
</div>

<?php }else{?>
<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" title="Editar departamento <?php echo $row_departamento['nombre'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  title="Cancelar la edición" HREF="departamentos.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr> 

</table>
</form> 
<?php }?>





</center>
</div>


<?php include_once("footer.php");?>