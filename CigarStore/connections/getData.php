<?php
	require 'connection.php';
	
	$mysql_host = 'localhost';
	$mysql_user = 'root';
	$mysql_password = '';
	$mysql_dbname = 'webstore';
	$mysql_con = mysqli_connect($mysql_host, $mysql_user, $mysql_password, $mysql_dbname);
	
	
	$query_all = "SELECT * FROM `products`";
	
	if($is_true = mysqli_query($mysql_con, $query_all))
	{
		echo "Query executed<br>";
		
		while($query_data = mysqli_fetch_assoc($is_true))
		{
			echo $query_data['prod_brand'].'<br>';
		}
	}
	else
	{
		echo "Query not executed";
	}

?>