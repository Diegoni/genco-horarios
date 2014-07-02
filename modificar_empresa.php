<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."empresas_model.php");

//seleccion del usuario
$empresa=getEmpresa($_GET['id']);   
$row_empresa = mysql_fetch_assoc($empresa);

$action=$_GET['action'];
$input_action="";
if($action==0){
	$input_action="readonly";
}


?>
<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="empresas.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="span4" value="<?php echo $row_empresa['id_empresa'];?>">


<tr>
<td>Empresa</td>
<td><input type="text" name="empresa" class="span4" value="<?php echo $row_empresa['empresa'];?>" <?php echo $input_action; ?> required></td>
</tr>

<tr>
<td>Cod</td>
<td><input type="text" name="cod_empresa" class="span4" value="<?php echo $row_empresa['cod_empresa'];?>" <?php echo $input_action; ?> required></td>
</tr>

<tr>
<td>Cuil</td>
<td>
	<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="span1" value="<?php echo substr($row_empresa['cuil'], 0, 2);?>" <?php echo $input_action; ?> required>-
	<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="span2" value="<?php echo substr($row_empresa['cuil'], 3, 8);?>" <?php echo $input_action; ?> required>-
	<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="span1" value="<?php echo substr($row_empresa['cuil'], 12, 1);?>" <?php echo $input_action; ?> required>
</td>
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
<button type="submit" onclick="return confirm('Esta seguro de eliminar este item?');" class="btn btn-primary" name="delete" value="1" title="Dar de baja a la empresa <?php echo $row_empresa['empresa'];?>"><i class="icon-minus-sign"></i> Eliminar</button>
<A class="btn btn-danger"  HREF="empresas.php" title="Cancelar la baja"> <i class="icon-ban-circle"></i> Cancelar</A></td>
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
<p>La empresa eliminada no se mostrara más en las planillas de horarios.<p> 
<p>La empresa no se borra de la base de datos solo se cambia su estado, se puede recuperar la empresa si se elimina.</p>
</div>
<div class="modal-footer">
<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Aceptar</button>
</div>
</div>
<?php }else{?>


<tr>
<td></td>
<td>
<button type="submit" class="btn btn-primary" name="modificar" value="1" title="Editar empresa <?php echo $row_empresa['empresa'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  title="Cancelar la edición" HREF="empresas.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr>  

</table>
</form>
<?php }?>





</center>
</div>

<?php include_once("footer.php");?>