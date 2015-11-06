<?php
header("Content-Type: application/vnd.ms-excel");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("content-disposition: attachment;filename=Resumen.xls");
?>
<HTML LANG=”es”>
<title>Bases de Datos.</title>
<TITLE>Titulo de la Página.</TITLE>
</head>
<body>
<?php
		$username	= "root";
		$password	= "bluepill";
		$database	= "controlfinal4";
		$url		= "localhost";
		mysql_connect($url,$username,$password);
		@mysql_select_db($database) or die( "No pude conectarme a la base de datos");
		
	
//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Valores iniciales
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->
$id_usuario=$_GET['id'];
$totalotras=0;
$fecha=date("d-m-Y");

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.departamento as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE id_usuario='$id_usuario'";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.departamento as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuarios=mysql_query($query) or die(mysql_error());
$row_usuarios = mysql_fetch_assoc($usuarios);

$query="SELECT 	usuario.id_usuario,
				usuario.usuario as usuario,
				usuario.legajo as legajo,
				departamento.departamento as departamento				
		FROM `usuario` 
		INNER JOIN
		departamento on(usuario.id_departamento=departamento.id_departamento)
		WHERE usuario.id_estado=1
		ORDER BY usuario.usuario";   
$usuarios2=mysql_query($query) or die(mysql_error());
$row_usuarios2 = mysql_fetch_assoc($usuarios2);


$query="SELECT *				
		FROM `tipootra` 
		ORDER BY tipootra.id_tipootra";   
$tipootra=mysql_query($query) or die(mysql_error());
$row_tipootra = mysql_fetch_assoc($tipootra);





//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Funciones php
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->


function devuelveArrayFechasEntreOtrasDos($fechaInicio, $fechaFin){
	$arrayFechas=array();
	$fechaMostrar = $fechaInicio;

	while(strtotime($fechaMostrar) <= strtotime($fechaFin)) {
	$arrayFechas[]=$fechaMostrar;
	$fechaMostrar = date("Y-m-d", strtotime($fechaMostrar . " + 1 day"));
	}

	return $arrayFechas;
} 

function redondear_minutos($hora){
	$horas=date("H", strtotime($hora));
	$minutos=date("i", strtotime($hora));

	$query="SELECT *
			FROM `limite` 
			ORDER BY limite";   
	$limite=mysql_query($query) or die(mysql_error());
	$row_limite = mysql_fetch_assoc($limite);


	do{
	if($minutos<$row_limite['limite']){
		$minutos=$row_limite['redondeo'];
		$horas=$horas+$row_limite['suma_hora'];
		return "$horas:$minutos";
		break;
	}
	}while($row_limite= mysql_fetch_array($limite));


}

function intervalo_tiempo($init,$finish)
{
	$diferencia = strtotime($finish) - strtotime($init);
	$diferencia=round($diferencia/60);
	$diferencia=$diferencia/60;
	if($diferencia<0){
		$diferencia="ERROR";
	}
    return $diferencia;
}

function pasar_hora($num){
	$num=$num*60;
	$hora_cd = $num*0.01666666667; //hora sin decimales
	$hora = floor($num*0.01666666667);//hora sin decimales
	$resto = $hora_cd-$hora;
	$minutos = round($resto*60);
	if($minutos<10){
		$minutos="0".$minutos;
	}
	$final= "".$hora.":".$minutos."";	
	
	return $final;
}

function devuelve_dia($fecha){
	$i = strtotime($fecha); 
	$nro = jddayofweek(cal_to_jd(CAL_GREGORIAN, date("m",$i),date("d",$i), date("Y",$i)));
	switch ($nro) {
	case 0:
         $dia="Domingo";
         break;
	case 1:
         $dia="Lunes";
         break;
	case 2:
         $dia="Martes";
         break;
	case 3:
         $dia="Miércoles";
         break;
	case 4:
         $dia="Jueves";
         break;
	case 5:
         $dia="Viernes";
         break;
	case 6:
         $dia="Sábado";
         break;
	}
	
	return $dia;
}




//----------------------------------------------------------------------
//----------------------------------------------------------------------
//						Busqueda de fechas
//----------------------------------------------------------------------			
//--------------------------------------------------------------------->

if(isset($_GET['buscar'])){

$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));

$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);

# Creo y completo tabla temporal para horas
$query_create = "CREATE TEMPORARY TABLE temp (id_marcada int, entrada datetime, id_usuario int, id_parametros int, id_estado int)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query="SELECT * 
		FROM marcada 
		WHERE 
		DATE_FORMAT(entrada, '%Y-%m-%d') >= '$fecha_inicio' AND
		DATE_FORMAT(entrada, '%Y-%m-%d') <= '$fecha_final' AND
		id_estado!=0";   
		$marcacion=mysql_query($query) or die(mysql_error());
		$row_marcacion = mysql_fetch_assoc($marcacion);   
$cantidad_marcacion = mysql_num_rows($marcacion);

		
do{
$query_ins = "INSERT INTO temp VALUES ('$row_marcacion[id_marcada]', '$row_marcacion[entrada]', '$row_marcacion[id_usuario]', '$row_marcacion[id_parametros]', '$row_marcacion[id_estado]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_marcacion = mysql_fetch_array($marcacion));



# Creo y completo tabla temporal para otras
$query_create = "CREATE TEMPORARY TABLE tempotra (id_usuario int, id_tipootra int, id_nota int, horas int, fecha date)";
$res_create = mysql_query($query_create) or die(mysql_error());

$query=
	"SELECT 
		* 
	FROM 
		otrahora 
	WHERE 
		fecha >= '$fecha_inicio' AND
		fecha <= '$fecha_final' AND
		eliminado = 0";   
		$otrahora=mysql_query($query) or die(mysql_error());
		$row_otrahora = mysql_fetch_assoc($otrahora);

do{
$query_ins = "INSERT INTO tempotra VALUES ('$row_otrahora[id_usuario]', '$row_otrahora[id_tipootra]', '$row_otrahora[id_nota]', '$row_otrahora[horas]', '$row_otrahora[fecha]')";
$res_ins = mysql_query($query_ins) or die(mysql_error());
}while ($row_otrahora = mysql_fetch_array($otrahora));			
}
?>


