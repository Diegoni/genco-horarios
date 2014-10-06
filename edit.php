<?php  
include_once("head.php");    
include_once($url['models_url']."marcadas_model.php");     
include_once($url['models_url']."parametros_model.php");   
$comparacion=0; 
/*--------------------------------------------------------------------
----------------------------------------------------------------------
					Borrar Marcada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
if (isset($_GET['delete'])){
	deleteMarcada($_GET['delete']);
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
					Edicion de entrada
----------------------------------------------------------------------
--------------------------------------------------------------------*/	
	
	if (isset($_GET['modificar'])){
		$bandera=1;
	//recorro el query modificando los parametros
	do{
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
	$parametros=getParametro($id_parametros);
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
		updateMarcada($id_parametros, $fecha, $entrada, $id_estado, $id_marcada);

	}
	}
	}while ($row_marcacion = mysql_fetch_array($marcacion));
	if($bandera==1){
	//cierro ventana
	?>
		<script>
		<!--alert("Las entradas fueron modificadas con éxito");-->
		opener.location.reload();
		window.close();
		</script>
		
	<?php  }} 
	
	if (isset($_GET['confirmar_edit'])){
		
	$id_estado=$_GET['id_estado'];
	if($id_estado==1){
		$id_estado=3;//estado editado de access
	}
		updateMarcada($_GET['id_parametros'], $_GET['fecha'], $_GET['entrada'], $id_estado, $_GET['id_marcada']);
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
	
	if (isset($_GET['nuevo'])){
	$bandera=1;
	$parametros = array();
	$id_max=getParametroMax();
	
	for ($i = 1; $i <= $id_max; $i++) {
		if($_GET['entrada'.$i]!=""){
				$id_parametros=$_GET['id_parametro'.$i];
				$entrada=$_GET['entrada'.$i];
				$control_parametros=control_parametros($id_parametros,$entrada);
					
				
				//verificar si la entrada esta dentro de los parametros permitidos	
				if($control_parametros==0){
					array_push($parametros, $id_parametros,$entrada);					
					$bandera=0;
				}else{
					insertMarcada($id_parametros, $fecha, $entrada, $id);
				}
			
			}
			
	}
	if($bandera==0){
	$j=1;
	echo 	"<div class='container; celeste'>
				Por favor controle los horarios ingresados, no está dentro de los parámetros.<br>
				<form action='edit.php' method='get'>";
				
	foreach($parametros as $entrada){
		if($j%2!=0){	
			$id_parametro=$entrada;
			
			$parametros=getParametro($id_parametro);   
			$parametros=mysql_query($query) or die(mysql_error());
			$row_parametros = mysql_fetch_assoc($parametros);
			
			echo"Parametro:<b>".$row_parametros['tipo']." ".$row_parametros['turno']."</b><br>
					<input type='hidden' name='id_parametro".$id_parametro."' value=".$id_parametro.">";
		}else{									
			echo"Marcada:<b>".$entrada."</b><br>							
					<input type='hidden' name='entrada".$id_parametro."' value=".$entrada."<br>";
		}
		$j=$j+1;
	}
		echo"	<input type='hidden' name='fecha' value=".$fecha.">
					<input type='hidden' name='id' value=".$id.">
					<button value='confirmar' name='confirmar_update' title='confirmar cambios realizados'>Confirmar</button>
					<a class='btn btn-danger' href='' title='no guarda los cambios realizados' onClick='cerrarse()'>Volver</a>
					</form>
					</div>";
	
	}else{
	?>
				<script>
				<!--alert("La entrada fue ingresada con éxito");-->
				opener.location.reload();
				window.close();
				</script>																
	<?php }
	}//cierra ifsset nuevo
			
	if (isset($_GET['confirmar_update'])){
			
	$bandera=1;
	
	$id_max = getParametroMax();
	
	for ($i = 1; $i <= $id_max; $i++) {
		if($_GET['entrada'.$i]!=""){
				$id_parametros=$_GET['id_parametro'.$i];
				$entrada=$_GET['entrada'.$i];

				insertMarcada($id_parametros, $fecha, $entrada, $id);
				}
			
			}
			
	?>
				<script>
				<!--alert("La entrada fue ingresada con éxito");-->
				opener.location.reload();
				window.close();
				</script>																
	<?php  } ?>


	<?php if ($bandera==1){?>
	<body>
	<div class="container; celeste">
	
<!--------------------------------------------------------------------
----------------------------------------------------------------------
					Editar entradas existentes
----------------------------------------------------------------------
--------------------------------------------------------------------->		

	<form action="edit.php" method="get" class="form-inline" role="form">
	<fieldset>
	<legend>Marcaciones:</legend>
	<table class="table">
	<?php  if($numero_marcacion>0){
	$stack = array();
	do{ 
	$id_marcada=$row_marcacion['id_marcada'];
	?>	
	<tr>
	<td>
		<select name="id_parametro<?php echo $id_marcada?>" class="form-control" style="width: 200px;">
		<?php 
		$id_parametros=$row_marcacion['id_parametros'];
		$parametros=getParametros();
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
				
				<option value="<?php  echo $row_parametros['id_parametros']?>" selected>
				<?php  echo $row_parametros['turno']?> : <?php  echo $row_parametros['tipo']?></option>
			<?php } else {?>
				<option value="<?php  echo $row_parametros['id_parametros']?>">
				<?php  echo $row_parametros['turno']?> : <?php  echo $row_parametros['tipo']?></option>
			<?php }
		}while ($row_parametros = mysql_fetch_array($parametros));?>
		
		</select>
	</td>
	
	<td>
		<div class="input-prepend">
			
			<div class="input-group" onclick="document.getElementById('entrada<?php echo $row_marcacion['id_marcada']?>').focus();">
      		<div class="input-group-addon">
				<span class="add-on" ><i class="icon-time"></i></span>
			</div>
			<input type="text" id="entrada<?php echo $row_marcacion['id_marcada']?>" class="form-control" name="entrada<?php echo $row_marcacion['id_marcada']?>"  placeholder="ingrese entrada" onkeypress="return false" value="<?php echo date("H:i", strtotime($row_marcacion['entrada']))?>" required/>
		</div>
					
		<script type="text/javascript">
			$('#entrada<?php echo $row_marcacion['id_marcada']?>').timepicker({
  				 showAnim: 'blind'
			});
		</script>
	</td>
	<td><a href="edit.php?delete=<?php echo $row_marcacion['id_marcada']?>&fecha=<?php echo $fecha?>&id=<?php echo $id?>" onclick="return confirm('Esta seguro que quiere borrar');" class="btn btn-danger" name="delete">X</td>
	</tr>
	<?php  	}while ($row_marcacion = mysql_fetch_array($marcacion));
	if($comparacion==1){?>
				<script>
				alert("Hay horarios repetidos");
				</script>
	<?php } 
	}else{
	echo "No hay elementos para editar";}?>
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
			<input type="hidden" name="fecha" value="<?php echo $_GET['fecha']?>">
			<a href='#' class='show_hide btn btn-default' title='Nuevo'>nuevo</a>
			<?php if($numero_marcacion>0){?>
			<input type="submit" class="btn btn-default" name="modificar" title="guardar las modificaciones realizadas" value="modificar" id="modificar">
			<?php }else{?>
			<input type="submit" class="btn btn-default" name="modificar" title="no se pueden realizar modificaciones" value="modificar" id="modificar" disabled>
			<?php }?>
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
		
			<?php 
			$parametros2=getParametros();
			$row_parametros2 = mysql_fetch_assoc($parametros2);
			
			$k=0;
			do{
			$comparacion=0;
				if(empty($stack)){
				}else{
				foreach($stack as $valor){
					if($valor==$row_parametros2['id_parametros']){
							$comparacion=1;
					}
					}
				}
				if($comparacion==0 && $row_parametros2['id_parametros']!=0){?>
				<tr>				
				<input  type="hidden" value="<?php echo $row_parametros2['id_parametros']?>" name="id_parametro<?php echo $row_parametros2['id_parametros']?>">
				<td><label><?php echo $row_parametros2['turno']?> : <?php  echo $row_parametros2['tipo']?></label></td>
				<td><input type="time" class="form-control" name="entrada<?php echo $row_parametros2['id_parametros']?>" id="nueva<?php echo $row_parametros2['id_parametros']?>" value=""></td>
				<script type="text/javascript">
			$('#nueva<?php echo $row_parametros2['id_parametros']?>').timepicker({
  				 showAnim: 'blind'
			});
		</script>	
				</tr>
			<?php 
			$k=$k+1;
			}				
			}while ($row_parametros2 = mysql_fetch_array($parametros2));
			if($k>0){ ?>
		<td colspan="5">
			<center>
				<input type="hidden" name="id" value="<?php echo $_GET['id']?>">
				<input type="hidden" name="fecha" value="<?php echo $_GET['fecha']?>">
				<input type="submit" class="btn btn-default" name="nuevo" value="aceptar"  id="nuevo">
				<a href='#' class='show_hide btn btn-danger' title='cancelar'>cancelar</a>
			</center>
		</td>
			
			<?php  }else{	?>
		<td colspan="5">
			<center>
				<input type="submit" class="btn btn-default" name="nuevo" title="todas las marcaciones ya están dadas de alta" value="aceptar"  id="nuevo" disabled>
				<a href='#' class='show_hide btn btn-danger' title='cancelar'>cancelar</a>
			</center>
		</td>
		<?php }?>
		
		</tr>
		</table>
		</fieldset>
		</form>
	</div>
	
	
	</div><!--Cierra el div class="celeste"-->
	</body>
	<?php }?>