<?php 
include_once("head.php");      
include_once($models_url."otrahora_model.php");      

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Editar entradas
//----------------------------------------------------------------------
//----------------------------------------------------------------------
	
	$fecha=$_GET['fecha'];
	$id=$_GET['id'];
	$bandera=1;
	
	//Query para traer todas las entradas correspondiente al dia y id de usuario
 	$otrahora=getOtrahora($id, $fecha);
	$row_otrahora = mysql_fetch_assoc($otrahora);
	$numero_otrahora = mysql_num_rows($otrahora);
	
	
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

	
	//cierro ventana
	?>
		<script>
		<!--alert("Las entradas fueron modificadas con éxito");-->
		opener.location.reload();
		window.close();
		</script>
		
	<?php  } 
	
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

	
	//cierro ventana
	?>
		<script>
		<!--alert("La entrada fue ingresada con éxito");-->
		opener.location.reload();
		window.close();
		</script>								
									
	<?php 
	
		}?>
	
	<div class="container; celeste">
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------->		

	<form action="edit_otros.php" method="get" > 
	<fieldset>
	<legend>Otras marcaciones:</legend>
	<table>
	<?php  if($numero_otrahora>0){

	?>	
	<tr>
	<td>Tipo</td>
	<td>
		<select name="id_tipootra" class="input-medium">
		<?php 
		$tipootra=getTipootra();
		$row_tipootra = mysql_fetch_assoc($tipootra);
		$id_tipootra=$row_otrahora['id_tipootra'];
		
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
	<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota" required><?php echo $row_otrahora['nota']?></textarea></td>
	</tr>
	</tr>

	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			
			<?php if($numero_otrahora>0){?>
			<a href='#' class='btn' title='no se puede ingresar uno nuevo' disabled>nuevo</a>
			<input type="submit" class="btn btn-default" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar">
			<?php }else{?>
			<a href='#' class='show_hide btn' title='Nuevo'>nuevo</a>
			<input type="submit" class="btn btn-default" name="modificar" title="no se pueden realizar modificaciones" value="modificar" id="modificar" disabled>
			<?php }?>
			<input type="hidden" name="id_otrahora" value="<?php echo $row_otrahora['id_otrahora']?>">
			<input type="hidden" name="id_nota" value="<?php echo $row_otrahora['id_nota']?>">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
			</center>
	</td>
	</tr>
	</table>
	</fieldset>
	</form>
		<?php  		
	}else{?>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Formulario nueva entrada
----------------------------------------------------------------------
--------------------------------------------------------------------->	
	
	<div class="slidingDiv">
	<form action="edit_otros.php" method="get" > 
	<fieldset>
	<legend>Nueva</legend>
	<table>
	
	<tr>
	<td>Razón</td>
	<td>
		<select name="id_tipootra" class="input-medium" required>
		<option value="">--ingrese tipo--</option>
		<?php 
		$tipootra=getTipootra();
		$row_tipootra = mysql_fetch_assoc($tipootra);
		$id_tipootra=$row_otrahora['id_tipootra'];
		
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
	<input type="hidden" class="input-medium" name="fecha" value="<?php echo $fecha;?>" placeholder="ingrese fecha" required>
	<tr>
	<td>Comentario</td>
	<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota" placeholder="ingrese comentario" required><?php echo $row_otrahora['nota']?></textarea></td>
	</tr>
	</tr>
	
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			<input type="submit" class="btn btn-default" name="agregar" title="agregar registro" value="nuevo" id="nuevo">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
			</center>
	</td>
	</tr>
	</table>
	</fieldset>
	</form>
	</div>
	<?php }?>
	
	</div><!--Cierra el div class="celeste"-->

	

	
 
