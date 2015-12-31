<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$records = array();
	$crecords = array();
	$oidrecords = array();
	
	$urecord = array();
	$tempName = "";
	$cigar_id = 0;
	$quantity = 0;
	$available = 0;
	$balance = 0;
	$tempName = "User";
	
	if(!empty($_POST))
	{
		if($_POST['btnLogout'] == 'Log out')
		{
			$updateEveryone = $db->query("UPDATE user SET status = 0");
		}
		if(isset($_POST['cigar_id'], $_POST['quantity'], $_POST['price']))
		{
			$cigar_id = trim($_POST['cigar_id']);
			$quantity  = trim($_POST['quantity']);
			$price = trim($_POST['price']);
			$available = trim($_POST['avail_units']);
			$total = $price * $quantity;
			//echo $available;
			$my_oid = 1;
			$my_proc = 1;
			
			if(!empty($cigar_id) && !empty($quantity) && !empty($price))
			{
				$order_id = $db->query("SELECT order_id, in_process FROM order_line");
				if($order_id->num_rows)
				{
					while($row = $order_id->fetch_object())
					{
						$crecords[] = $row;
					}
					
					foreach($crecords as $cr)
					{
						$my_oid = 0;
						$my_proc = 0;
						
						$my_oid = $cr->order_id;
						$my_proc = $cr->in_process;
					}
					if($my_proc == 0)
					{
						$my_proc = 1;
						$my_oid += 1;
					}
					
					$order_id->free();
				}
				
				if($available >= $quantity)
				{
					if($order_line = $db->query("SELECT * FROM order_line WHERE in_process = 1"))
					{
						if($order_line->num_rows)
						{
							while($row = $order_line->fetch_object())
							{
								$oidrecords[] = $row;
							}
							$order_line->free();
						}
						foreach($oidrecords as $oid)
						{
							if($cigar_id == $oid->cigar_id)
							{
								$tempID = escape($oid->cigar_id);
								$tempCurQuant = escape($oid->quantity);
							}
						}
					}
					if($tempID == $cigar_id)
					{
						$newQuant = $tempCurQuant + $quantity;
						if($available >= $quantity)
						{
							$newBalance = $available - $quantity;
							$updateSecondQuantity = $db->query("UPDATE cigar SET units = $newBalance WHERE id = $tempID");
							$updateLine = $db->query("UPDATE order_line SET quantity = $newQuant WHERE cigar_id = $tempID AND in_process = 1");
						}
						else
						{
							?>
							<script>
								alert("Specified amount exceeds available units");
							</script>
							<?php
						}
						
					}
					else
					{
						$balance = $available - $quantity;
						$updateQuantity = $db->query("UPDATE cigar SET units = $balance WHERE id = $cigar_id");
						
						$insert = $db->prepare("INSERT INTO order_line(order_id, cigar_id, quantity, price, in_process) VALUES(?,?,?,?,?)");
						$insert->bind_param('iiidi', $my_oid, $cigar_id, $quantity, $total, $my_proc);
						
						if($insert->execute())
						{
							header('Location: MyCart.php');
							die();
						}
					}
					
				}
				else
				{
					?>
					<script>
						alert("Specified amount exceeds available units. There are only <?php echo $available; ?> units available");
					</script>
					<?php
				}
			}
		}
	}
	
	if($cigars = $db->query("SELECT * FROM cigar WHERE units >= 1"))
	{
		if($cigars->num_rows)
		{
			while($row = $cigars->fetch_object())
			{
				$records[] = $row;
			}
			$cigars->free();
		}
	}
	
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
			$tempName = escape($u->first_name);
		}
	}
	$counter = 1;
	$change = 0;
