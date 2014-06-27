<?php include_once("head.php");?>
<body>
<!--------------------------------------------------------------------
----------------------------------------------------------------------
							Menú principal
----------------------------------------------------------------------
--------------------------------------------------------------------->
<div class="row">	
	<div class="span12">
		<div class="navbar navbar-inverse navbar-fixed-top">
			<div class="navbar-inner">
				<div class="container">
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="./index.php">
					<img width="106" height="40"  src="<?php echo $logo;?>"></a>
					<div class="nav-collapse collapse">
						<ul class="nav">
							<li class="opcion">
								<a href="index.php">Inicio</a>
							</li>
							<li class="">
								<a href="usuario.php">Total usuario</a>
							</li>
							<li class="">
								<a href="usuario2.php">Total usuarios</a>
							</li>
							<li class="">
								<a href="usuarios.php">Usuarios</a>
							</li>
							<li class="">
								<a href="departamentos.php">Departamentos</a>
							</li>
							<li class="">
								<a href="empresas.php">Empresas</a>
							</li>
							<li class="">
								<div class="btn-group">
								  <a class="negro dropdown-toggle" data-toggle="dropdown" href="#">
										<i class="icon-cogs"></i>
								  </a>
									<ul class="dropdown-menu">
										<li><a href="feriados.php">Feriados</a></li>
										<li><a href="convenios.php">Convenios</a></li>							
										<li><a href="limites.php">Limites</a></li>
										<li><a href="config.php">Config</a></li>
										<li><a href="log.php">Logs</a></li>
										<li><a href="exportar/Genco-Horarios.pdf" target="_blank">Ayuda</a></li>
									</ul>
								</div>
							</li>
						</ul>
						
						<ul class="nav pull-right">						
							<li class="">
								<div class="btn-group">
								  <a class="negro dropdown-toggle" data-toggle="dropdown" href="#">
										<a class="brand" href="#"><?=$_SESSION['usuario_nombre']?></a>
								  </a>
									<ul class="dropdown-menu">
										<li><a title="Cerrar sessión de usuario" href="login/logout.php">Cerrar Sesión</a></li>
									</ul>
								</div>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
