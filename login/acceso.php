<?php 
    session_start(); 
    include('../config/database.php');
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
		<p><span><input type="submit" name="enviar" value="Ingresar" /></span></p>
		<script language="JavaScript">
		document.login.usuario_nombre.focus();
		</script>
		</form>    
		
        
           
             
                        
		</div>	
		</div>			
<?php 
    }else { 
?> 
        <p>Hola <strong><?=$_SESSION['usuario_nombre']?></strong> | <a href="logout.php">Salir</a></p> 
<?php 
    } 
?>
<meta http-equiv="Content-type" content="text/html; charset=utf-8" />
<link rel="stylesheet" href="../css/login.css">
