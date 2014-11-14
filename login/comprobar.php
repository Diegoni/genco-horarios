<?php 
    session_start(); 
    include('../config/database.php');
		include('../config/config.php');?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../<?php echo $url['librerias_url']?>css/login.css">
<link href="../<?php echo $url['librerias_url']?>bootstrap/css/<?php echo $config['css']?>.css" rel="stylesheet" media="screen">
<head>
<body>
	<?php		
	if(isset($_POST['enviar'])) { 
			// comprobamos que se hayan enviado los datos del formulario 
			// comprobamos que los campos usuarios_nombre y usuario_clave no estén vacíos 
		if(empty($_POST['usuario_nombre']) || empty($_POST['usuario_clave'])) {?> 
		<div class="container">
			<div class="login-container">
				<div id="output"></div>
				<div class="avatar" style="background-image: url('user.png');"></div>
				<div class="form-box">
					<div class="row">
						<div class="alert alert-danger" role="alert">
							<?php echo "El usuario o el password no han sido ingresados."?>
						</div> 
						<a href="acceso.php" class="btn btn-default">Reintentar</a> 
					</div>
				</div>
			</div>
		</div> 
		<?php }else { 
				// "limpiamos" los campos del formulario de posibles códigos maliciosos 
				$usuario_nombre	= mysql_real_escape_string($_POST['usuario_nombre']); 
				$usuario_clave	= mysql_real_escape_string($_POST['usuario_clave']); 
				$usuario_clave	= md5($usuario_clave); 
				// comprobamos que los datos ingresados en el formulario coincidan con los de la BD 
				$sql = mysql_query("SELECT * FROM usuarios WHERE usuario_nombre='".$usuario_nombre."' AND usuario_clave='".$usuario_clave."'"); 
				if($row = mysql_fetch_array($sql)) { 
					$_SESSION['usuario_id']		= $row['usuario_id']; // creamos la sesion "usuario_id" y le asignamos como valor el campo usuario_id 
					$_SESSION['usuario_nombre'] = $row["usuario_nombre"]; // creamos la sesion "usuario_nombre" y le asignamos como valor el campo usuario_nombre 
					$_SESSION['id_tipousuario'] = $row["id_tipousuario"]; 
					header("Location: ../index.php"); 
				}else { 
?>

		<div class="container">
			<div class="login-container">
				<div id="output"></div>
				<div class="avatar" style="background-image: url('user.png');"></div>
				<div class="form-box">
					<div class="row">
						<div class="alert alert-danger" role="alert">
							Error, no se ha podido conectar
						</div> 
						<a href="acceso.php" class="btn btn-default">Reintentar</a> 
					</div>
				</div>
			</div>
		</div> 
<?php 
			} 
			} 
	}else { 
		header("Location: acceso.php"); 
	} 
?>
</body>
</html>