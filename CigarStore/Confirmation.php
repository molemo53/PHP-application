<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$orecord = array();
	$urecord = array();
	$olrecord = array();
	$cname = array();
	
	if($order = $db->query("SELECT * FROM orders"))
	{
		if($order->num_rows)
		{
			while($row2 = $order->fetch_object())
			{
				$orecord[] = $row2;
			}
			$order->free();
		}
		foreach($orecord as $o)
		{
			$userID 	= escape($o->user_id);
			$orderID 	= escape($o->order_id);
			$orderRef 	= escape($o->order_ref);
			$total 		= escape($o->total);
			$today		= escape($o->order_date);
		}
	}
	
	if($user = $db->query("SELECT * FROM user WHERE id = $userID"))
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
			$name			= escape($u->first_name);
			$address		= escape($u->address);
			$city			= escape($u->city);
			$postal_code	= escape($u->postal_code);
		}
	}
	
	if($order = $db->query("SELECT * FROM order_line WHERE order_id = $orderID"))
	{
		if($order->num_rows)
		{
			while($row2 = $order->fetch_object())
			{
				$olrecord[] = $row2;
			}
			$order->free();
		}
	}
?>
<html>
	<head>
		<title>Confirmation</title>
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
							<td align = "right" ><?php echo $today; ?></td>
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
								<th align = "left">Items in this shipment</th>
							</tr>
						</thead>
						<tbody>
							<table width = "800px" align = "center">
								<?php
								foreach($olrecord as $ol)
								{
									$temp = escape($ol->cigar_id);
									$tempName = "";
									if($cigar_name = $db->query("SELECT name FROM cigar WHERE id = $temp"))
									{
										if($cigar_name->num_rows)
										{
											while($row = $cigar_name->fetch_object())
											{
												$cname[] = $row;
											}
											$cigar_name->free();
										}
									}
									foreach($cname as $cigName)
									{
										$tempName = escape($cigName->name);
										$cigName->name = "";
									}
								?>
									<tr>
										<td width = "265px"><?php echo $tempName; ?></td>
										<td width = "265px">X <?php echo escape($ol->quantity); ?></td>
										<td align = "right" width = "265px">R <?php echo escape($ol->price); ?></td>
									</tr>
								<?php
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
							<td align = "right">Suntotal:</td>
							<td width = "100px" align = "right">R <?php echo $total; ?></td>
						</tr>
						<tr>
							<td width = "600px"></td>
							<td align = "right">Shipping:</td>
							<td width = "100px" align = "right">R 0</td>
						</tr>
						<tr>
							<td width = "600px"></td>
							<td align = "right">Balance:</td>
							<td width = "100px" align = "right">R <?php echo $total; ?></td>
						</tr>
					</table>
					
					<br>
					<hr width = "800px">
					<br>
					
					<p id = "refString">To track your shipment use <?php echo $orderRef;?> as your reference code.</p>
					<p id = "refString">Your shipment will be dispatched within the next Seven days.</p>
					<p id = "refString">Thank you for shopping with Cutters. Where it all go's up in smoke</p>
					
				</div>
			</div>
			<div id = "innerDivBottom"></div>
		</div>
	</body>
</html>