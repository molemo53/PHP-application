<?php

	$db = new mysqli('localhost', 'root', '', 'webstore');
	if($db->connect_errno)
	{
		die('Can not connect to the database.');
	}
?>