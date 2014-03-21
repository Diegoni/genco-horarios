<?php 
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
include_once("menu.php"); 

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

			$query="SELECT * 
					FROM marcada 
					WHERE DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha_americana' AND id_usuario='$id'";   
			$registro=mysql_query($query) or die(mysql_error());
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

$query="SELECT * FROM `update` 
		ORDER BY id_update DESC";   
$update=mysql_query($query) or die(mysql_error());
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
$query="SELECT * FROM `parametros` 
		WHERE DATE_FORMAT(inicio, '%H:%m')<'$hora' 
		AND DATE_FORMAT(final, '%H:%m')>'$hora'
		AND id_tipo='$tipo'";   
$parametros=mysql_query($query) or die(mysql_error());
$row_parametros = mysql_fetch_assoc($parametros);
$cantidad=mysql_num_rows($parametros);

//SI NO COINCIDE CON NINGUNO VA 0
if($cantidad<0){
	$id_parametros=0;
}else{
	$id_parametros=$row_parametros['id_parametros'];
}

//INGRESO EL REGISTRO
mysql_query("INSERT INTO marcada 
					(entrada, id_usuario,id_parametros_access, id_parametros,id_estado) 
					VALUES 
					('$CHECKTIME','$USERID','$CHECKTYPE','$id_parametros',1)") 
					or die(mysql_error());
					
}else{
echo "No hay nuevos registros";
$bandera=0;
}					
					
					
}while (odbc_fetch_row($checkinout));

if($bandera==1){

//GUARDO REGISTRO DE LA ULTIMA FECHA
$ultima_fecha=date( "Y-m-d H:m:s", strtotime($CHECKTIME));
$fecha_hoy=date("Y-m-d H:m:s");

mysql_query("INSERT INTO  `update` (
				`ultima_fecha` ,
				`ultimo_id` ,
				`fecha` ,
				`registros`
				)
				VALUES (
				'$ultima_fecha',  
				'$USERID',  
				'$fecha_hoy',  
				'$i'
				);") 
					or die(mysql_error());
			    //('$CHECKTIME','$USERID','$fecha_hoy','$i')") 
					
echo "Los datos se han cargado correctamente";
}
}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Filtros para busqueda en la tabla
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['empleado'])){
$query="SELECT 	usuario.id_usuario as id,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				usuario.id_estado as id_estado,
				departamento.nombre as departamento
		FROM `usuario` INNER JOIN departamento
		ON (usuario.id_departamento=departamento.id_departamento)
		WHERE 
		usuario.usuario like '%$_GET[usuario]%' AND
		departamento.nombre like '%$_GET[departamento]%' AND
		usuario.legajo like '%$_GET[legajo]%' AND
		usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

}else{
$query="SELECT 	usuario.id_usuario as id,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				usuario.id_estado as id_estado,
				departamento.nombre as departamento
		FROM `usuario` INNER JOIN departamento
		ON (usuario.id_departamento=departamento.id_departamento)
		WHERE 
		usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

}

//----------------------------------------------------------------------
//----------------------------------------------------------------------
//					Modificar parametros
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
	if (isset($_POST['parametros']))
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
		<h2>Parametros de configuración</h2>
		<p>Estos son los valores que se paramétrizan las entradas y salidas</p>
		<p>
		<?$query="SELECT *
		FROM `parametros`
		INNER JOIN turno ON(parametros.id_turno=turno.id_turno)
		INNER JOIN tipo ON(parametros.id_tipo=tipo.id_tipo)
		ORDER BY id_parametros ";   
	$parametros=mysql_query($query) or die(mysql_error());
	$row_parametros = mysql_fetch_assoc($parametros);?>
	
	<div class="container; celeste">
	<form action="index.php" method="post" > 
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
	<td><?
		$query="SELECT * FROM `turno`";   
		$turno=mysql_query($query) or die(mysql_error());
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
		$query="SELECT * FROM `tipo`";   
		$tipo=mysql_query($query) or die(mysql_error());
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
<p class="blink">	
<?if($bandera==1){ ?>
Por favor actualice la base de datos
<? }?>
</p>


	<table class="table table-striped table-hover">
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
		<li class="divider"></li>
		<li><a href="genco-usuarios/index.php" title="Usuarios"><i class="icon-folder-open"></i> Usuarios</a>
		<li><a href="usuario.php" title="Totales"><i class="icon-dashboard"></i></i> Totales</a>
		</form>
	  </ul>
	</div>
		
		
	</td>
	</tr>
	</table>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Formulario busqueda
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<?
	$query="SELECT 	*
			FROM `usuario` 
			WHERE
			usuario.id_estado=1
			ORDER BY usuario.usuario";   
	$usuario_lista=mysql_query($query) or die(mysql_error());
	$row_usuario_lista = mysql_fetch_assoc($usuario_lista);
?>
<datalist id="usuario">
<? do{ ?>
  <option value="<?= $row_usuario_lista['usuario'];?>">
<? }while($row_usuario_lista=mysql_fetch_array($usuario_lista));?>
</datalist>

<?
	$query="SELECT 	*
			FROM `departamento` 
			WHERE
			departamento.id_estado=1
			ORDER BY departamento.nombre";   
	$departamento_lista=mysql_query($query) or die(mysql_error());
	$row_departamento_lista = mysql_fetch_assoc($departamento_lista);
?>
<datalist id="departamento">
<? do{ ?>
  <option value="<?= $row_departamento_lista['nombre'];?>">
<? }while($row_departamento_lista=mysql_fetch_array($departamento_lista));?>
</datalist>


