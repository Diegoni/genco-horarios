<?php  header('Content-type: text/html; charset=utf-8'); 
    session_start(); 
    include('../config/database.php');
    include('../config/config.php');?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../<?php echo $url['librerias_url']?>css/login.css">
<link href="../<?php echo $url['librerias_url']?>bootstrap/css/<?php echo $config['css']?>.css" rel="stylesheet" media="screen">

<script src="../<?php echo $url['librerias_url']?>bootstrap/js/jquery.js"></script>
<script src="../<?php echo $url['librerias_url']?>bootstrap/js/bootstrap.js"></script>
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
			
			<a href="" type="button" data-toggle="modal" data-target="#myModal">
			  No puedes ingresar
			</a>
			
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
	
	
	
!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
	<div class="modal-dialog">
		<form action="correo.php" method="get">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
				<h4 class="modal-title" id="myModalLabel">Problemas para acceder</h4>
			</div>
			<div class="modal-body">
				
					<div class="form-group">
						<label for="exampleInputEmail1">Mensaje</label>
						<textarea name="mensaje" type="text" class="form-control" id="exampleInputEmail1" placeholder="Enter mensaje" required>
						</textarea>
					</div>
					<div class="form-group">
						<label for="exampleInputEmail1">Email</label>
						<input name="email" type="email" class="form-control" id="exampleInputEmail1" placeholder="Enter email" maxlength="64" required>
					</div>				
			</div>
			<div class="modal-footer">
				<button type="submit" class="btn btn-default">Enviar</button>
			</div>
		</div>
		</form>
	</div>
</div>
</body>
</html>

