<?php
	function escape($String)
	{
		return htmlentities(trim($String), ENT_QUOTES, 'UTF-8');
	}
?>