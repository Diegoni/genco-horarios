<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 
include_once($models_url."marcadas_model.php"); 
include_once($models_url."parametros_model.php"); 
include_once($models_url."turnos_model.php"); 
include_once($models_url."tipos_model.php"); 
include_once($models_url."updates_model.php"); 
include_once($models_url."usuarios_model.php"); 
include_once($models_url."departamentos_model.php"); 
include_once($models_url."otrahora_model.php"); 
include_once($models_url."logs_model.php"); 

//Funcion para saber si se debe actualizar la pagina
function actualizar ($fecha_americana,$fecha_access2){
//conexion odbc		
		$dsn = "NWIND"; 
		$usuario = "";
		$clave="";
		$ODBC=odbc_connect($dsn, $usuario, $clave);
		if (!$ODBC){
		exit("<strong>Ya ocurrido un error tratando de conectarse con el origen de datos.</strong>");}
		
		
// consulta la cantidad de registros para ese dia en la base de access
		$sql="SELECT count(*) as total, USERID 
		FROM CHECKINOUT 
		WHERE (((CHECKINOUT.CHECKTIME)>#$fecha_americana# AND (CHECKINOUT.CHECKTIME)<#$fecha_access2#))
		GROUP BY  CHECKINOUT.USERID
		ORDER BY USERID;"; 
		$contador=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));

$bandera=0;


		while (odbc_fetch_row($contador)){  
			$id=odbc_result($contador,"USERID");
			$cantidad_odbc=odbc_result($contador,"total");        

			$registro=getMarcacion($id, $fecha_americana);
			$row_registro = mysql_fetch_assoc($registro);
			$cantidad_mysql = mysql_num_rows($registro);
			 

			// comparamos la cantidad de registros
			if($cantidad_odbc<=$cantidad_mysql){
			}else{
			$bandera=1;
			}
		}
return $bandera;
}


//fecha con la que se trabaja, la actual o la seteada
if(isset($_GET['fecha'])){
	$fecha=$_GET['fecha'];
	$fecha_americana=date( "Y-m-d", strtotime($_GET['fecha']));
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}else{
	$fecha= date("d-m-Y");
	$fecha_americana=date("Y-m-d");
	$fecha_access2 = date('Y/m/d', strtotime("$fecha_americana + 1 day"));
}

//consulto si debo actualizar la pagina
//Descomentar esta linea para que tome el ODBC y la actualizacion
//$bandera=actualizar($fecha_americana,$fecha_access2);


