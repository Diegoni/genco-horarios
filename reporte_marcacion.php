<?php    
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
	
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."departamentos_model.php");
include_once($url['models_url']."empresas_model.php");
include_once($url['models_url']."convenios_model.php");
include_once($url['models_url']."convenio_turnos_model.php");
include_once($url['models_url']."marcadas_model.php");
include_once($url['models_url']."otrahora_model.php");
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
						Formulario Inicial
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	

<div class="row well">
	<div class="col-lg-12">
		<p>Reporte de Marcaciones</p>
		
		<form class="form-horizontal" role="form">
		<div class="col-md-6">	
		<div class="form-group">	
			<label for="inputEmail3" class="col-sm-4 control-label"> Seleccione grupo </label>
			<div class="col-md-8">	
				<select name="grupo" class="chosen-select form-control"
				onChange="javascript:window.location.href='reporte_marcacion.php?grupo='+this.value+'';">
					<option></option>
					<option value="1" <?php echo selected(1, 'grupo');?>>Usuario</option>
					<option value="2" <?php echo selected(2, 'grupo');?>>Departamento</option>
					<option value="3" <?php echo selected(3, 'grupo');?>>Empresa</option>
					<option value="4" <?php echo selected(4, 'grupo');?>>Convenio</option>
				</select>
			</div>
		</div>
		
		
		<?php if(isset($_GET['grupo'])){ ?>
		
		<div class="form-group">
			<label for="inputEmail3" class="col-sm-4 control-label"> Seleccione <?php echo $grupo ?> </label>
			<div class="col-md-8">	
				<select name="id" class="chosen-select form-control"
				<?php if(isset($_GET['buscar'])){ ?>
					onChange="javascript:window.location.href='reporte_marcacion.php?id='+this.value+'&grupo=<?php  echo $_GET['grupo'];?>&fecha_inicio=<?php echo $fecha_inicio?>&fecha_final=<?php echo $fecha_final?>&buscar=1'">
				<?php }else{ ?>
					onChange="javascript:window.location.href='reporte_marcacion.php?id='+this.value+'&grupo=<?php  echo $_GET['grupo'];?>'">
				<?php } ?>
					<option value=""></option>
					<?php do{ ?>
						<option value="<?php echo $row_query['id_'.$grupo]?>" <?php echo selected($row_query['id_'.$grupo], 'id');?>>
							<?php echo $row_query[$grupo]?>
						</option>
					<?php }while ($row_query = mysql_fetch_array($query)) ?>
				</select>
			</div>
		</div>
		
		<?php } ?>
		</div>
		
		<?php if(isset($_GET['id'])){ ?>
		
		<div class="col-md-6">	
		<div class="form-group">
			<div class="col-md-6 ">
    		<div class="input-group">
      			<div class="input-group-addon" onclick="document.getElementById('datepicker2').focus();">
      				<span class="add-on">
      					<i class="icon-calendar"></i>
      				</span>
      			</div>
      			<input value="<?php echo date('d-m-Y', strtotime($fecha_inicio)); ?>" type="text" name="fecha_inicio" id="datepicker2" placeholder="fecha de inicio" class="form-control" autocomplete="off" required>
    		</div>
    		</div>
			
			<div class="col-md-6 ">
    		<div class="input-group">
      			<div class="input-group-addon" onclick="document.getElementById('datepicker').focus();">
      				<span class="add-on">
      					<i class="icon-calendar"></i>
      				</span>
      			</div>
      			<input value="<?php echo date('d-m-Y', strtotime($fecha_final)); ?>"	type="text" name="fecha_final" id="datepicker" placeholder="fecha final" class="form-control" autocomplete="off" required>
    		</div>
    		</div>
  		</div>
		</div>
		
		<div class="col-md-6 pull-right">		
		<div class="form-group">
			<div class="col-md-12">
				<button type="submit" class="btn btn-default pull-right" title="Buscar" name="buscar" value="1">
				<i class="icon-search"></i> Buscar
			</button>
			</div>
		</div>
		</div>
		<?php } ?>
		
		</form>
	</div>
</div>

<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Usuarios
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	


