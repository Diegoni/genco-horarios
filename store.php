<? 
include_once("config/database.php");
	
function SumarHoras($fecha_inicial, $fecha_final, $id){
	$mysqli = new mysqli("localhost", "root", "");
	if(!$mysqli) die('Could not connect: ' . mysql_error());
	mysqli_select_db($mysqli, "controlfinal2");
	if(!$mysqli) die('Could not connect to DB: ' . mysql_error()); 
	
	$username = strtolower($_COOKIE["username"]);
	$mysqli->query("SET @un = " . "'" . $mysqli->real_escape_string($username) . "'");

	$result = $mysqli->query("call SumarHoras('$fecha_inicial', '$fecha_final', '$id');");
	if(!$result) die("CALL failed: (" . $mysqli->errno . ") " . $mysqli->error);
	 
	if($result->num_rows > 0) 
	{
		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC))
		{
			$Normales = $row["Normales"];
			$Extra = $row["Extra"];
			
					echo $Normales;
					echo " - ";
					echo $Extra;
					echo "<br>";

		}
	}
	else {
					
					echo "cero";
	}
}

$query="SELECT * FROM `usuario` ORDER BY nombre ASC";   
$usuario=mysql_query($query) or die(mysql_error());
$row_usuario = mysql_fetch_assoc($usuario);

do{
echo $row_usuario['usuario'];
echo " : ";
SumarHoras('2012-04-01', '2012-04-30', $row_usuario['id_usuario']);
}while ($row_usuario = mysql_fetch_array($usuario))


?>