?>
<html>
	<head>
		<title>Home</title>
		<link href="Styling.css" type="text/css" rel="stylesheet" />
	</head>
	
	<script type="text/javascript">
		function works(clicked_id)
		{
			alert(clicked_id);
		}
		
		function validNum()
		{
			var x = document.forms["subForm"]["quantity"].value;
			if(isNaN(x))
			{
				alert("Quantity specified is invalid");
				return false;
			}
			else if(x <= 0)
			{
				alert("Please specify a quantity greater than 0");
				return false;
			}
		}
	</script>
	<body>
		<div id = "outerDiv" style="background-image: url('images/wood.jpg');">
			<div id = "innerDivTop">
				<table>
					<tr>
						<td>
							<a href = "Home.php">
							<div id = "logoDiv" style="background-image: url('images/Cutters.png');">
							</div>
							</a>
						</td>
						<td width = "970">
							<table id = "topTable" align = "right">
								<tr height = "5px">
									<td id = "topTableData" width = "50px"><p align = "center"><?php echo $tempName; ?></p></td>
									<td id = "topTableData" width = "60px"><p align = "center"><a href = "LogIn.php">Login</a></p></td>
									<td id = "topTableData" width = "60px"><p align = "center"><a href = "Register.php">Register</a></p></td>
									<td id = "topTableDataCart" width = "70px"><p align = "center"><a href = "MyCart.php">My Cart</a></p></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id = "innerDivCenter">
				<div id = "innerInnerDivCenter">
					<center>
					<?php
						if(!count($records))
						{
							echo 'No records';
						}
						else
						{
					?>
						<table id = "itemsTable" width = "900px">
							<tr height = "200px" width = "900px">
							<?php
								foreach($records as $c)
								{
									if($counter <=4)
									{
							?>
								<td width = "225">
									<table border = "1" align = "center">
											<tr>
												<td>
													<div style = "background-color: black; height: 100px; width: 200px;">
														<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
													</div>
												</td>
											</tr>
											<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price);?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
									</table>
								</td>
							<?php
									$counter += 1;
									}
									else if($counter <= 8 && $change == 0)
									{?>
							</tr>
									<tr height = "200px" width = "900px">
											<td width = "225">
												<table border = "1" align = "center">
													<tr>
												<td>
													<div style = "background-color: black; height: 100px; width: 200px;">
														<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
													</div>
												</td>
											</tr>
											<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
												</table>
											</td>
								<?php
									$counter += 1;
									$change = 1;
									}
									
									else if($counter <= 8 && $change == 1)
									{?>
										<td width = "225">
											<table border = "1" align = "center">
												<tr>
												<td>
													<div style = "background-color: black; height: 100px; width: 200px;">
														<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
													</div>
												</td>
											</tr>
											<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1"></p>
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
											</table>
										</td>
							   <?php
									$counter += 1;
									}
									else if($counter <= 12 && $change == 1)///////////////////////////////////////////////////////////////////////////////////////////////////
									{?>
									</tr>
										<tr height = "200px" width = "900px">
											<td width = "225">
												<table border = "1" align = "center">
													<tr>
														<td>
															<div style = "background-color: black; height: 100px; width: 200px;">
																<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
															</div>
														</td>
													</tr>
													<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
												</table>
											</td>
								<?php
									$counter += 1;
									$change = 2;
									}
									else if($counter <= 12 && $change == 2)
									{?>
										<td width = "225">
											<table border = "1" align = "center">
												<tr>
													<td>
														<div style = "background-color: black; height: 100px; width: 200px;">
															<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
														</div>
													</td>
												</tr>
												<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
											</table>
										</td>
								<?php
									$counter += 1;
									} 
									else if($counter <= 16 && $change == 2)
									{
									?>
										</tr>
										<tr height = "200px" width = "900px">
											<td width = "225">
												<table border = "1" align = "center">
													<tr>
														<td>
															<div style = "background-color: black; height: 100px; width: 200px;">
																<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
															</div>
														</td>
													</tr>
													<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
												</table>
											</td>
								<?php
										$counter += 1;
										$change = 3;
									}
									else if($counter <= 16 && $change == 3)
									{?>
										<td width = "225">
											<table border = "1" align = "center">
												<tr>
													<td>
														<div style = "background-color: black; height: 100px; width: 200px;">
															<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
														</div>
													</td>
												</tr>
												<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
											</table>
										</td>
									<?php
									$counter += 1;	
									}
									 else if($counter <= 20 && $change == 3)
									 {
										 
									 ?>
										</tr>
										<tr height = "200px" width = "900px">
											<td width = "225">
												<table border = "1" align = "center">
													<tr>
														<td>
															<div style = "background-color: black; height: 100px; width: 200px;">
																<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
															</div>
														</td>
													</tr>
													<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
												</table>
											</td>
							<?php
										$counter += 1;
										$change = 4;
									 }
									 else if($counter <= 20 && $change == 4)
									{ ?>
										<td width = "225">
											<table border = "1" align = "center">
												<tr>
													<td>
														<div style = "background-color: black; height: 100px; width: 200px;">
															<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
														</div>
													</td>
												</tr>
												<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
											</table>
										</td>
									<?php
										$counter += 1;
									}
									else if($counter <= 24 && $change == 4)
									{
									 ?>
									 </tr>
										<tr height = "200px" width = "900px">
											<td width = "225">
												<table border = "1" align = "center">
													<tr>
														<td>
															<div style = "background-color: black; height: 100px; width: 200px;">
																<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
															</div>
														</td>
													</tr>
													<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
												</table>
											</td>
							<?php
									$counter += 1;
									$change = 5;
									}
									else if($counter <= 24 && $change == 5)
									{
									?>
									<td width = "225">
											<table border = "1" align = "center">
												<tr>
													<td>
														<div style = "background-color: black; height: 100px; width: 200px;">
															<img src = <?php echo escape($c->image);?> height = "100px" width = "200px";>
														</div>
													</td>
												</tr>
												<form name = "subForm" action = "" method ="post" onSubmit = "return validNum()">
											<tr align = "center">
												<td>
													<input type = "hidden" name = "cigar_id" id = "cigar_id" value = <?php echo escape($c->id); ?>>
													<?php echo escape($c->brand); ?>
												</td>
											</tr>
											<tr align = "center">
												<td><?php echo escape($c->name); ?></td>
											</tr>
											<tr align = "center">
												<td> <p>R <?php echo escape($c->price); ?><input type = "hidden" name = "price" id = "price" value = <?php echo escape($c->price); ?>> </p> </td>
											</tr>
											<tr align = "center">
												<td>
													<p>Quantity <input type = "text" name = "quantity" id = "quantity" size = "1" value = "1">
													<input type = "hidden" name = "avail_units" id = "avail_units" value = <?php echo escape($c->units); ?>>
												</td>
											</tr>
											<tr align = "center">
												<td>
													<input type = "submit" value = "Add to Cart" id = <?php echo escape($c->id); ?>>	
												</td>
											</tr>
											</form>
											</table>
										</td>
							<?php
									$counter += 1;
									}
							}
							?>
										</tr>			
						</table>
						<?php
						}
						?>
					</center>
				</div>
			</div>
			<div id = "innerDivBottom">
				<br>
				<table width = "1190px" align = "center" valign = "center">
					<tr>
						<form name = "subForm" action = "" method ="post">
							<td>
								<a href = "OrderDetails.php"><p>Review my order details</p></a>
							</td>
							<td align = "right">
								<p>
								<?php
									if($tempName == "User")
									{
										?>
											<a href = "LogIn.php"><input type = "button" value = "LogIn" id = <?php echo escape($c->id); ?>></a>
										<?php
									}
									else
									{
										?>
											<input type = "submit" name = "btnLogout" value = "Log out" id = <?php echo escape($c->id); ?>>
										<?php
									}
								?>
								</p>
							</td>
						</form>
					</tr>
					<tr>
						<td>
							<a href = "MyOrders.php"><p>Review my orders</p></a>
						</td>
					</tr>
				</table>
			</div>
		</div>
	</body>
</html>