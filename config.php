<?php
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: ../login/acceso.php");
	}
include_once("menu.php");
include_once($models_url."configs_model.php");
include_once($models_url."mensajes_model.php"); 

if(isset($_GET['update'])){
	if($_GET['update']==1){
		echo "<script>alert('Si no se van a aplicar los redondeos se deben “Mostar marcadas sin redondeos”')</script>";	
	}	
	
	echo getMensajes('update', 'ok', 'Config', 1);
}
 

	$config=getConfig();
	$row_config = mysql_fetch_assoc($config);	
	do{
		$id_config=$row_config['id_config'];
		$aplicar_redondeo=$row_config['aplicar_redondeo'];
		$mostrar_marcada=$row_config['mostrar_marcada'];
		$css=$row_config['css'];
	 }while($row_config=mysql_fetch_array($config)); 

?>
<div class="row">
<div class="span12">
<center>
<form class="form-inline" action="config_script.php">
<div ALIGN=left class="well">
	<button type="submit" class="btn btn-primary" name="update" value="1" title="Editar config"><i class="icon-edit"></i> Editar</button>
	<div class="pull-right"><h4>Configuración</h4></div>
</div>

<div class="tabbable"> <!-- Only required for left/right tabs -->
  <ul class="nav nav-tabs">
    <li class="active"><a href="#tab1" data-toggle="tab">Redondeo</a></li>
    <li><a href="#tab2" data-toggle="tab">Sistema</a></li>
    <li><a href="#tab3" data-toggle="tab">Colores</a></li>
  </ul>
  <div class="tab-content">
    <div class="tab-pane active" id="tab1">
		<table class="table table-hover">
		
		<input type="hidden" name="id" value="<?php echo $id_config?>">
		<tr>
		<td>Aplicar Redondeo</td>
		<td><input type="checkbox" name="aplicar_redondeo" <?php if($aplicar_redondeo==1){ echo "checked";}?>></td>
		</tr>
		
		<tr>
		<td>Mostrar marcada sin redondeo</td>
		<td><input type="checkbox" name="mostrar_marcada" <?php if($mostrar_marcada==1){ echo "checked";}?>></td>
		</tr>
		
		</table>
    </div>
    <div class="tab-pane" id="tab2">
      	<table class="table table-hover">
		

		<tr>
		<td>Título</td>
		<td><input type="text" name="title" value="<?php echo $title;?>" required></td>
		</tr>
	
		</table>
    </div>
    <div class="tab-pane" id="tab3">
     	<table class="table table-hover">

		<tr>
		<td>Colores</td>
		<td>
			<select name="css">
				<?php for($i=0;$i<21;$i++){ 
						if($i==$css){ ?>
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
  </div>
</div>



</form> 
</center>
</div>


<?php include_once("footer.php");?>