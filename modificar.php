<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."usuarios_model.php");
include_once($models_url."departamentos_model.php");
include_once($models_url."empresas_model.php");
include_once($models_url."convenios_model.php");
include_once($models_url."imagenes_model.php");

if(isset($_GET['id'])){
  $id=$_GET['id'];
}else{
  $id=$_POST['id'];
}

//seleccion del usuario
$usuario=$usuario=getUsuario($id);   
$row_usuario = mysql_fetch_assoc($usuario);

//para departamentos
$departamento=getDepartamentos();
$row_departamento = mysql_fetch_assoc($departamento);
$numero_filas = mysql_num_rows($departamento);

//para empresa
$empresa=getEmpresas();
$row_empresa = mysql_fetch_assoc($empresa);
$numero_empresas = mysql_num_rows($empresa);


//para convenio
$convenio=getConvenios();
$row_convenio = mysql_fetch_assoc($convenio);
$numero_convenio = mysql_num_rows($convenio);

if(isset($_FILES['foto'])){
	
	$extension = pathinfo($_FILES['foto']['name']); 
	$extension = ".".$extension['extension']; 		
	$_FILES['foto']['name']=$id.$extension;
  
  copy($_FILES['foto']['tmp_name'],$imagenes_perfil_url.$_FILES['foto']['name']);
  
  $foto_nombre=$_FILES['foto']['name'];
  $foto_tipo=$_FILES['foto']['type'];
  $foto_size=$_FILES['foto']['size'];
  
  $foto=array('foto_nombre'=> $foto_nombre,
              'foto_tipo'=>$foto_tipo,
              'foto_size'=>$foto_size,
              'id_usuario'=>$id);
  updateFoto($foto);
}

?>
<div class="row">

<div class="span9">
<center>

<!-- formulario de modificacion-->
<form class="form-inline" action="usuarios.php">
<table class="table table-hover">
<tr>
<input type="hidden" name="id" class="span4" value="<?php echo $row_usuario['id_usuario'];?>">


<tr>
<td>Usuario</td>
<td><input type="text" name="usuario" class="span4" value="<?php echo $row_usuario['usuario'];?>" required></td>
</tr>

<tr>
<td>Nombre</td>
<td><input type="text" name="nombre" class="span4" value="<?php echo $row_usuario['nombre'];?>" required></td>
</tr>

<tr>
<td>Apellido</td>
<td><input type="text" name="apellido" class="span4" value="<?php echo $row_usuario['apellido'];?>" required></td>
</tr>

<tr>
<td>DNI</td>
<td><input type="text" name="dni" onkeypress="return isNumberKey(event)" maxlength="8" class="span4" value="<?php echo $row_usuario['dni'];?>" required></td>
</tr>

<tr>
<td>Fecha ingreso</td>
<td><input type="text" name="fecha_ingreso" class="span4" value="<?php echo date( "d-m-Y", strtotime($row_usuario['fecha_ingreso'])); ;?>" required></td>
</tr>

<tr>
<td>Cuil</td>
<td>
	<input type="text" name="cuil1" onkeypress="return isNumberKey(event)" maxlength="2" class="span1" value="<?php echo substr($row_usuario['cuil'], 0, 2);?>" required>-
	<input type="text" name="cuil2" onkeypress="return isNumberKey(event)" maxlength="8" class="span2" value="<?php echo substr($row_usuario['cuil'], 3, 8);?>" required>-
	<input type="text" name="cuil3" onkeypress="return isNumberKey(event)" maxlength="1" class="span1" value="<?php echo substr($row_usuario['cuil'], 12, 1);?>" required>
</td>
</tr>

<tr>
<td>Legajo</td>
<td><input type="text" name="legajo" class="span4" onkeypress="return isNumberKey(event)" value="<?php echo $row_usuario['legajo'];?>" required></td>
</tr>

<tr>
<td>Empresa</td>
<td>
	<select class="span4" name="empresa">
	<?php
		do{ 
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
<td>
	<select class="span4" name="departamento">
	<?php 	
	
		do{ 
		if ($row_usuario['id_departamento']==$row_departamento['id_departamento']){?>	
		 <option value="<?php echo $row_departamento['id_departamento'];?>" selected><?php echo $row_departamento['nombre'];?></option>
	<?php	 }else{ ?>
	  <option value="<?php echo $row_departamento['id_departamento'];?>"><?php echo $row_departamento['nombre'];?></option>
	<?php }?>
	<?php } while ($row_departamento = mysql_fetch_array($departamento))?>
	</select>
</td>
</tr>  


<tr>
<td>Convenio</td>
<td>
	<select class="span4" name="convenio">
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
<button type="submit" class="btn btn-primary" name="modificar" value="1" title="Editar usuario al usuario <?php echo $row_usuario['nombre'];?>"><i class="icon-edit"></i> Editar</button>
<A class="btn btn-danger"  title="Cancelar la edición" HREF="usuarios.php"><i class="icon-ban-circle"></i> Cancelar</A></td>
</tr>  


</table>
</form>



</center>
</div>
<div class="span3">
  <form action="modificar.php" method="post" enctype="multipart/form-data">
    <?php if(isset($_FILES['foto'])){
      echo "La foto se registro en el servidor.<br>";
      echo "<img src=\"$imagenes_perfil_url$foto_nombre\">";
    }else{
      $foto_nombre=$row_usuario['foto_nombre'];
      echo "<img src=\"$imagenes_perfil_url$foto_nombre\">";
    }
    ?>
<input type="file" name="foto"><br>
<input type="hidden" name="id" value="<?php echo $id?>">
<input type="submit" class ="btn btn-default" value="Enviar">
</div>


<?php include_once("footer.php");?>