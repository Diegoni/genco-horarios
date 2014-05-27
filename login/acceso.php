<?php header('Content-type: text/html; charset=utf-8'); 
    session_start(); 
    include('../config/database.php');
    include('../config/config.php');?>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
<link rel="stylesheet" href="../<?= $librerias_url?>css/login.css">
<head>
<body>
<?php	
    if(empty($_SESSION['usuario_nombre'])) { // comprobamos que las variables de sesión estén vacías         
?> 

		<div id="firstboxlogin">
		<div id="logo_login"></div>
		<div id="boxlogin">
		<form action="comprobar.php" method="post" name="login"> 
		<fieldset>
		<legend>Autenticación</legend>
		<div class="row">
			<span class="label">
				<label>Usuario :  </label>
			</span>
			<span class="formw">
				<input type="text" name="usuario_nombre" />
			</span>
		</div>
		<div class="row">
			<span class="label">
				<label>Password : </label>
			</span>
			<span class="formw">
				<input type="password" name="usuario_clave" />
			</span>
			</div>
		</fieldset>
		<p><span><input class="button" type="submit" name="enviar" value="Ingresar" /></span></p>
		<script language="JavaScript">
		document.login.usuario_nombre.focus();
		</script>
		</form>    
		
        
           
             
                        
		</div>	
		</div>			
<?php 
    }else { 
?> 
		<div id="firstboxlogin">
		<div id="logo_login"></div>
		<div id="boxlogin">
		<fieldset>
		<legend>Autenticación</legend>
		<div class="row">
			<span class="label">
				<label>Usuario :  </label>
			</span>
			<span class="label">
				<?=$_SESSION['usuario_nombre']?>
			</span>
		</div>
		</fieldset>
		<p><span><a class="button" title="volver a la aplicación" href="../index.php">Volver</a> <a class="button" title="desconectar usuario" href="logout.php">Salir</a></span></p>
		<script language="JavaScript">
		document.login.usuario_nombre.focus();
		</script>
		</form>    
<?php 
    } 
?>
</body>
</html>

