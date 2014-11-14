<?php  header('Content-type: text/html; charset=utf-8'); 
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
    if(empty($_SESSION['usuario_nombre'])) { // comprobamos que las variables de sesión estén vacías         
?>
<div class="container">
	<div class="login-container">
		<div id="output"></div>
		<div class="avatar" style="background-image: url('user.png');"></div>
		<div class="form-box">
			<form action="comprobar.php" method="post" name="login"> 
			<div class="row">
				<input type="text" name="usuario_nombre" placeholder="Ingrese usuario"/>
				<input type="password" name="usuario_clave" placeholder="Ingrese password"/>
			</div>
		
			<p>
				<span>
					<input class="btn btn-default" type="submit" name="enviar" value="Ingresar" />
				</span>
			</p>
			<script language="JavaScript">
				document.login.usuario_nombre.focus();
			</script>
			</form>
		</div>
	</div>
</div> 
	    
<?php  
    }else { 
?> 
		
<div class="container">
	<div class="login-container">
		<div id="output"></div>
		<div class="avatar" style="background-image: url('user.png');"></div>
		<div class="form-box">
			<form action="comprobar.php" method="post" name="login"> 
			<div class="row">
				<b>Usuario: </b>
				<?php echo $_SESSION['usuario_nombre']?>
			</div>
		
			<p>
				<span>
					<a class="btn btn-default" title="volver a la aplicación" href="../index.php">Volver</a>
					<a class="btn btn-default" title="desconectar usuario" href="logout.php">Salir</a></span></p>
				</span>
			</p>
			<script language="JavaScript">
				document.login.usuario_nombre.focus();
			</script>
			</form>
		</div>
	</div>
</div> 
		    
<?php } ?>
		</div>	
	</div>	
</body>
</html>

