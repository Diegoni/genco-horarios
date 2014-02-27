<?php include_once("menu.php"); ?>
<?

$query="SELECT * FROM `empresa` WHERE id_empresa='$_GET[id_empresa]'";    
$empresa=mysql_query($query) or die(mysql_error());
$row_empresa = mysql_fetch_assoc($empresa);
mysql_query("SET NAMES 'utf8'");

//pregunta por la opcion de usar cbu, 1=si y 0=no
if ($_GET['_Folio']==1){
	$Folio=$_GET['Folio'];
}else{
	$Folio="0";
}
if ($_GET['_Digito1']==1){
	$Digito1=$_GET['Digito1'];
}else{
	$Digito1="0";
}
if ($_GET['_Sucursal']==1){
	$Sucursal=$_GET['Sucursal'];
}else{
	$Sucursal="0";
}
if ($_GET['_Digito2']==1){
	$Digito2=$_GET['Digito2'];
}else{
	$Digito2="0";
}
 
	$Importe=$_GET['Importe'].$_GET['Centavos'];
  	mysql_query("INSERT INTO `detalle` (
					Nombre,
					Cuit,
					FechAcred,
					TipoCuenta,
					Moneda,
					Folio,
					Digito1,
					Sucursal,
					Digito2,
					CBU,
					CodTransac,
					TipoTransc,
					Importe,
					Referencia,
					IdCliente,
					FecMov)
			VALUES (
				'".$_GET['Nombre']."',
				'".$_GET['Cuit']."',
				'".$_GET['FecAcred']."',
				'".$_GET['TipoCuenta']."',
				'".$_GET['Moneda']."',
				'".$Folio."',
				'".$Digito1."',
				'".$Sucursal."',
				'".$Digito2."',
				'".$_GET['CBU']."',
				'".$_GET['CodTransac']."',
				'".$_GET['TipoTransc']."',
				'".$Importe."',
				'".$_GET['Referencia']."',
				'".$_GET['IdCliente']."',
				'".$_GET['FecMov']."')	
			") or die(mysql_error());

  	
			?>
			


<div class="span9">
<center>

<!--------------------------------------------------------------------
----------------------------------------------------------------------
			Formulario
----------------------------------------------------------------------			
--------------------------------------------------------------------->
<h4>El detalle se ha modificado con éxito <i class="icon-thumbs-up text-success"></i></h4>
<table class="table table-striped table-hover">

<!-- Cabecera -->
.
<tr class="success">
<td></td>
<td><b>Carga del footer</b></td>
</tr>

<!-- Formulario -->

<form class="form-inline" action="armartxt.php" name="ente" >
<tr>
<td>Tipo Registro</td>
<td><input type="text" class="span6" name="TipoRegistro" maxlength="2" value="*F" placeholder="*F" required></td>
</tr>

<tr>
<td>Empresas</td>
<td><input type="text" class="span6" name="Empresas" maxlength="6" onkeypress="return isNumberKey(event)" value="<?echo $row_empresa['Empresa'] ?>" placeholder="Código de prestación de la empresa." required></td>
</tr>

<tr>
<td>Cantidad de Registros</td>
<td><input type="number" class="span6" name="CantRegistros" maxlength="11" onkeypress="return isNumberKey(event)" placeholder="Cantidad de registros" value="1" required></td>
</tr>

<tr>
<td></td>
<td><button type="submit" class="btn" title="Guardar footer" name="id_ente" value="<?echo $_GET['id_ente']?>">Aceptar</button></td>
</tr>

<script language="JavaScript">
document.ente.TipoRegistro.focus();
</script>

</table>
</center>
</div>
