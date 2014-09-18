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
		<div class="navbar navbar-inverse">
			<div class="navbar-inner">
				
					<button type="button" class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
					<a class="brand" href="./index.php">
					<img width="106" height="40"  src="<?php echo $config['logo'];?>"></a>
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
										<a class="brand" title="Cerrar sessión de usuario" href="login/logout.php"><?=$_SESSION['usuario_nombre']?> <i class="icon-signout"></i></a>
								  </a>
								</div>
							</li>
						</ul>
					</div>
				
			</div>
		</div>

<div class="container">	
