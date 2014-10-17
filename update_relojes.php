<?php    
/*
session_start();
	if(!isset($_SESSION['usuario_nombre'])){
	header("Location: login/acceso.php");
	}
 *
 */
ini_set('max_execution_time', 600); //600 seconds = 10 minutes
include_once("menu.php"); 
include_once($url['models_url']."usuarios_model.php");
include_once($url['models_url']."relojes_model.php");
include_once($url['models_url']."marcadas_model.php");

function buscarMarcacion($datos){
	  $contador=0;	
	  $dom = new DOMDocument();
	  $html = $dom->loadHTMLFile('http://'.$datos['ip'].'/csl/query?action=run&uid='.$datos['id'].'&sdate='.$datos['start_date'].'&edate='.$datos['end_date'].'');
	
	  //discard white space 
	  $dom->preserveWhiteSpace = false; 
	
	  //the table by its tag name
	  $tables = $dom->getElementsByTagName('table'); 
	
	  //get all rows from the table
	  $rows = $tables->item(0)->getElementsByTagName('tr'); 
	
	  // loop over the table rows
	  foreach ($rows as $row) 
	  { 
	   		
		$cols = $row->getElementsByTagName('td'); 
	   // echo the values  
			if('Date'!=$cols->item(0)->nodeValue){
				
				$registro=array(
							'date'			=> $cols->item(0)->nodeValue,
							'id_user'		=> $cols->item(1)->nodeValue, 
							'user'			=> $cols->item(2)->nodeValue,
							'time'			=> $cols->item(3)->nodeValue,
							'status'		=> $cols->item(4)->nodeValue,
							'verification'	=> $cols->item(5)->nodeValue,
							'id_reloj'		=> $datos['id_reloj']
				);
				
				$contador = $contador + insertMarcadaReloj($registro);
				
			}
			
				
	    }
	return $contador; 
}

$relojes			= getRelojes();
$row_reloj			= mysql_fetch_assoc($relojes);
$cantidad_reloj		= mysql_num_rows($relojes);



do{
	$contador=0;
	echo $row_reloj['ip']."<br>";
	
	$usuarios			= getUsuarios();
	$row_usuario		= mysql_fetch_assoc($usuarios);
	$cantidad_usuario	= mysql_num_rows($usuarios);
	
	do{
			
		$datos=array(
					'ip'		=> $row_reloj['ip'],
					'id'		=> $row_usuario['id_usuario'],
					'start_date'=> $_GET['start_date'],
					'end_date'	=> $_GET['end_date'],
					'id_reloj'	=> $row_reloj['id_reloj']);
		$contador = $contador + buscarMarcacion($datos);
				
		
	}while($row_usuario=mysql_fetch_array($usuarios));
	
	echo "contador".$contador."<br>";
	
}while($row_reloj=mysql_fetch_array($relojes));



?>