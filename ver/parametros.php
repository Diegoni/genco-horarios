<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Parametros
----------------------------------------------------------------------
--------------------------------------------------------------------->		

<?php
include_once("head.php");      

	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Actualizar parametros
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	if (isset($_POST['modificar']))
	{
	$query="SELECT * FROM `parametros`";   
	$parametros2=mysql_query($query) or die(mysql_error());
	$row_parametros2 = mysql_fetch_assoc($parametros2);
	$numero_parametros2 = mysql_num_rows($parametros2);
	
	do {
	$id_turno=$_POST['id_turno'.$row_parametros2['id_parametros']];
	$id_tipo=$_POST['id_tipo'.$row_parametros2['id_parametros']];
	$inicio=$_POST['inicio'.$row_parametros2['id_parametros']];
	$final=$_POST['final'.$row_parametros2['id_parametros']];
	$considerar=$_POST['considerar'.$row_parametros2['id_parametros']];
	
	mysql_query("UPDATE `parametros` SET 
						id_turno='$id_turno',
						id_tipo='$id_tipo',
						inicio='$inicio',
						final='$final',
						considerar='$considerar'
						WHERE id_parametros='$row_parametros2[id_parametros]'
						") or die(mysql_error());
	}while ($row_parametros2 = mysql_fetch_array($parametros2));
	
	?>
		<script>
		window.close();
		</script>
		
	<? }
	else{

	//busco tabla de parametros
 	$query="SELECT *
		FROM `parametros`
		INNER JOIN turno ON(parametros.id_turno=turno.id_turno)
		INNER JOIN tipo ON(parametros.id_tipo=tipo.id_tipo)
		ORDER BY id_parametros ";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);?>
	
	<div class="container; celeste">
	<form action="parametros.php" method="post" > 
	<table>
	<tr>
	<td>Turno</td>
	<td>Tipo</td>
	<td>Desde</td>
	<td>Hasta</td>
	<td>Considerar</td>
	</tr>
	<?
	do{ ?>	
	<tr>
	<td><select name="id_turno<?echo $row_parametros['id_parametros']?>" class="input-small">
		<?
		$query="SELECT * FROM `turno`";   
		$turno=mysql_query($query) or die(mysql_error());
		$row_turno = mysql_fetch_assoc($turno);
		do{
		if($row_turno['id_turno']==$row_parametros['id_turno']){
		?>
		<option value="<? echo $row_turno['id_turno']?>" selected><? echo $row_turno['turno']?></option>
		<?} else {?>
		<option value="<? echo $row_turno['id_turno']?>"><? echo $row_turno['turno']?></option>
		<?}}while ($row_turno = mysql_fetch_array($turno))
		?>
		</select>
	</td>
	<td><select name="id_tipo<?echo $row_parametros['id_parametros']?>" class="input-small">
		<?
		$query="SELECT * FROM `tipo`";   
		$tipo=mysql_query($query) or die(mysql_error());
		$row_tipo = mysql_fetch_assoc($tipo);
		do{
		if($row_tipo['id_tipo']==$row_parametros['id_tipo']){
		?>
		<option value="<? echo $row_tipo['id_tipo']?>" selected><? echo $row_tipo['tipo']?></option>
		<?} else {?>
		<option value="<? echo $row_tipo['id_tipo']?>"><? echo $row_tipo['tipo']?></option>
		<?}}while ($row_tipo = mysql_fetch_array($tipo))
		?>
		</select>
	</td>
	<td><input type="time" class="input-medium" name="inicio<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['inicio']?>" required></td>
	<td><input type="time" class="input-medium" name="final<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['final']?>" required></td>
	<td><input type="range" class="input-small" name="considerar<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['considerar']?>" min="1" max="30" id="slider<?echo $row_parametros['id_parametros']?>" onchange="printValue('slider<?echo $row_parametros['id_parametros']?>','rangeValue<?echo $row_parametros['id_parametros']?>')" required>
		<input id="rangeValue<?echo $row_parametros['id_parametros']?>" type="text" class="input-minimini" disabled>min.</td>
	</tr>
	<? 	}while ($row_parametros = mysql_fetch_array($parametros))?>
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?echo $id?>">
			<input type="submit" class="btn" name="modificar" value="modificar"  id="modificar">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">volver</a>
			</center>
	</td>
	</tr>
	</table>
	
	</div>
	</form>
	</div> 
 	
	<?	} //cierra else?>
 
