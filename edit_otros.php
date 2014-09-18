<?php 
include_once("head.php");      
include_once($url['models_url']."otrahora_model.php");
include_once($url['models_url']."archivos_model.php");
include_once("helpers.php");        

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Editar entradas
//----------------------------------------------------------------------
//----------------------------------------------------------------------
	
	
	if(isset($_GET['id'])){
		$id=$_GET['id'];
		$fecha=$_GET['fecha'];	
	}elseif(isset($_POST['id'])){
		$id=$_POST['id'];
		$fecha=$_POST['fecha'];
	}
	
	$bandera=1;
	
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Editar archivos
//----------------------------------------------------------------------
//----------------------------------------------------------------------	
	
	if(isset($_FILES['archivo'])){
		$id_archivo=$_POST['id_archivo'];
		if($id_archivo!=0){
			try {
				$archivo=getArchivo($_POST['id_archivo']);
				$row_archivo = mysql_fetch_assoc($archivo);
				unlink($url['arhivo_otra_hora'].$row_archivo['nombre']);	
			} catch (Exception $e) {
				trigger_error("No se puede borrar el archivo".$e->getMessage(), E_WARNING);
			}
			
			$extension = pathinfo($_FILES['archivo']['name']); 
			$extension = ".".$extension['extension']; 		
			$_FILES['archivo']['name']=$id_archivo.$extension;
			
		}else{
			$query="SELECT 	max(id_archivo) as id
			FROM `archivo`";   
			$archivo=mysql_query($query) or die(mysql_error());
			$row_archivo = mysql_fetch_assoc($archivo);
			$max=$row_archivo['id']+1;
			
			$extension = pathinfo($_FILES['archivo']['name']); 
			$extension = ".".$extension['extension']; 		
			$_FILES['archivo']['name']=$max.$extension;
		}

		try{
			copy($_FILES['archivo']['tmp_name'],$url['arhivo_otra_hora'].$_FILES['archivo']['name']);	
		}catch (Exception $e){
			trigger_error("No se puede copiar el archivo".$e->getMessage(), E_WARNING);
		}
	  	
	  
	  	$archivo_nombre=$_FILES['archivo']['name'];
	  	$archivo_tipo=$_FILES['archivo']['type'];
		$archivo_extension=$extension;
	  	$archivo_size=$_FILES['archivo']['size'];
	  
	  	$archivo=array('archivo_nombre'=> $archivo_nombre,
	              'archivo_tipo'=>$archivo_tipo,
	              'archivo_size'=>$archivo_size,
	              'archivo_extencion'=>$archivo_extension,
	              'id_archivo'=>$id_archivo,
	              'id_otrahora'=>$_POST['id_otrahora']);
		if($id_archivo==0){
			insertArchivo($archivo);	
		}else{
			updateArchivo($archivo);
		}
	  	
	}	


	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Edicion de entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['modificar'])){
	updateOtrahora(	$_GET['id_tipootra'],
									$_GET['horas'],
									$_GET['fecha'],
									$_GET['nota'],
									$_GET['id_nota'],
									$_GET['id_otrahora']);
	
	echo 
		"<script>
		<!--alert('Las entradas fueron modificadas con éxito');-->
		opener.location.reload();
		window.close();
		</script>";
		
	} 
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nueva entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['agregar'])){
	insertOtrahora(	$_GET['id'], 
									$_GET['id_tipootra'], 
									$_GET['nota'], 
									$_GET['horas'], 
									$_GET['fecha']);

	echo 	"<script>
			<!--alert('La entrada fue ingresada con éxito');-->
			opener.location.reload();
			window.close();
			</script>";								
	
	}
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Consultas
----------------------------------------------------------------------
--------------------------------------------------------------------*/		
	
	$otrahora=getOtrahora($id, $fecha);
	$row_otrahora = mysql_fetch_assoc($otrahora);
	$numero_otrahora = mysql_num_rows($otrahora);
	
	
	
	$tipootra=getTipootra();
	$row_tipootra = mysql_fetch_assoc($tipootra);
	$id_tipootra=$row_otrahora['id_tipootra'];
	?>
	
	<div class="container; celeste">
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------->		

	<form action="edit_otros.php" method="get" > 
	
	<?php  if($numero_otrahora>0){ ?>	
	<fieldset>
	<legend>Otras marcaciones:</legend>
	<table>	
	<tr>
	<td>Tipo</td>
	<td>
		<select name="id_tipootra" class="input-medium">
		<?php 
		do{	if($id_tipootra==$row_tipootra['id_tipootra']){		
				?>
				<option value="<?php  echo $row_tipootra['id_tipootra']?>" selected>
				<?php  echo $row_tipootra['tipootra']?></option>
			<?php } else {?>
				<option value="<?php  echo $row_tipootra['id_tipootra']?>">
				<?php  echo $row_tipootra['tipootra']?></option>
			<?php }
		}while ($row_tipootra = mysql_fetch_array($tipootra));?>
		</select>
	</td>
	</tr>
	<tr>
	<td>Horas</td>
	<td><input type="number" class="input-medium" name="horas" value="<?php echo $row_otrahora['horas']?>" required></td>
	</tr>
	<input type="hidden" class="input-medium" name="fecha" value="<?php echo $row_otrahora['fecha']?>" required>
	<tr>
	<td>Comentario</td>
	<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota"><?php echo $row_otrahora['nota']?></textarea></td>
	</tr>
	</tr>

	<tr>
	<td colspan="5">
		<center>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			<a href='#' class='btn' title='no se puede ingresar uno nuevo' disabled>nuevo</a>
			<input type="submit" class="btn btn-default" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar">		
			<input type="hidden" name="id_otrahora" value="<?php echo $row_otrahora['id_otrahora']?>">
			<input type="hidden" name="id_nota" value="<?php echo $row_otrahora['id_nota']?>">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
		</center>
	</td>
	</tr>
	</table>
	</fieldset>
	</form>
	<fieldset>
	<legend>Documento:</legend>
	<form action="edit_otros.php" method="post" enctype="multipart/form-data">
		<?php $id_otrahora=$row_otrahora['id_otrahora'];?>	
	    <?php if($row_otrahora['id_archivo']!=0){
	    	$id_archivo=$row_otrahora['id_archivo'];
	    	$archivo = getArchivo($id_archivo);
			$row_archivo = mysql_fetch_assoc($archivo);
			
			$archivo_nombre=$row_archivo['nombre'];
	    	
			$icono=devuelve_icono($row_archivo['extension'], $url['iconos_url']);
					
			echo "<a href=".$url['arhivo_otra_hora'].$archivo_nombre."'>
				<img src='$icono'></a></br>"; 
		?>
		<a href='#' class='show_hide btn' title='Nuevo'>Cambiar</a>
		<div class="slidingDiv">
			<br>
			<input type="file" name="archivo"><br>	
			<input type="hidden" name="fecha" value="<?php echo $fecha;?>">
			<input type="hidden" name="id" value="<?php echo $id?>">
			<input type="hidden" name="id_archivo" value="<?php echo $id_archivo?>">
			<input type="hidden" name="id_otrahora" value="<?php echo $id_otrahora;?>">
		
			<input type="submit" class ="btn btn-default" value="Enviar">
		</div>
		
		<?php }else{?>
			
		<input type="file" name="archivo"><br>	
		<input type="hidden" name="fecha" value="<?php echo $fecha;?>">
		<input type="hidden" name="id" value="<?php echo $id?>">
		<input type="hidden" name="id_archivo" value="0">
		<input type="hidden" name="id_otrahora" value="<?php echo $id_otrahora;?>">
		
		<input type="submit" class ="btn btn-default" value="Enviar">
		
		<?php } ?>
	</form>
	</fieldset>
	
	</div><!--Cierra el div class="celeste"-->	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Formulario nueva entrada
----------------------------------------------------------------------
--------------------------------------------------------------------->	
	<?php }else{?>
	<fieldset>
	<legend>Otras marcaciones:</legend>
	<table>
	<tr>
		<td>Razón</td>
		<td>
			<select name="id_tipootra" class="input-medium" required>
			<option value="">--ingrese tipo--</option>
			<?php 
			do{	?>
					<option value="<?php  echo $row_tipootra['id_tipootra']?>">
					<?php  echo $row_tipootra['tipootra']?></option>
				<?php 
			}while ($row_tipootra = mysql_fetch_array($tipootra));?>
			</select>
		</td>
	</tr>
	
	<tr>
		<td>Horas ausentadas</td>
		<td><input type="number" class="input-medium" name="horas" value="" placeholder="ingrese horas" required></td>
	</tr>
	
	<tr>
		<td>Comentario</td>
		<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota" placeholder="ingrese comentario"><?php echo $row_otrahora['nota']?></textarea></td>
	</tr>
	
	<tr>
		<td colspan="5">
			<center>
			<input type="hidden" class="input-medium" name="fecha" value="<?php echo $fecha;?>" placeholder="ingrese fecha" required>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			<input type="submit" class="btn btn-default" name="agregar" title="agregar registro" value="nuevo" id="nuevo">
			<input type="submit" class="btn btn-default" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar" disabled>
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
			</center>
		</td>
	</tr>
	</table>
	</fieldset>
	</form>
	</div>
	<?php }?>
	
	

	

	
 
