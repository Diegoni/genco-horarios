<?php include_once("head.php");      

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Editar entradas
//----------------------------------------------------------------------
//----------------------------------------------------------------------
	
	$fecha=$_GET['fecha'];
	$id=$_GET['id'];
	$bandera=1;
	
	//Query para traer todas las entradas correspondiente al dia y id de usuario
 	$query="SELECT * 
		FROM otrahora 
		INNER JOIN tipootra ON(otrahora.id_tipootra=tipootra.id_tipootra)
		INNER JOIN nota ON(otrahora.id_nota=nota.id_nota)
		WHERE fecha = '$fecha' 
		AND otrahora.id_usuario='$id'";   
	$otrahora=mysql_query($query) or die(mysql_error());
	$row_otrahora = mysql_fetch_assoc($otrahora);
	$numero_otrahora = mysql_num_rows($otrahora);
	
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Edicion de entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['modificar'])){

	mysql_query("UPDATE `otrahora` SET 
						id_tipootra='$_GET[id_tipootra]',
						horas = '$_GET[horas]',
						fecha = '$_GET[fecha]'
						WHERE id_otrahora='$_GET[id_otrahora]'
						") or die(mysql_error());
						
	
	mysql_query("UPDATE `nota` SET 
						nota='$_GET[nota]'
						WHERE id_nota='$_GET[id_nota]'
						") or die(mysql_error());
	
	//cierro ventana
	?>
		<script>
		<!--alert("Las entradas fueron modificadas con éxito");-->
		window.parent.location.reload()
		window.close();
		</script>
		
	<? } 
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Nueva entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['agregar'])){
	mysql_query("INSERT INTO `nota` (nota) 
				VALUES ('$_GET[nota]')") or die(mysql_error());
				$id_nota=mysql_insert_id();
				
	mysql_query("INSERT INTO `otrahora` (id_usuario, id_tipootra, id_nota, horas, fecha) 
				VALUES ('$_GET[id]', '$_GET[id_tipootra]', '$id_nota', '$_GET[horas]', '$_GET[fecha]')") or die(mysql_error());				

	
	//cierro ventana
	?>
		<script>
		<!--alert("La entrada fue ingresada con éxito");-->
		window.close();
		</script>								
									
	<?
	
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
	<? if($numero_otrahora>0){

	?>	
	<tr>
	<td>Tipo</td>
	<td>
		<select name="id_tipootra" class="input-medium">
		<?
		$query="SELECT * FROM tipootra";   
		$tipootra=mysql_query($query) or die(mysql_error());
		$row_tipootra = mysql_fetch_assoc($tipootra);
		$id_tipootra=$row_otrahora['id_tipootra'];
		
		do{	if($id_tipootra==$row_tipootra['id_tipootra']){		
				?>
				<option value="<? echo $row_tipootra['id_tipootra']?>" selected>
				<? echo $row_tipootra['tipootra']?></option>
			<?} else {?>
				<option value="<? echo $row_tipootra['id_tipootra']?>">
				<? echo $row_tipootra['tipootra']?></option>
			<?}
		}while ($row_tipootra = mysql_fetch_array($tipootra));?>
		</select>
	</td>
	</tr>
	<tr>
	<td>Horas</td>
	<td><input type="number" class="input-medium" name="horas" value="<?echo $row_otrahora['horas']?>" required></td>
	</tr>
	<tr>
	<td>Fecha</td>
	<td><input type="date" class="input-medium" name="fecha" value="<?echo $row_otrahora['fecha']?>" required></td>
	</tr>
	<tr>
	<td>Comentario</td>
	<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota" required><?echo $row_otrahora['nota']?></textarea></td>
	</tr>
	</tr>
	<? 		
	}else{
	echo "No hay otras marcaciones";}?>
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?echo $_GET['id']?>">
			
			<?if($numero_otrahora>0){?>
			<a href='#' class='btn' title='no se puede ingresar uno nuevo' disabled>nuevo</a>
			<input type="submit" class="btn" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar">
			<?}else{?>
			<a href='#' class='show_hide btn' title='Nuevo'>nuevo</a>
			<input type="submit" class="btn" name="modificar" title="no se pueden realizar modificaciones" value="modificar" id="modificar" disabled>
			<?}?>
			<input type="hidden" name="id_otrahora" value="<?echo $row_otrahora['id_otrahora']?>">
			<input type="hidden" name="id_nota" value="<?echo $row_otrahora['id_nota']?>">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
			</center>
	</td>
	</tr>
	</table>
	</fieldset>
	</form>
	
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
	<td>Tipo</td>
	<td>
		<select name="id_tipootra" class="input-medium" required>
		<option value="">--ingrese tipo--</option>
		<?
		$query="SELECT * FROM tipootra";   
		$tipootra=mysql_query($query) or die(mysql_error());
		$row_tipootra = mysql_fetch_assoc($tipootra);
		$id_tipootra=$row_otrahora['id_tipootra'];
		
		do{	?>
				<option value="<? echo $row_tipootra['id_tipootra']?>">
				<? echo $row_tipootra['tipootra']?></option>
			<?
		}while ($row_tipootra = mysql_fetch_array($tipootra));?>
		</select>
	</td>
	</tr>
	<tr>
	<td>Horas</td>
	<td><input type="number" class="input-medium" name="horas" value="" placeholder="ingrese horas" required></td>
	</tr>
	<tr>
	<td>Fecha</td>
	<td><input type="date" class="input-medium" name="fecha" value="<?echo $fecha;?>" placeholder="ingrese fecha" required></td>
	</tr>
	<tr>
	<td>Comentario</td>
	<td><textarea type="text" class="input-medium" rows="5" cols="40" name="nota" placeholder="ingrese comentario" required><?echo $row_otrahora['nota']?></textarea></td>
	</tr>
	</tr>
	
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?echo $_GET['id']?>">
			<input type="submit" class="btn" name="agregar" title="agregar registro" value="nuevo" id="nuevo">
			<a href='#' class='show_hide btn btn-danger' title="no guarda los cambios realizados">volver</a>
			</center>
	</td>
	</tr>
	</table>
	</fieldset>
	</form>
	</div>
	
	
	</div><!--Cierra el div class="celeste"-->

	

	
 