<div class="alert alert-info">
<button type="button" class="close" data-dismiss="alert">&times;</button>
	<form class="form-inline" action="index.php" name="ente">
	<tr>
	<td>
		<div class="input-prepend">
		<span class="add-on" onclick="document.getElementById('legajo').focus();"><i class="icon-paper-clip"></i></span>
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
<BR>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->           
<div id="target">
<img class="carga" src="imagenes/cargando.gif" />
<table  id="table" class="sortable">
<thead>
	<th title="Legajo de los usuarios"><h3>Legajo</h3></th>
	<th title="Nombre de los usuarios"><h3>Nombre</h3></th>
	<th title="Departamento al que pertenecen"><h3>Sector</h3></th>
	<th title="sin definir"><h3>sd</h3></th>
	<th title="Mañana - Entrada"><h3>m-e</h3></th>
	<th title="Mañana - Salida"><h3>m-s</h3></th>
	<th title="Tarde - Entrada"><h3>t-e</h3></th>
	<th title="Tarde - Salida"><h3>t-s</h3></th>
	<th title="Otro tipo"><h3>otros</h3></th>
	<th title="Editar las entradas"><h3>editar</h3></th>
</thead>

<tbody>
<?
# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query="SELECT * 
		FROM marcada 
		WHERE 
		DATE_FORMAT(entrada, '%Y-%m-%d') like '$fecha_americana'
		AND	id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());
		$row_marcacion = mysql_fetch_assoc($marcacion);
		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));

# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query="SELECT * 
		FROM otrahora 
		WHERE fecha = '$fecha_americana'";   
		$otrahora=mysql_query($query) or die(mysql_error());
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			


do{?>
	<tr>
	<td><? echo $row_usuario['legajo']?></td>
	<td><a href="usuario.php?id=<?= $row_usuario['id']?>&fecha=<?= $fecha;?>" class="ayuda-boton btn"><? echo $row_usuario['usuario']?></a></td>
	<td><? echo $row_usuario['departamento']?></td>
		<? 
		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				id_usuario='$row_usuario[id]'
				AND id_parametros=$i";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			?>

			<?
			if($cantidad_parametros==0){?>
				<td><p class="insert_access"> - </p></td>
			<?}else if($cantidad_parametros>1){?>
				<td><p class="duplicado" title="Registro duplicado, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
			<?}else{
				if($row_marcacion['id_estado']==3){
					$query="SELECT * 
					FROM log_auditoria_marcada
					WHERE
					id_marcada='$row_marcacion[id_marcada]'";   
				$log_auditoria_marcada=mysql_query($query) or die(mysql_error());
				$row_log_auditoria_marcada = mysql_fetch_assoc($log_auditoria_marcada);
				?>
				<td><p class="modificado" title="Registro modificado, original :<? echo date('H:i', strtotime($row_log_auditoria_marcada['entrada_old']));?>"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_estado']==2){?>
				<td><p class="insert_php" title="Registro dado de alta por sistema"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
				<?}else if($row_marcacion['id_parametros']==0){?>
				<td><p class="duplicado" title="Registro sin definir, por favor modificarlo"><? echo date('H:i', strtotime($row_marcacion['entrada']));?></p></td>
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
				id_usuario='$row_usuario[id]'";   
			$otrahora=mysql_query($query) or die(mysql_error());
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
			if($cantidad>0){
		?>
		<td><p class="insert_access"><a href="#" class="btn" title="<? echo $row_otrahora['nota'];?>" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id']?>&fecha=<?echo $fecha_americana?>')"><? echo $row_otrahora['tipootra'];?> : <? echo $row_otrahora['horas'];?></a></p></td>
		<?}else{?>
		<td><p class="insert_access"><a href="#" class="btn" title="Agregar" onClick="abrirVentana('edit_otros.php?id=<?echo $row_usuario['id']?>&fecha=<?echo $fecha_americana?>')"><i class="icon-plus-sign-alt"></i></a></p></td>
		<?}?>
	<td><a href="#" class="btn" title="Parametros" onClick="abrirVentana('edit.php?id=<?echo $row_usuario['id']?>&fecha=<?echo $fecha_americana?>')"><i class="icon-edit-sign"></i></a></td>
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
 
<!--Controles de la tabla-->            
	<div id="controls">
	<div id="perpage">
		<select onchange="sorter.size(this.value)">
		<option value="5">5</option>
			<option value="10" selected="selected">10</option>
			<option value="20">20</option>
			<option value="50">50</option>
			<option value="100">100</option>
		</select>
		<span>Cantidad por Pagina</span>
	</div>
	<div id="navigation">
		<img src="imagenes/first.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1,true)" />
		<img src="imagenes/previous.gif" width="16" height="16" alt="First Page" onclick="sorter.move(-1)" />
		<img src="imagenes/next.gif" width="16" height="16" alt="First Page" onclick="sorter.move(1)" />
		<img src="imagenes/last.gif" width="16" height="16" alt="Last Page" onclick="sorter.move(1,true)" />
	</div>
	<div id="text">Mostrando pagina <span id="currentpage"></span> de <span id="pagelimit"></span></div>
    <p><br /></p>
    <p>&nbsp; </p>
    </div>

<!--script de la tabla, ver si se cambia de lugar-->	
	<script type="text/javascript">
  var sorter = new TINY.table.sorter("sorter");
	sorter.head = "head";
	sorter.asc = "asc";
	sorter.desc = "desc";
	sorter.even = "evenrow";
	sorter.odd = "oddrow";
	sorter.evensel = "evenselected";
	sorter.oddsel = "oddselected";
	sorter.paginate = true;
	sorter.currentid = "currentpage";
	sorter.limitid = "pagelimit";
	sorter.init("table",1);
  </script>   

</center>
</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->

</body>
