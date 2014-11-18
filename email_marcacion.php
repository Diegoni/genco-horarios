<?php    
session_start();
include_once("control_usuario.php");
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."convenios_model.php");
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."otrahora_model.php");
include_once($url['models_url']."encargados_model.php");
include_once($url['models_url']."correos_model.php");
include_once("helpers.php");

/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/

if(isset($_GET['grupo'])){
	if($_GET['grupo']==1){
		
		$query=getUsuarios();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='usuario';
		
	}else if($_GET['grupo']==2){
			
		$query=getDepartamentos();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='departamento';
		
	}else if($_GET['grupo']==3){
		
		$query=getEmpresas();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='empresa';
		
	}else if($_GET['grupo']==4){
		
		$query=getConvenios();
		$row_query = mysql_fetch_assoc($query);
		$numero_filas = mysql_num_rows($query);
		$grupo='convenio';
		
	}
}


/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar grupo de usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/


if(isset($_GET['id'])){
		
	if($_GET['grupo']==1){
		$campo='id_usuario';
	}else if($_GET['grupo']==2){
		$campo='id_departamento';
	}else if($_GET['grupo']==3){
		$campo='id_empresa';
	}else if($_GET['grupo']==4){
		$campo='id_convenio';
	}
	
	$usuarios=getUsuarios($_GET['id'], $campo);
	$row_usuario = mysql_fetch_assoc($usuarios);
	
	$usuarios2=getUsuarios($_GET['id'], $campo);
	$row_usuario2 = mysql_fetch_assoc($usuarios);
	$numero_usuario = mysql_num_rows($usuarios);

}

/*------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Buscar intervalo de fechas
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------*/

if($_GET['buscar']==1){
		$fecha_inicio=date( "Y-m-d", strtotime($_GET['fecha_inicio']));
		$fecha_final=date( "Y-m-d", strtotime($_GET['fecha_final']));
}else{
		$fecha=date("d-m-Y");
		$fecha_inicio=date('01-m-Y', strtotime($fecha));
		$ultimoDia = getUltimoDiaMes(date('Y', strtotime($fecha)),date('m', strtotime($fecha)));
		$fecha_final=$ultimoDia.date('-m-Y', strtotime($fecha));
}

?>


<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	