<?php if(isset($_GET['buscar'])){ ?>
<div class="row">
	<div class="col-md-12" id="scrollit">
		<div class="panel panel-default">
			<div>
				<div class="btn-group  pull-right">
					<button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
						<i class="icon-print"></i> Imprimir <span class="caret"></span>
					</button>
					<ul class="dropdown-menu" role="menu">
						<li><a href="usuario_reporte_grupo.php?grupo=<?php echo $_GET['grupo']?>&id=<?php echo $_GET['id']?>&fecha_inicio=<?php echo $fecha_inicio?>&fecha_final=<?php echo $fecha_final?>&buscar=1" target="_blank"> Empleado</a></li>
						<li><a href="usuario_reporte_grupo.php?grupo=<?php echo $_GET['grupo']?>&id=<?php echo $_GET['id']?>&fecha_inicio=<?php echo $fecha_inicio?>&fecha_final=<?php echo $fecha_final?>&buscar=2" target="_blank"> Empresa</a></li>
					</ul>
				</div>
				<a class='btn btn-default pull-right' href="javascript:imprSelec('muestra')" >
					<i class="icon-table"></i> Tabla
				</a>
				<button class="btn btn-default pull-right excel" onclick="tableToExcel('example', '<?php echo $grupo?>')">
					<i class="icon-download-alt"></i> Excel
				</button>
			</div>
			<br>
			<hr>
			<div class="panel-body" id="muestra">
				<table border="1" style="width: 100%" class="table table-hover" id="example">
					<thead>
						<tr class="success">
							<th style="text-align: center">Usuario</th>
							<th style="text-align: center">Día</th>
							<th style="text-align: center">Fecha</th>
							<th style="text-align: center">m-e</th>
							<th style="text-align: center">m-s</th>
							<th style="text-align: center">t-e</th>
							<th style="text-align: center">t-s</th>
							<th style="text-align: center">Otros</th>
							<th title="Subtotales">Subtotal</th>
							<?php if($config['mostrar_marcada']==1){ ?>
							<th title="Calculo de horas laborales">Horas</th>
							<?php } ?>
							<?php if($config['aplicar_redondeo']==1){ ?>
							<th title="Redondeo de horas">R</th>
							<?php } ?>
						</tr>
					</thead>
				<tbody>
				<?php do{ ?>
					<a name="seccion<?php echo $row_usuario2['id_usuario']?>" id="seccion<?php echo $row_usuario2['id_usuario']?>"></a>
					<div class="slidingDiv <?php echo $row_usuario2['id_usuario']?>">
						<?php 
						if(isset($_GET['buscar'])){?>
							
							<a href="#" class="upscroll">
								<h3>
									<img src="<?php echo $url['imagenes']?>loading_small.gif"><?php echo $row_usuario2['usuario'];?>
								</h3>
							</a>
							
							
						<?php
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
								?>
								
								<tr>
									<td><p class='dia'><?php echo $row_usuario2['usuario']?> </p></td>
									<td><p class='dia'><?php echo devuelve_dia($valor)?> </p></td>
									<td><p class='<?php echo $clase ?>' title='<?php echo $title ?>'><?php echo $valor ?></p></td>
									<td><?php echo $array_marcaciones['marcacion-1-'.$valor]; ?></td> 	
									<td><?php echo $array_marcaciones['marcacion-2-'.$valor]; ?></td>
									<td><?php echo $array_marcaciones['marcacion-3-'.$valor]; ?></td>
									<td><?php echo $array_marcaciones['marcacion-4-'.$valor]; ?></td>
									<td><?php echo $array_otrashoras['otrahora-'.$valor]; ?></td>
									<?php if($subtotal>0){ ?>
										<td><?php echo pasar_hora($m)." + ".pasar_hora($t);?></td>
											
										<?php if($config['mostrar_marcada']==1){ ?>
											<td><?php echo pasar_hora($subtotal); ?></td>	
										<?php } ?>  
										
										<?php if($config['aplicar_redondeo']==1){ ?>
											<td><?php echo redondear_minutos(pasar_hora($subtotal)); ?></td>
												<?php $subtotal=segundos_a_hora(redondear_minutos(pasar_hora($subtotal)))/60/60; ?>
										<?php } ?> 
									<?php } else {?>
										<td> - </td>
										<?php if($config['mostrar_marcada']==1){ ?>
											<td> - </td>
										<?php } ?>  
										
										<?php if($config['aplicar_redondeo']==1){ ?>
											<td> - </td>
										<?php } ?> 
									<?php } ?>
								</tr>
								
						<?php } ?>
								
						<?php
						}else{ ?>
							<div class="alert alert-warning alert-dismissible" role="alert">
  								<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Cerrar</span></button>
  								Para ver las marcaciones de <strong><?php echo $row_usuario2['usuario'] ?></strong> seleccione un periodo de tiempo
							</div>
						<?php
						} 
						?>
					</div>
					
					<script>
					$(".<?php echo $row_usuario2['id_usuario']?>").hide();
					      
					$('.show_hide<?php echo $row_usuario2['id_usuario']?>').click(function(){
		    			$(".<?php echo $row_usuario2['id_usuario']?>").slideToggle();
		    			$('#li<?php echo $row_usuario2['id_usuario']?>').toggleClass('active');
		    			$('#go<?php echo $row_usuario2['id_usuario']?>').toggleClass('disabled');
		    		});
		    		
		    		$('.#go<?php echo $row_usuario2['id_usuario']?>').click(function(){
            			$("html, body").animate({ scrollTop: $("#seccion<?php echo $row_usuario2['id_usuario']?>").scrollTop() }, 1000);
           				return false;
        			});
 
		    		</script>
				<?php }while ($row_usuario2 = mysql_fetch_array($usuarios2)) ?>
				</tbody>
							</table>
							<H1 class="SaltoDePagina"></H1>
			</div>
		</div>
	</div>
	</div>
</div>
<?php } ?>


<!------------------------------------------------------------------------------------
--------------------------------------------------------------------------------------
						Tabla
--------------------------------------------------------------------------------------
------------------------------------------------------------------------------------->	




<?php   include_once("footer.php");?> 


</div><!--cierra el class="span12" -->
</div><!--cierra el row -->
</div><!--cierra el class="container"-->


</body>
