<?php
class m_update_db{
	private $_table		= "update_db";
	private $_id_table	= "id_update";
	private	$_order		= "last_id";
	private	$_relation	= "";  
	
	function __construct(){
		
	}

/*---------------------------------------------------------------------------------  
					Ultimo ID actualizado             
---------------------------------------------------------------------------------*/

	function getLastID(){
		$sql = "
		SELECT 
			MAX(last_id) as ultimo
		FROM 
			`$this->_table` ";   

		$query = mysql_query($sql) or die(mysql_error());
		
		return $query;
	}
	
/*---------------------------------------------------------------------------------  
					Insert de update            
---------------------------------------------------------------------------------*/	
	
	function insert($datos){
		$insert = 
			"INSERT INTO `$this->_table`(
				`date_add`, 
				`cantidad_registros`, 
				`last_id`
			) VALUES (
				'$datos[date_add]',
				'$datos[cantidad_registros]',
				'$datos[last_id]'
			)";
		mysql_query($insert) or die(mysql_error());
	}
	
}
?>