<?php if(isset($_GET['buscar'])){ ?>
<div class="row">
	<div class="col-md-12" id="scrollit">
		<div class="panel panel-default">
			<div class="panel-body" id="muestra">
				<?php 
				$mensaje	= $_GET['email']."<br>"; 
				?>
				
				<?php
				$mensaje	.= "<table border='1' style='width: 100%' class='table'>";
				$mensaje	.= "<thead>";
				$mensaje	.= "<tr>";
				$mensaje	.= "<th style='text-align: center'>Usuario</th>";
				$mensaje	.= "<th style='text-align: center'>Día</th>";
				$mensaje	.= "<th style='text-align: center'>Fecha</th>";
				$mensaje	.= "<th style='text-align: center'>m-e</th>";
				$mensaje	.= "<th style='text-align: center'>m-s</th>";
				$mensaje	.= "<th style='text-align: center'>t-e</th>";
				$mensaje	.= "<th style='text-align: center'>t-s</th>";
				$mensaje	.= "<th style='text-align: center'>Otros</th>";
				$mensaje	.= "<th>Subtotal</th>";
				
				if($config['mostrar_marcada']==1){
					$mensaje	.= "<th>Horas</th>";
				}
				
				if($config['aplicar_redondeo']==1){ 
					$mensaje	.= "<th>R</th>";
				}
				
				$mensaje	.= "</tr>";
				$mensaje	.= "</thead>";
				$mensaje	.= "<tbody>";
				
				do{
					
					 		$array_marcaciones=array();
							$array_otrashoras=array();
							
							if(isset($row_usuario2['id_usuario'])){														
								$marcacion = getMarcaciones($row_usuario2['id_usuario'], $fecha_inicio, $fecha_final);
								$row_marcacion = mysql_fetch_assoc($marcacion);   
								$cantidad_marcacion = mysql_num_rows($marcacion);
								
								$otrahora= getOtrahora($row_usuario2['id_usuario'], $fecha_inicio, $fecha_final);
								$row_otrahora = mysql_fetch_assoc($otrahora);
								$cantidad=mysql_num_rows($otrahora);
								
								do{
									$array_marcaciones['marcacion-'.$row_marcacion['id_parametros'].'-'.date('Y-m-d', strtotime($row_marcacion['entrada']))] = date('H:i', strtotime($row_marcacion['entrada']));
								}while ($row_marcacion = mysql_fetch_array($marcacion));
								
								do{
									$array_otrashoras['otrahora-'.date('Y-m-d', strtotime($row_otrahora['fecha']))]	= $row_otrahora['tipootra']." :".$row_otrahora['horas'];
								}while ($row_otrahora = mysql_fetch_array($otrahora));
								 								
								if($cantidad_marcacion>0){
									$arrayFechas=devuelveArrayFechasEntreOtrasDos($fecha_inicio, $fecha_final);
								}
							}
							
								
							foreach($arrayFechas as $valor){
								$subtotal=0;
								
								list ($clase, $title, $esferiado) = esferiado($valor);
								
								if($array_marcaciones['marcacion-1-'.$valor]>0 &&  $array_marcaciones['marcacion-2-'.$valor]>0){
									$m=intervalo_tiempo($array_marcaciones['marcacion-1-'.$valor],  $array_marcaciones['marcacion-2-'.$valor]);
								}else{
									$m=0;
								}
								
								if($array_marcaciones['marcacion-3-'.$valor]>0 && $array_marcaciones['marcacion-4-'.$valor]>0){
									$t=intervalo_tiempo($array_marcaciones['marcacion-3-'.$valor],$array_marcaciones['marcacion-4-'.$valor]);
								}else{
									$t=0;
								}
								
								if($t>0 || $m>0){
									$subtotal=$m+$t;
								}
								
								$mensaje	.= "<tr>";
								$mensaje	.= "<td><p class='dia'>".$row_usuario2['usuario']."</p></td>";
								$mensaje	.= "<td><p class='dia'>".devuelve_dia($valor)."</p></td>";
								$mensaje	.= "<td><p class='".$clase."' rel='tooltip' title='".$title."'>".$valor."</p></td>"; 
								$mensaje	.= "<td>".$array_marcaciones['marcacion-1-'.$valor]."</td>";
								$mensaje	.= "<td>".$array_marcaciones['marcacion-2-'.$valor]."</td>"; 	
								$mensaje	.= "<td>".$array_marcaciones['marcacion-3-'.$valor]."</td>";
								$mensaje	.= "<td>".$array_marcaciones['marcacion-4-'.$valor]."</td>";
								$mensaje	.= "<td>".$array_otrashoras['otrahora-'.$valor]."</td>";
	
								if($subtotal>0){
									$mensaje	.= "<td>".pasar_hora($m)." + ".pasar_hora($t)."</td>";
											
									if($config['mostrar_marcada']==1){ 
										$mensaje	.= "<td>".pasar_hora($subtotal)."</td>";	
									}  
									
									if($config['aplicar_redondeo']==1){
										$mensaje	.= "<td>".redondear_minutos(pasar_hora($subtotal))."</td>";
										$subtotal=segundos_a_hora(redondear_minutos(pasar_hora($subtotal)))/60/60;
									} 
								} else {
									$mensaje	.= "<td> - </td>";
									
									if($config['mostrar_marcada']==1){
										$mensaje	.= "<td> - </td>";
									}  
										
									if($config['aplicar_redondeo']==1){
										$mensaje	.= "<td> - </td>";
									} 
								}
								
								$mensaje	.= "</tr>";
								
								}
					
				}while ($row_usuario2 = mysql_fetch_array($usuarios2));
				$mensaje	.= "</tbody>";
				$mensaje	.= "</table>";
				$mensaje	.= "<H1 class='SaltoDePagina'></H1>";
				
				$asunto		= $_GET['asunto'];
				
				$cabeceras  = 'MIME-Version: 1.0' . "\r\n";
				$cabeceras .= 'Content-type: text/html; charset=UTF-8' . "\r\n";
				$cabeceras .= 'From: '.$config['remitente'].' <'.$config['correo'].'>' . "\r\n";
				
				if(isset($_GET['email_1'])){
					$email_1	= $_GET['email_1'];
					mail($email_1, $asunto, $mensaje, $cabeceras);	
				}else{
					$email_1	= "-";
				}
				
				if(isset($_GET['email_2'])){
					$email_2	= $_GET['email_2'];
					mail($email_2, $asunto, $mensaje, $cabeceras);	
				}else{
					$email_2	= "-";
				}
				
				if(isset($_GET['email_3'])){
					$email_3	= $_GET['email_3'];
					mail($email_3, $asunto, $mensaje, $cabeceras);	
				}else{
					$email_3	= "-";
				}
				
				$datos=array(
					'asunto'			=> $_GET['asunto'],
					'mensaje'			=> $_GET['email'],
					'fecha_inicio'		=> date('Y-m-d', strtotime($_GET['fecha_inicio'])),
					'fecha_final'		=> date('Y-m-d', strtotime($_GET['fecha_final'])),
					'grupo'				=> $_GET['grupo'],
					'id'				=> $_GET['id'],
					'email_1'			=> $email_1,
					'email_2'			=> $email_2,
					'email_3'			=> $email_3,
					'id_usuario'		=> $_SESSION['usuario_id'],
					'fecha'				=> date('Y-m-d H:i:s'),
					'id_tipo_reporte'	=> 1
				);
				
				insertCorreo($datos);
				?>
				<div class="alert alert-success" role="alert">
					Su correo fue enviado con éxito <a href="#" class="alert-link"></a>
				</div>
				<div class="row">
					<div class="col-md-2"><b>Asunto</b></div>
					<div class="col-md-10"><?php echo $asunto ?></div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-2"><b>Correo</b></div>
					<div class="col-md-10">
						<?php 
							if(isset($_GET['email_1'])){
								echo "<p>".$_GET['email_1']."</p>";
							} 
							
							if(isset($_GET['email_2'])){
								echo "<p>".$_GET['email_2']."</p>";
							}

							if(isset($_GET['email_3'])){
								echo "<p>".$_GET['email_3']."</p>";
							}
						?>
					</div>
				</div>
				<hr>
				<div class="row">
					<div class="col-md-2"><b>Mensaje</b></div>
					<div class="col-md-10"><?php echo $mensaje ?></div>
				</div>
			</div>
		</div>
	</div>
</div>
<?php } ?>

<?php   include_once("footer.php");?> 


</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->


</body>
