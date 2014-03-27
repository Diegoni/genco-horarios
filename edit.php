<?php include_once("head.php");    

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Marcada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['delete'])){

$delete=$_GET['delete'];

mysql_query("UPDATE `marcada` SET 
						id_estado=0
						WHERE id_marcada='$delete'
						") or die(mysql_error());

}	 

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
		FROM marcada 
		INNER JOIN parametros ON(marcada.id_parametros=parametros.id_parametros)
		WHERE DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha' 
		AND id_usuario='$id' 
		AND id_estado!=0
		ORDER BY marcada.id_parametros";   
	$marcacion=mysql_query($query) or die(mysql_error());
	$row_marcacion = mysql_fetch_assoc($marcacion);
	$numero_marcacion = mysql_num_rows($marcacion);
	
	function control_parametros($id_parametro,$entrada){
	//preguntar si es correcto, verificar si la entrada esta dentro de los parametros permitidos
	$query="SELECT * FROM parametros
			WHERE id_parametros='$id_parametro'";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);

	$control_inicio = strtotime($entrada) - strtotime($row_parametros['inicio'])  ;
	$control_inicio=$control_inicio/60;
		
	$control_final = strtotime($row_parametros['final']) - strtotime($entrada) ;
	$control_final=$control_final/60;
	
	if($control_inicio<0 || $control_final<0){
		$bandera=0;
		return $bandera;
	}else{
		$bandera=1;
		return $bandera;
	
	}
	}

/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Marcada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['delete'])){

$delete=$_GET['delete'];

mysql_query("UPDATE `marcada` SET 
						id_estado=0
						WHERE id_marcada='$delete'
						") or die(mysql_error());

}	
	
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Edicion de entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['modificar'])){
		$bandera=1;
	//recorro el query modificando los parametros
	do {
	$id_parametros=$_GET['id_parametro'.$row_marcacion['id_marcada']];
	$entrada=$_GET['entrada'.$row_marcacion['id_marcada']];
	$registro=date("H:i", strtotime($row_marcacion['entrada']));
	
	//compara para ver si hay modificaciones
	if($entrada!=$registro || $id_parametros!=$row_marcacion['id_parametros']){
	
	//verificar si la entrada esta dentro de los parametros permitidos
	$control_parametros=control_parametros($id_parametros,$entrada);	
	
	$id_marcada=$row_marcacion['id_marcada'];
	$id_estado=$row_marcacion['id_estado'];

	if($control_parametros==0){
	$query="SELECT * FROM parametros
			INNER JOIN
			tipo ON(parametros.id_tipo=tipo.id_tipo)
			INNER JOIN
			turno ON(parametros.id_turno=turno.id_turno)
			WHERE id_parametros='$id_parametros'";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);
	
		echo 	"<div class='container; celeste'>
				Por favor controle los horarios ingresados, no está dentro de los parámetros.<br>
				Marcada:<b>".$entrada."</b><br>
				Parametro:<b>".$row_parametros['tipo']." ".$row_parametros['turno']."</b><br>
				<form action='edit.php' method='get'>
				<input type='hidden' name='id_parametros' value=".$id_parametros.">
				<input type='hidden' name='fecha' value=".$fecha.">
				<input type='hidden' name='entrada' value=".$entrada.">
				<input type='hidden' name='id_marcada' value=".$id_marcada.">
				<input type='hidden' name='id_estado' value=".$id_estado.">
				<button class='btn' value='confirmar' name='confirmar_edit' title='confirmar cambios realizados'>Confirmar</button>
				<a class='btn btn-danger' href='' title='no guarda los cambios realizados' onClick='cerrarse()'>Volver</a>
				</form>
				</div>";
				
		$bandera=0;
	}else{

	if($id_estado==1){
		$id_estado=3;//estado editado de access
	}
	
	mysql_query("UPDATE `marcada` SET 
						id_parametros='$id_parametros',
						entrada = '$fecha $entrada:00',
						id_estado = '$id_estado'
						WHERE id_marcada='$id_marcada'
						") or die(mysql_error());
	}
	}
	}while ($row_marcacion = mysql_fetch_array($marcacion));
	if($bandera==1){
	//cierro ventana
	?>
		<script>
		<!--alert("Las entradas fueron modificadas con éxito");-->
		window.parent.location.reload()
		window.close();
		</script>
		
	<? }} 
	
	if (isset($_GET['confirmar_edit'])){
	
	$id_parametros=$_GET['id_parametros'];
	$fecha=$_GET['fecha'];
	$entrada=$_GET['entrada'];
	$id_marcada=$_GET['id_marcada'];
	$id_estado=$_GET['id_estado'];
	if($id_estado==1){
		$id_estado=3;//estado editado de access
	}


	mysql_query("UPDATE `marcada` SET 
						id_parametros='$id_parametros',
						entrada = '$fecha $entrada:00',
						id_estado = '$id_estado'
						WHERE id_marcada='$id_marcada'
						") or die(mysql_error());
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
	
	if (isset($_GET['nuevo'])){
	$id_parametro=$_GET['id_parametro'];
	$entrada=$_GET['entrada'];
	$control_parametros=control_parametros($id_parametro,$entrada);
	
	//verificar si la entrada esta dentro de los parametros permitidos	
	if($control_parametros==0){
	$query="SELECT * FROM parametros
			INNER JOIN
			tipo ON(parametros.id_tipo=tipo.id_tipo)
			INNER JOIN
			turno ON(parametros.id_turno=turno.id_turno)
			WHERE id_parametros='$id_parametro'";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);
	
		echo 	"<div class='container; celeste'>
				Por favor controle los horarios ingresados, no está dentro de los parámetros.<br>
				Marcada:<b>".$entrada."</b><br>
				Parametro:<b>".$row_parametros['tipo']." ".$row_parametros['turno']."</b><br>
				<form action='edit.php' method='get'>
				<input type='hidden' name='id_parametro' value=".$id_parametro.">
				<input type='hidden' name='fecha' value=".$fecha.">
				<input type='hidden' name='entrada' value=".$entrada.">
				<input type='hidden' name='id' value=".$id.">
				<button value='confirmar' name='confirmar_update' title='confirmar cambios realizados'>Confirmar</button>
				<a class='btn btn-danger' href='' title='no guarda los cambios realizados' onClick='cerrarse()'>Volver</a>
				</form>
				</div>";
				
		$bandera=0;
	}else{
	mysql_query("INSERT INTO `marcada` (id_parametros, entrada, id_usuario, id_estado) 
				VALUES ('$id_parametro', '$fecha $entrada:00', '$id', 2)") or die(mysql_error());
	
	//cierro ventana
	?>
		<script>
		<!--alert("La entrada fue ingresada con éxito");-->
		window.close();
		</script>								
		
								
	<?}
	
	}
	
	if (isset($_GET['confirmar_update'])){
	
	$id_parametro=$_GET['id_parametro'];
	$entrada=$_GET['entrada'];
	$fecha=$_GET['fecha'];
	$id=$_GET['id'];
	
	mysql_query("INSERT INTO `marcada` (id_parametros, entrada, id_usuario, id_estado) 
				VALUES ('$id_parametro', '$fecha $entrada:00', '$id', 2)") or die(mysql_error());
	?>
		<script>
		<!--alert("La entrada fue ingresada con éxito");-->
		window.close();
		</script>								
		
								
	<?}
	
	
	
	?>
	
	<?if ($bandera==1){?>
	<body>
	<div class="container; celeste">
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------->		

	<form action="edit.php" method="get" > 
	<fieldset>
	<legend>Marcaciones:</legend>
	<table>
	<? if($numero_marcacion>0){
	$stack = array();
	do{ 
	$id_marcada=$row_marcacion['id_marcada'];
	?>	
	<tr>
	<td>
		<select name="id_parametro<?echo $id_marcada?>" class="input-medium">
		<?
		$id_parametros=$row_marcacion['id_parametros'];
		$query="SELECT * FROM parametros 		
				INNER JOIN turno ON(parametros.id_turno=turno.id_turno)
				INNER JOIN tipo ON(parametros.id_tipo=tipo.id_tipo)";   
		$parametros=mysql_query($query) or die(mysql_error());
		$row_parametros = mysql_fetch_assoc($parametros);
		do{
			if($id_parametros==$row_parametros['id_parametros']){		
					$comparacion==0;
					foreach ($stack as $valor) {
					if($valor==$row_parametros['id_parametros']){
							$comparacion=1;
					}
					}
					array_push($stack, $row_parametros['id_parametros']);
				?>
				
				<option value="<? echo $row_parametros['id_parametros']?>" selected>
				<? echo $row_parametros['turno']?> : <? echo $row_parametros['tipo']?></option>
			<?} else {?>
				<option value="<? echo $row_parametros['id_parametros']?>">
				<? echo $row_parametros['turno']?> : <? echo $row_parametros['tipo']?></option>
			<?}
		}while ($row_parametros = mysql_fetch_array($parametros));?>
		
		</select>
	</td>
	
	<td><input type="time" class="input-medium" name="entrada<?echo $row_marcacion['id_marcada']?>" value="<?echo date("H:i", strtotime($row_marcacion['entrada']))?>" required></td>
	<td><a href="edit.php?delete=<?echo $row_marcacion['id_marcada']?>&fecha=<?= $fecha?>&id=<?= $id?>" onclick="return confirm('Esta seguro que quiere borrar');" class="btn btn-danger" name="delete">X</td>
	</tr>
	<? 	}while ($row_marcacion = mysql_fetch_array($marcacion));
	if($comparacion==1){?>
				
				<script>
				alert("Hay horarios repetidos");
				</script>
	<?} 
	}else{
	echo "No hay elementos para editar";}?>
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?echo $_GET['id']?>">
			<input type="hidden" name="fecha" value="<?echo $_GET['fecha']?>">
			<a href='#' class='show_hide btn' title='Nuevo'>nuevo</a>
			<?if($numero_marcacion>0){?>
			<input type="submit" class="btn" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar">
			<?}else{?>
			<input type="submit" class="btn" name="modificar" title="no se pueden realizar modificaciones" value="modificar" id="modificar" disabled>
			<?}?>
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
		<form action="edit.php" method="get" > 
		<fieldset>
		<legend>Nueva marcación:</legend>
		<table>
		<tr>
		<td>
			<select name="id_parametro" class="input-medium">
			<?
			$query="SELECT * FROM parametros 		
					INNER JOIN turno ON(parametros.id_turno=turno.id_turno)
					INNER JOIN tipo ON(parametros.id_tipo=tipo.id_tipo)";   
			$parametros2=mysql_query($query) or die(mysql_error());
			$row_parametros2 = mysql_fetch_assoc($parametros2);
			do{
			$comparacion=0;
				foreach ($stack as $valor) {
					if($valor==$row_parametros2['id_parametros']){
							$comparacion=1;
					}
					}
				if($comparacion==0){?>
				<option value="<? echo $row_parametros2['id_parametros']?>">
					<? echo $row_parametros2['turno']?> : <? echo $row_parametros2['tipo']?>
				</option>
			<?	}				
			}while ($row_parametros2 = mysql_fetch_array($parametros2))	?>
			</select>
		</td>
		<td><input type="time" class="input-medium" name="entrada" value="" required></td>
		</tr>	
		<tr>
		<td colspan="5">
			<center>
				<input type="hidden" name="id" value="<?echo $_GET['id']?>">
				<input type="hidden" name="fecha" value="<?echo $_GET['fecha']?>">
				<input type="submit" class="btn" name="nuevo" value="aceptar"  id="nuevo">
				<a href='#' class='show_hide btn btn-danger' title='cancelar'>cancelar</a>
			</center>
		</td>
		</tr>
		</table>
		</fieldset>
		</form>
	</div>
	
	
	</div><!--Cierra el div class="celeste"-->
	</body>
	<?}?>
	

	
 
