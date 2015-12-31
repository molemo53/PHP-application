<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$orecord = array();
	$urecord = array();
	$olrecord = array();
	$cname = array();
	$subtotal = 0;
	
	if(!empty($_POST))
	{
		if(isset($_POST['order_ref']))
		{
			$tempRef = trim($_POST['order_ref']);
			if($user = $db->query("SELECT * FROM user WHERE status = 1"))
			{
				if($user->num_rows)
				{
					while($row2 = $user->fetch_object())
					{
						$urecord[] = $row2;
					}
					$user->free();
					
					foreach($urecord as $u)
					{
						$user_id		= escape($u->id);
						$name			= escape($u->first_name);
						$address		= escape($u->address);
						$city			= escape($u->city);
						$postal_code	= escape($u->postal_code);
					}
					if(!empty($tempRef))
					{
						if($order = $db->query("SELECT * FROM orders WHERE order_ref = '$tempRef'"))
						{
							if($order->num_rows)
							{
								while($row2 = $order->fetch_object())
								{
									$orecord[] = $row2;
								}
								$order->free();
								
								foreach($orecord as $o)
								{
									$myID = escape($o->order_id);
								}
								
								if($orderl = $db->query("SELECT * FROM order_line WHERE order_id = $myID"))
								{
									if($orderl->num_rows)
									{
										while($row2 = $orderl->fetch_object())
										{
											$olrecord[] = $row2;
										}
										$orderl->free();
									}
								}
							}
							else
							{
								?>
								<script>
									alert("There a no references matching the one entered");
								</script>
								<?php
							}
						}
					}
					else
					{
						?>
						<script>
							alert("Enter a ref id then select search");
						</script>
						<?php
					}
					
				}
				else
				{
					?>
					<script>
						alert("You need to be logged in to search for order details");
					</script>
					<?php
				}
				
			}
		}
	}
?>
<html>
	<head>
		<title>Order Details</title>
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
					<h1 id = "regHead">Find Order</h1>
					<form action = "" method = "post">
						<table width = "800px" align = "center" >
							<tr>
								<td><p>My order ref:</p></td>
							</tr>
							<tr>
								<td><input type = "text" name = "order_ref" size = 25></td>
							</tr>
							<tr>
								<td><input type = "submit" value = "Search" id = "searchRef"></td>
							</tr>
						</table>
					</form>
					
					<hr width = "800px">
					<br>
					
					<table width = "800px" align = "center" border = "1">
						<thead id = "shipmentTable">
							<tr>
								<th align = "left">These are all your items for this order for ref</th>
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
									$tempTotal = 0;
									$tempTotal = escape($ol->price);
									$subtotal += $tempTotal;
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