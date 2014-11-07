<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($url['models_url']."configs_model.php");
include_once($url['models_url']."mensajes_model.php"); 
include_once($url['models_url']."imagenes_model.php");

if(isset($_GET['update'])){
	if($_GET['update']==1){
		echo "<script>alert('Si no se van a aplicar los redondeos se deben “Mostar marcadas sin redondeos”')</script>";	
	}	
	
	echo getMensajes('update', 'ok', 'Config', 1);
}

if(isset($_FILES['foto'])){
	
	$extension = pathinfo($_FILES['foto']['name']); 
	$extension = ".".$extension['extension']; 		
	$_FILES['foto']['name']='logo_menu'.$extension;
  
  copy($_FILES['foto']['tmp_name'],'imagenes/'.$_FILES['foto']['name']);
  
  $foto_nombre=$_FILES['foto']['name'];
  $foto_tipo=$_FILES['foto']['type'];
  $foto_size=$_FILES['foto']['size'];
  
  $foto=array('foto_nombre'=> $foto_nombre,
              'foto_tipo'=>$foto_tipo,
              'foto_size'=>$foto_size,
              'id_config'=>1);
  updateFotologo($foto);
} 


if(isset($_FILES['firma'])){
	
	$extension = pathinfo($_FILES['firma']['name']); 
	$extension = ".".$extension['extension']; 		
	$_FILES['firma']['name']='firma'.$extension;
  
  copy($_FILES['firma']['tmp_name'],'imagenes/'.$_FILES['firma']['name']);
  
  $foto_nombre=$_FILES['firma']['name'];
  $foto_tipo=$_FILES['firma']['type'];
  $foto_size=$_FILES['firma']['size'];
  
  $foto=array('foto_nombre'=> $foto_nombre,
              'foto_tipo'=>$foto_tipo,
              'foto_size'=>$foto_size,
              'id_config'=>1);
  updateFirma($foto);
} 

	$configs=getConfig();
	$row_config = mysql_fetch_assoc($configs);	
	do{
		$id_config=$row_config['id_config'];
	 }while($row_config=mysql_fetch_array($configs));
	 
	 

?>
<div class="row">
<div class="col-md-12">
<form class="form-inline" action="config_script.php">
	<p class="block-title">Configuración</p>
	<div>
		<button type="submit" class="btn btn-primary" name="update" value="1" title="Editar config"><i class="icon-edit"></i> Editar</button>
	</div>
<div class="divider"></div>
<div class="panel panel-default">
<div class="panel-body">
<div class="tabbable"> <!-- Only required for left/right tabs -->
	<ul class="nav nav-tabs">
		<li class="active"><a href="#tab1" data-toggle="tab">Sistema</a></li>
    	<li><a href="#tab2" data-toggle="tab">Redondeo</a></li>
    	<li><a href="#tab3" data-toggle="tab">Impresión</a></li>
	</ul>
  	<div class="tab-content">
    
    <div class="tab-pane active" id="tab1">
		<table class="table table-hover">
		<tr>
			<td>Título</td>
			<td><input type="text" class="form-control" name="title" value="<?php echo $config['title'];?>" required></td>
		</tr>
		
		<tr>
			<td>Logo</td>
			<td><img width="106" height="40"  src="<?php echo $config['logo'];?>"> <a href='#' class='show_hide btn btn-default'>Cambiar</a></td>
		</tr>
		<tr>
		<td>Colores</td>
			<td>
			<select name="css" class="form-control">
				<?php for($i=0;$i<21;$i++){ 
						if($i==$config['css']){ ?>
							<option value="<?php echo $i?>" selected>Opción <?php echo $i+1;?></option>
				<?php	}else{ ?>
							<option value="<?php echo $i?>">Opción <?php echo $i+1;?></option>		
				<?php 	} ?>
				<?php }?>
			</select>
			</td>
		</tr>
		</table>
    </div>
    
    <div class="tab-pane" id="tab2">
    	<table class="table table-hover">
		<input type="hidden" name="id" value="<?php echo $id_config?>">
		<tr>
			<td>Aplicar Redondeo</td>
			<td><input type="checkbox" name="aplicar_redondeo" <?php if($config['aplicar_redondeo']==1){ echo "checked";}?> data-on="success" data-off="danger" ></td>
		</tr>
		
		<tr>
			<td>Mostrar marcada sin redondeo</td>
			<td><input type="checkbox" name="mostrar_marcada" <?php if($config['mostrar_marcada']==1){ echo "checked";}?> data-on="success" data-off="danger" ></td>
		</tr>
		</table>      	
    </div>
   
	<div class="tab-pane" id="tab3">
       	<table class="table table-hover">
		<tr>
			<td>Mostrar fecha actual</td>
			<td><input type="checkbox" name="fecha_actual"  <?php if($config['fecha_actual']==1){ echo "checked";}?> data-on="success" data-off="danger" ></td>
		</tr>
			
		<tr>
			<td>Mostrar fecha de marcación más </td>
			<td>
				<div class="form-group">
    				<div class="input-group">
    				<input id="suma_dias" type="number" class="form-control" name="suma_dias" value="<?php echo $config['suma_dias'];?>">
					<div class="input-group-addon">días</div>
      				</div>
				</div>
			</td>
		</tr>
		
		<tr>
			<td>Cantidad de marcaciones por hoja </td>
			<td>
				<div class="form-group">
    				<div class="input-group">
					<input id="registros" type="number" class="form-control" name="marcaciones_x_hoja" value="<?php echo $config['marcaciones_x_hoja'];?>" max="6" min="1">
					<div class="input-group-addon">Marcaciones</div>
      				</div>
				</div>
			</td>
		</tr>
		<tr>
			<td>Firma</td>
			<td><img width="106" height="40"  src="<?php echo $config['firma'];?>"> <a href='#' class='show_hide2 btn btn-default'>Cambiar</a></td>
		</tr>
		</table>
    </div>
  </div>
</div>
</div>
</div>
</form>

<div class="slidingDiv">
	<h4>Logo</h4>
	<form action="config.php" method="post" enctype="multipart/form-data">
		<input type="file" name="foto"><br>	
		<input type="submit" class ="btn btn-default" value="Enviar">
		<a href='#' class='show_hide btn btn-danger'>Cancelar</a>
	</form>
</div>
 
<div class="slidingDiv2">
	<h4>Firma</h4>
	<form action="config.php" method="post" enctype="multipart/form-data">
		<input type="file" name="firma"><br>	
		<input type="submit" class ="btn btn-default" value="Enviar">
		<a href='#' class='show_hide2 btn btn-danger'>Cancelar</a>
	</form>
</div>
</center>
</div>


<?php include_once("footer.php");?>