<!--------------------------------------------------------------------
----------------------------------------------------------------------
						Tabla
----------------------------------------------------------------------			
--------------------------------------------------------------------->	
<body>
<center>
<table border="1">
<thead>
	<th><h3>Legajo</h3></th>
	<th><h3>Usuario</h3></th>
	<th><h3>desde</h3></th>
	<th><h3>hasta</h3></th>
	<th><h3>Horas</h3></th>
	<? do{ ?>
	<th><h3><?= $row_tipootra['tipootra'];?></h3></th>
	<? }while($row_tipootra=mysql_fetch_array($tipootra))?>
	<th><h3>50%</h3></th>
	<th><h3>100%</h3></th>
</thead>

<tbody>
<?
//recorremos todos los usuarios
do{
$total=0;
$totalotras;
$id_usuario=$row_usuarios2['id_usuario'];
?>
<tr>
	<td><?= $row_usuarios2['legajo'];?></td>
	<td><?= $row_usuarios2['usuario'];?></td>
	<td><?= $fecha_inicio;?></td>
	<td><?= $fecha_final;?></td>
<?
foreach($arrayFechas as $valor){
	
		for ($i = 0; $i <= 4; $i++) {
				$query="SELECT * 
				FROM temp 
				WHERE
				DATE_FORMAT(entrada, '%Y-%m-%d') like '$valor'
				AND id_parametros=$i
				AND id_usuario=$id_usuario";   
			$marcacion=mysql_query($query) or die(mysql_error());
			$row_marcacion = mysql_fetch_assoc($marcacion);
			$cantidad_parametros=mysql_num_rows($marcacion);
			
			// Esta funcion redondea segun los la tabla limites de la base de datos, se pidio que se sacara
			//$redondear_minutos=redondear_minutos(date('H:i', strtotime($row_marcacion['entrada'])));

			if($cantidad_parametros==0){
				 
				if($i==1){
					$me=0;
				} else if($i==2){ 
					$ms=0;
				} else if($i==3){ 
					$te=0;
				} else if($i==4){ 
					$ts=0;
				}

			}else{
						
				if($i==1){
					$me=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==2){ 
					$ms=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==3){ 
					$te=date('H:i', strtotime($row_marcacion['entrada']));
				} else if($i==4){ 
					$ts=date('H:i', strtotime($row_marcacion['entrada']));
				}
						
			}//cierra el else
		}//cierra el for
		 if($me>0 && $ms>0){
			$m=intervalo_tiempo($me,$ms);
			}else{
			$m=0;
			}
			
			if($te>0 && $ts>0){
			$t=intervalo_tiempo($te,$ts);
			}else{
			$t=0;
			}
			
			if($t>0 || $m>0){
			$subtotal=$m+$t;
			$total=$total+$subtotal;
			}else{
			$subtotal=0;
			}
		
} 
if($total>0){ ?>
<td><?= pasar_hora($total); ?></td>
<? } else { ?>
<td> - </td>

<?}			$query="SELECT *				
					FROM `tipootra` 
					ORDER BY tipootra.id_tipootra";   
			$tipootra2=mysql_query($query) or die(mysql_error());
			$row_tipootra2 = mysql_fetch_assoc($tipootra2);
	
	$total_otrahora=0;
	do{
	$suma_otrahora=0;
	$id_tipootra=$row_tipootra2['id_tipootra'];
			
			$query="SELECT * 
				FROM tempotra 
				INNER JOIN tipootra ON(tempotra.id_tipootra=tipootra.id_tipootra)
				WHERE
				id_usuario='$id_usuario' AND
				tempotra.id_tipootra='$id_tipootra'";   
			$otrahora=mysql_query($query) or die(mysql_error());
			$row_otrahora = mysql_fetch_assoc($otrahora);
			$cantidad=mysql_num_rows($otrahora);
	do{
	$suma_otrahora=$suma_otrahora+$row_otrahora['horas'];
	}while($row_otrahora=mysql_fetch_array($otrahora));
	$total_otrahora=$total_otrahora+$suma_otrahora;
	if($suma_otrahora==0){ ?>
	<td> - </td>		
	<?}else{?>
	<td><?= $suma_otrahora;?></td>		
<? 	}
	}while($row_tipootra2=mysql_fetch_array($tipootra2));

	if($total_otrahora==0){ ?>
	<td> - </td>		
	<?}else{?>
	<td><?= $total_otrahora;?></td>		
<? 	} ?>
		<td> - </td>	
		</tr>
<?}while($row_usuarios2=mysql_fetch_array($usuarios2));


//elimino las tablas temporaria
$query_drop = "DROP TABLE temp";
$res_drop = mysql_query($query_drop) or die(mysql_error());

$query_drop = "DROP TABLE tempotra";
$res_drop = mysql_query($query_drop) or die(mysql_error());

?>


<!--Controles de la tabla-->            
</tbody>
</table>

</center>
</body>
