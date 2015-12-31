<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$orecord = array();
	$urecord = array();
	$olrecord = array();
	$cname = array();
	$subtotal = 0;
	$date = date("d/m/Y");
	
	if($user = $db->query("SELECT * FROM user WHERE status = 1"))
	{
		if($user->num_rows)
		{
			while($row2 = $user->fetch_object())
			{
				$urecord[] = $row2;
			}
			$user->free();
		}
		foreach($urecord as $u)
		{
			$user_id		= escape($u->id);
			$name			= escape($u->first_name);
			$address		= escape($u->address);
			$city			= escape($u->city);
			$postal_code	= escape($u->postal_code);
		}
	}
	
	if($order = $db->query("SELECT * FROM orders WHERE user_id = $user_id"))
	{
		if($order->num_rows)
		{
			while($row2 = $order->fetch_object())
			{
				$orecord[] = $row2;
			}
			$order->free();
		}
		else
		{
			?>
			<script>
				alert("You have not yet ordered anything");
			</script>
			<?php
		}
	}
?>
<html>
	<head>
		<title>My Orders</title>
		<link href="Styling.css" type="text/css" rel="stylesheet" />
	</head>

	<body>
		<div id = "outerDiv" style="background-image: url('images/wood.jpg');">
			<div id = "innerDivTop">
				<table>
					<tr>
						<td>
							<a href = "Home.php">
								<div id = "logoDiv" style="background-image: url('images/Cutters.png');"></div>
							</a>
						</td>
						<td width = "970">
							<table id = "topTable" align = "right">
								<tr height = "5px">
									<td width = "40px"><p align = "center"><a href = "Home.php">Home</a></p></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id = "innerDivCenter">
				<div id = "innerInnerDivCenter">
					<table id = "cartTable" width = "800px" align = "center" >
						<tr border = "1">
							<td align = "right" ><?php echo $date; ?></td>
						</tr>
						<table id = "innerCartTableConfirm" width = "800px" align = "center">
							<tr>
								<td id = "shipRow" valign = "top" width = "90px">Ship To</td>
								<td>
									<table>
										<tr>
											<td><?php echo $name; ?></td>
										</tr>
										<tr>
											<td><?php echo $address; ?></td>
										</tr>
										<tr>
											<td><?php echo $city; ?></td>
										</tr>
										<tr>
											<td><?php echo $postal_code; ?></td>
										</tr>
									</table>
								</td>
								<td></td>
							</tr>
						</table>
					</table>
					
					<hr width = "800px">
					<br>
					
					<table width = "800px" align = "center">
						<thead id = "shipmentTable">
							<tr>
								<th align = "left">These are all your orders</th>
							</tr>
						</thead>
						<tbody>
							<table width = "800px" align = "center">
								<?php
								foreach($orecord as $o)
								{
								?>
									<tr>
										<td width = "200px">Order ID: <?php echo escape($o->order_id); ?></td>
										<td width = "200px">Order Ref: <?php echo escape($o->order_ref); ?></td>
										<!--<td width = "200px">X </td>-->
										<td align = "right" width = "200px">R <?php echo escape($o->total); ?></td>
									</tr>
								<?php
									$temp = 0;
									$temp = escape($o->total);
									$subtotal += $temp;
								}
								?>
							</table>
						</tbody>
					</table>
					
					<br>
					<hr width = "800px">
					<br>
					
					<table width = "800px" align = "center">
						<tr>
							<td width = "600px"></td>
							<td align = "right">Subtotal:</td>
							<td width = "100px" align = "right">R <?php echo $subtotal; ?></td>
						</tr>
					</table>
					
					<br>
					<hr width = "800px">
			
				</div>
			</div>
			<div id = "innerDivBottom"></div>
		</div>
	</body>
</html>
<?php

?>