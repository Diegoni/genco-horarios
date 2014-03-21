<? include_once("head.php");?>
<body >
<div class="container">

<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Cabecera
----------------------------------------------------------------------
--------------------------------------------------------------------->

		
		<div class="row cabecera">
	
			<div class="span9">
				<p>Sistema de marcación horaria</p>
			</div>
			<div class="span3">
				<a href="http://www.tmsgroup.com.ar/" title="Sitio de TMS Group" target="_blank"><img class="imagenlogo" src="imagenes/logo.png"></a>
				<div class="session">
				<strong><?=$_SESSION['usuario_nombre']?></strong> 
				<a title="Cerrar sessión de usuario" href="login/logout.php">Cerrar Sessión</a> 
				</div>
			</div>
			<!--<a href='#' class='show_hide' title='Ayuda' id="ayuda-boton"><i class='icon-question-sign'></i></a>-->
			
		</div>
		

<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Menu principal
----------------------------------------------------------------------
--------------------------------------------------------------------->		
		
		<div class="row">	
		<div class="span12">
			<!-- antiguo menu
			<ul class="nav nav-pills nav-stacked">
				<li></li>
				<li class="dropdown">
                <a class="dropdown-toggle opciones" data-toggle="dropdown" href="#"><i class="icon-plus-sign-alt"></i> Nuevo <i class="icon-angle-right"></i></a>
					<ul class="dropdown-menu">
					  <li><a href="ente.php"><i class="icon-user"></i> Ente</a></li>
					  <li><a href="empresa.php"><i class="icon-suitcase"></i> Empresa</a></li>
					</ul>
				</li>		
				<li><a  class="opciones" href="entes.php"><i class="icon-group"></i> Entes</a></li>
				<li><a  class="opciones" href="empresas.php"><i class="icon-suitcase"></i> Empresas</a></li>
				<li><a  class="opciones" href="index.php"><i class="icon-usd"></i> Movimiento</a></li>
				<li class="dropdown">
                <a class="dropdown-toggle opciones" data-toggle="dropdown" href="#"><i class="icon-eye-open"></i> Ver <i class="icon-angle-right"></i></a>
					<ul class="dropdown-menu">
					  <li><a href="log.php"><i class="icon-list-ol"></i>  Movimientos</a></li>
					  <li><a href="archivo.php"><i class="icon-folder-open"></i>  Archivos</a></li>
					</ul>
				</li>				
			</ul>
			
		
			<ul class="nav nav-tabs">
			  <li><a href="index.php">Inicio</a></li>
			  <li><a href="#">...</a></li>
			  <li><a href="#">...</a></li>
			</ul>
			-->

        </div>
		</div>

