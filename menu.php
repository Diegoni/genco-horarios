<?php 
include_once("head.php");
include_once("helpers.php");
?>
<body>
	<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Menú principal
----------------------------------------------------------------------
--------------------------------------------------------------------->
	
	
<nav class="navbar navbar-default navbar-inverse" role="navigation">
	<div class="container-fluid">
	<div class="navbar-header">
		<button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
			<span class="sr-only">Toggle navigation</span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
			<span class="icon-bar"></span>
		</button>
		<a class="navbar-brand" href="#">
			<img width="106" height="30"  src="<?php echo $config['logo'];?>"></a>
		</a>
	</div>

	<!-- Collect the nav links, forms, and other content for toggling -->
	<div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
		<ul class="nav navbar-nav">
		
			<li class="opcion">
				<a href="index.php"><i class="icon-home"></i> Inicio</a>
			</li>
			
			<li class="">
				<a href="usuario.php"><i class="icon-calendar"></i> Total usuario</a>
			</li>
			
			<li class="">
				<a href="usuario2.php"><i class="icon-tasks"></i> Sumas totales</a>
			</li>
			
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-bar-chart"></i> Reporte <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="reporte_marcacion.php">Marcaciones</a></li>
					<li><a href="reporte_tardanza.php">Tardanza</a></li>							
				</ul>
			</li>
			
			<li class="">
				<a href="usuarios.php"><i class="icon-user"></i> Usuarios</a>
			</li>
								
			<li class="">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-group"></i> Departamentos <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="departamentos.php">Departamentos</a></li>
					<li><a href="encargados.php">Encargados</a></li>							
				</ul>
				
			</li>
								
			<li class="">
				<a href="empresas.php"><i class="icon-building"></i> Empresas</a>
			</li>
			
			<li class="">
				<a href="relojes.php"><i class="icon-time"></i> Relojes</a>
			</li>
			
			<li class="dropdown">
				<a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-cogs"></i> <span class="caret"></span></a>
				<ul class="dropdown-menu" role="menu">
					<li><a href="feriados.php">Feriados</a></li>
					<li><a href="convenios.php">Convenios</a></li>							
					<li><a href="limites.php">Limites</a></li>
					<li><a href="config.php">Config</a></li>
					<li><a href="update_relojes_form.php">Actualización manual</a></li>
					<li><a href="log.php">Logs</a></li>
					<li><a href="exportar/Genco-Horarios.pdf" target="_blank">Ayuda</a></li>
				</ul>
			</li>
		</ul>
     
		<ul class="nav navbar-nav navbar-right">
			<li class="">
				<a class="brand" title="Cerrar sessión de usuario" href="login/logout.php"><?php echo $_SESSION['usuario_nombre']?> <i class="icon-signout"></i></a>
			</li>
		</ul>
	</div><!-- /.navbar-collapse -->
	</div><!-- /.container-fluid -->
</nav>

<div class="container">	