//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Actualizo registro
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['actualizar'])&& $bandera==1){
$bandera=1;

$update=getUpdates();
$row_update = mysql_fetch_assoc($update);

$fecha_americana=date( "Y-m-d H:m:s", strtotime($row_update['ultima_fecha']));


$sql="SELECT *			
		FROM CHECKINOUT 
		WHERE (CHECKINOUT.CHECKTIME)>#$fecha_americana# ORDER BY CHECKTIME";
$checkinout=odbc_exec($ODBC,$sql)or die(exit("Error en odbc_exec"));


$i=0;



do{
	$i=$i+1;

	$USERID=odbc_result($checkinout,"USERID");
	if($USERID!=0){
		$CHECKTIME=odbc_result($checkinout,"CHECKTIME");
		$CHECKTYPE=odbc_result($checkinout,"CHECKTYPE");

		$hora=date('H:i', strtotime("$CHECKTIME"));

		//CONTROLO QUE TIPO ES I=IN,ENTRADA Y O=OUT,SALIDA
		if($CHECKTYPE=="I"){
			$tipo=1;
		}else{
			$tipo=2;
		}
		//BUSCO DENTRO DE PARAMETROS SI ES MAÑANA TARDE O NOCHE DEPENDIENDO DE LA HORA
		$parametros=getParametros($hora, $tipo);
		$row_parametros = mysql_fetch_assoc($parametros);
		$cantidad=mysql_num_rows($parametros);

		//SI NO COINCIDE CON NINGUNO VA 0
		if($cantidad<0){
			$id_parametros=0;
		}else{
			$id_parametros=$row_parametros['id_parametros'];
		}

		//INGRESO EL REGISTRO
		insertMarcadaAccess($CHECKTIME, $USERID, $CHECKTYPE, $id_parametros);			
	}else{
		echo "No hay nuevos registros";
		$bandera=0;
	}								
					
}while (odbc_fetch_row($checkinout));

	if($bandera==1){
		//GUARDO REGISTRO DE LA ULTIMA FECHA
		$ultima_fecha=date( "Y-m-d H:m:s", strtotime($CHECKTIME));
		$fecha_hoy=date("Y-m-d H:m:s");

		insertUpdate($ultima_fecha, $USERID, $fecha_hoy, $i);
						
		echo "Los datos se han cargado correctamente";
	}
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Filtros para busqueda en la tabla
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['empleado'])){
	$usuario=getUsuarios($_GET['empredo'],'usuario');
	$row_usuario = mysql_fetch_assoc($usuario);
}else{
	$usuario=getUsuarios();
	$row_usuario = mysql_fetch_assoc($usuario);
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Modificar parametros
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
	if (isset($_POST['parametros']))
	{
	$parametros2=getParametros();
	$row_parametros2 = mysql_fetch_assoc($parametros2);
	$numero_parametros2 = mysql_num_rows($parametros2);
	
	do {
		$id_turno=$_POST['id_turno'.$row_parametros2['id_parametros']];
		$id_tipo=$_POST['id_tipo'.$row_parametros2['id_parametros']];
		$inicio=$_POST['inicio'.$row_parametros2['id_parametros']];
		$final=$_POST['final'.$row_parametros2['id_parametros']];
		$considerar=$_POST['considerar'.$row_parametros2['id_parametros']];
		
	updatePrametro($id_turno,	$id_tipo,	$inicio, $final, $considerar,	$row_parametros2['id_parametros']);
	}while ($row_parametros2 = mysql_fetch_array($parametros2));
	}


?>
<div class="row">
<div class="span12">
<center>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Parametros
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<div id="openModal" class="modalDialog">
	<div>
		<a href="#closes" title="Cerrar" class="closes">X</a>
		<h2>Parámetros de configuración</h2>
		<p>Estos son los valores que filtran las entradas y salidas</p>
		<p>
	<?
	$parametros=getParametros();
	$row_parametros = mysql_fetch_assoc($parametros);
	?>
	
	<div class="container; celeste">
	<form action="index.php" method="post" > 
	<table class="sortable">
	<thead>
	<tr>
	<td>Turno</td>
	<td>Tipo</td>
	<td>Desde</td>
	<td>Hasta</td>
	<td>Considerar</td>
	</tr>
	</thead>
	<?
	do{ ?>	
	<tr>
	<td><?
		$turno=getTurnos();
		$row_turno = mysql_fetch_assoc($turno);
		do{
		if($row_turno['id_turno']==$row_parametros['id_turno']){
		?>
		<input type="hidden" value="<? echo $row_turno['id_turno']?>" name="id_turno<?echo $row_parametros['id_parametros']?>">
		<? echo $row_turno['turno']?>
		<?} 
		}while ($row_turno = mysql_fetch_array($turno))
		?>
	</td>
	<td><?
		$tipo=getTipos();
		$row_tipo = mysql_fetch_assoc($tipo);
		do{
		if($row_tipo['id_tipo']==$row_parametros['id_tipo']){
		?>
		<input type="hidden" value="<? echo $row_tipo['id_tipo']?>" name="id_tipo<?echo $row_parametros['id_parametros']?>">
		<? echo $row_tipo['tipo']?>
		<?}
		}while ($row_tipo = mysql_fetch_array($tipo))
		?>
	</td>
	<td><input type="time" class="input-inter" name="inicio<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['inicio']?>" required></td>
	<td><input type="time" class="input-inter" name="final<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['final']?>" required></td>
	<td><input type="range" class="input-small" name="considerar<?echo $row_parametros['id_parametros']?>" value="<?echo $row_parametros['considerar']?>" min="1" max="30" id="slider<?echo $row_parametros['id_parametros']?>" onchange="printValue('slider<?echo $row_parametros['id_parametros']?>','rangeValue<?echo $row_parametros['id_parametros']?>')" required>
		<input id="rangeValue<?echo $row_parametros['id_parametros']?>" type="text" class="input-minimini" disabled>min.</td>
	</tr>
	<? 	}while ($row_parametros = mysql_fetch_array($parametros))?>
	<tr>
	<td colspan="5">
			<center>
			<input type="hidden" name="id" value="<?echo $id?>">
			<input type="submit" class="btn" name="parametros" value="Modificar"  id="parametros">
			<a class="btn btn-danger" href="" title="no guarda los cambios realizados" onClick="cerrarse()">Cancelar</a>
			</center>
	</td>
	</tr>
	</table>
	
	</div>
	</form>
	</div> 
		
		</p>
	</div>
</div>




<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Cabecera
----------------------------------------------------------------------			
--------------------------------------------------------------------->

<?if($bandera==1){ ?>
Por favor actualice la base de datos
<? }?>



	<table class="table table-striped">
	<tr class="success">
	<td>
		<b>Marcaciones del día</b>
	</td>
	<td>
		<p class="fecha" title="Fecha con la que se esta trabajando"><? echo  $fecha;?></p>
	</td>
	<td>
		<form class="form-inline" action="index.php" name="ente">
		<p></p>
		<b><div class="input-prepend">
			<span class="add-on" onclick="document.getElementById('datepicker').focus();"><i class="icon-calendar"></i></span>
			<input type="text" name="fecha" id="datepicker" placeholder="ingrese fecha"  autocomplete="off" required>
		</div></b>
		<button type="submit" class="btn" title="Buscar"><i class="icon-search"></i></button>
		</form>
		
	</td>
	<td>

	<a href="javascript:imprSelec('muestra')" class='btn'><i class="icon-print"></i> Imprimir</a>
	<button class="btn" onclick="tableToExcel('example', 'W3C Example Table')"><i class="icon-download-alt"></i> Excel</button>

	
	<div class="btn-group">
	  <a class="btn btn-success dropdown-toggle" data-toggle="dropdown" href="#">
		<i class="icon-cogs"></i>
		<span class="caret"></span>
	  </a>
	  <ul class="dropdown-menu">
		<li><a href="#openModal" title="Parametros"><i class="icon-time"></i> Parametros</a></li>
		<li><a href="index.php?fecha=<? echo $fecha;?>" title="Refresh" ><i class="icon-refresh"></i> Refresh</a></li>
		<?if($bandera==1){ ?>
			<form class="form-inline" action="index.php" name="importar">
			<input type="hidden" name="fecha" value="<? echo $fecha;?>">
			<li><button type="submit" title="Actualice la base de datos" name="actualizar" value="1"><i class="icon-download-alt"></i> Actualizar</button></li>
			<form class="form-inline" action="index.php" name="importar">
		<?}else{?>
			<li class="disabled"><a href="" title="Los datos ya estan actalizados" name="actualizar" value="1"><i class="icon-download-alt"></i> Actualizar</a></li>
		<?}?>
		<!--<li><a href='#' class='show_hide' title='Más detalles en la búsqueda'><i class="icon-chevron-sign-down"></i> Búsqueda</a></li>-->
		<li><a href="#myModal" role="button" data-toggle="modal"><i class="icon-question-sign"></i> Ayuda</a></li>
		</form>
	  </ul>
	</div>

		
		
	</td>
	</tr>
	</table>

	
	
	<!-- Ayuda -->
	<div id="myModal" class="modal hide fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-header">
	<button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
	<h3 id="myModalLabel"><i class="icon-question-sign"></i> Ayuda</h3>
	</div>
	<div class="modal-body">
	<p>Esta tabla muestra todas las marcaciones que se hicieron para una fecha determinada.</p>
	<p>Las marcaciones que aparecen en rojo tienen algún tipo de conflicto.</p>
	<p>Las marcaciones que aparecen en verde están modificadas y las que aparecen en amarillo están dada de alta por el sistema.</p>
	<p>Las marcaciones se pueden editar desde la columna “Editar”.</p>
	<p>Se pueden agregar otro tipo de horas desde “Otros”.</p>
	<p>Para ver las de un usuario determinado, solo debe seleccionar al usuario.</p>
	</div>
	<div class="modal-footer">
	<button class="btn" data-dismiss="modal" aria-hidden="true">Aceptar</button>
	</div>
	</div>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Formulario busqueda
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<?
	$usuario_lista=getUsuarios();
	$row_usuario_lista = mysql_fetch_assoc($usuario_lista);
?>
<datalist id="usuario">
<? do{ ?>
  <option value="<?= $row_usuario_lista['usuario'];?>">
<? }while($row_usuario_lista=mysql_fetch_array($usuario_lista));?>
</datalist>

<?
	$departamento_lista=getDepartamentos();
	$row_departamento_lista = mysql_fetch_assoc($departamento_lista);
?>
<datalist id="departamento">
<? do{ ?>
  <option value="<?= $row_departamento_lista['nombre'];?>">
<? }while($row_departamento_lista=mysql_fetch_array($departamento_lista));?>
</datalist>

<div class="slidingDiv">
<div class="alert alert-info">
<a href='#'  class="close show_hide">&times;</a>
	<form class="form-inline" action="index.php" name="ente">
	<tr>
	<td>
		<div class="input-prepend">
		<span class="add-on" onclick="document.getElementById('legajo').focus();"><i class="icon-folder-close-alt"></i></span>
		<input type="text" class="span1" name="legajo" placeholder="legajo" id="legajo" autofocus>
		</div>
	</td>
	<td>
		<div class="input-prepend">
		<span class="add-on" onclick="document.getElementById('usuario2').focus();"><i class="icon-user"></i></span>
		<input type="text" list="usuario" class="span2" name="usuario" placeholder="nombre" autocomplete="off" id="usuario2">
		</div>
	</td>
	<td>
		<div class="input-prepend">
		<span class="add-on" onclick="document.getElementById('departamento2').focus();"><i class="icon-group"></i></span>
		<input type="text" list="departamento" class="span2" name="departamento" placeholder="departamento" autocomplete="off" id="departamento2">
		</div>
	</td>
	<input type="hidden" name="fecha" value="<? echo $fecha;?>">
	<td colspan="8"><button type="submit" class="btn" title="buscar" name="empleado" value="1">Aceptar</button></td>
	</tr>
	</form>
</div>
</div>
<BR>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->           


<!--<table  id="table" class="sortable">-->
<div id="muestra">
<table border="1" class="table table-hover" id="example">
<thead>
	<th title="Legajo de los usuarios">Legajo</th>
	<th title="Nombre de los usuarios">Nombre</th>
	<th title="Departamento al que pertenecen">Sector</th>
	<th title="sin definir">sd</th>
	<th title="Mañana - Entrada">m-e</th>
	<th title="Mañana - Salida">m-s</th>
	<th title="Tarde - Entrada">t-e</th>
	<th title="Tarde - Salida">t-s</th>
	<th title="Otro tipo">Otros</th>
	<th title="Editar las entradas">Editar</th>
</thead>

<tbody>
<?
# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

		$marcacion=getMarcaciones(NULL, $fecha_americana);
		$row_marcacion = mysql_fetch_assoc($marcacion);
		
do{
	$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
	$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));

# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

		$otrahora=getOtrahoras($fecha_americana);
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
	$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
	$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			


do{?>
	<tr>
	<td><? echo $row_usuario['legajo']?></td>
	<td><a href="usuario.php?id=<?= $row_usuario['id_usuario']?>&fecha=<?= $fecha;?>&buscar=2" class="ayuda-boton btn"><? echo $row_usuario['usuario']?></a></td>
	<td><? echo $row_usuario['departamento']?></td>
		<? 
		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				id_usuario='$row_usuario[id_usuario]'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);

			if($cantidad_parametros==0){?>
				<td><p class="insert_access"> - </p></td>
			<?}else if($cantidad_parametros>1){?>
				<td><p class="label label-important" title="Registro duplicado, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
			<?}else{
				if($row_marcacion['id_estado']==3){
				
				$log_auditoria_marcada=getLog($row_marcacion['id_marcada']);
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
				?>
				<td><p class="label label-success" title="Registro modificado, original :<? echo date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));?>"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_estado']==2){?>
				<td><p class="label" title="Registro dado de alta por sistema"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_parametros']==0){?>
				<td><p class="label label-important" title="Registro sin definir, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else{?>
				<td><p class="insert_access"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}?>
			<?}//cierra el else?>
		<?}//cierra el for?>
		<?
		$query="SELECT * 
				FROM tempotra 
				INNER JOIN tipootra ON(tempotra.id_tipootra=tipootra.id_tipootra)
				INNER JOIN nota ON(tempotra.id_nota=nota.id_nota)
				WHERE
				id_usuario='$row_usuario[id_usuario]'";   
			$otrahora=mysql_query($query) or die(mysql_error());
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
			if($cantidad>0){
		?>
		<td><p class="insert_access"><a href="#" class="btn" title="<? echo $row_otrahora['nota'];?>" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $fecha_americana?>')"><? echo $row_otrahora['tipootra'];?> : <? echo $row_otrahora['horas'];?></a></p></td>
		<?}else{?>
		<td><p class="insert_access"><a href="#" class="btn" title="Agregar" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $fecha_americana?>')"><i class="icon-plus-sign-alt"></i></a></p></td>
		<?}?>
	<td><a href="#" class="btn" title="Parametros" onClick="abrirVentana('edit.php?id=<?echo $row_usuario['id_usuario']?>&fecha=<?echo $fecha_americana?>')"><i class="icon-edit-sign"></i></a></td>
	</tr>
<? }while ($row_usuario = mysql_fetch_array($usuario));

//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());

$query_drop = "DROP TABLE tempotra";
$res_drop = mysql_query($query_drop) or die(mysql_error());

?>
</tbody>
</table>
</div>

 
<? include_once("footer.php");?>

</center>
</div><!--cierra el class="span12" -->
</div><!--cierra el row -->


</div><!--cierra el class="container"-->

</body>


