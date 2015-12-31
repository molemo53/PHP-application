<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$records = array();
	$name = array();
	$delArray = array();
	$count = 0;
	$total = 0;
	$temp = "";
	$urecord = array();
	$crecord = array();
	$orecord = array();
	$tempName = "User";
	$newTotal = 0;
	$user = 0;
	
	
	if(isset($_POST))
	{
		foreach($_POST['isCheck'] as $myA)
		{
			$delArray[] = $myA;
		}
		$cig_id = $_POST['cigars_id'];
		$my_oid  = $_POST['orders_id'];
		$total = $_POST['total_cost'];
		$quantity = $_POST['quant'];
		$user = $_POST['user_id'];
		
		$date1 = date("d/m/Y");
		$dTemp = strtotime("+7 day");
		$date2 = date('d/m/Y', $dTemp);

		$ref = 'REF_';
		$order_ref = $ref . $my_oid . $user . $quantity;
		
		if($_POST['btnAction'] == 'Remove From Cart')
		{	
			foreach($delArray as $dCigID)
			{
				if($getQuant = $db->query("SELECT * FROM cigar WHERE id = $dCigID"))
				{
					if($getQuant->num_rows)
					{
						while($row2 = $getQuant->fetch_object())
						{
							$crecord[] = $row2;
						}
						$getQuant->free();
					}
					foreach($crecord as $c)
					{
						$tempQuant = escape($c->units);
					}
					
					if($getCur = $db->query("SELECT * FROM order_line WHERE cigar_id = $dCigID"))
					{
						if($getCur->num_rows)
						{
							while($row2 = $getCur->fetch_object())
							{
								$orecord[] = $row2;
							}
							$getCur->free();
						}
						foreach($orecord as $c)
						{
							$tempCurQuant = escape($c->quantity);
						}
					}			
					$newTotal = $tempQuant + $tempCurQuant;
					$updateQuantity = $db->query("UPDATE cigar SET units = $newTotal WHERE id = $dCigID");
					$remove = $db->query("DELETE FROM order_line WHERE cigar_id = $dCigID");
				}
			}
		}
		else if($_POST['btnAction'] == 'Check Out')
		{
			if($user != 0)
			{
				$orderInsert = $db->query("INSERT INTO orders(order_id, user_id, order_date, delivery_date, order_ref, total) VALUES ('{$my_oid}', '{$user}', '{$date1}', '{$date2}', '{$order_ref}', '{$total}')");
				$updateLine = $db->query("UPDATE order_line SET in_process = 0");
				
				header('Location: Confirmation.php');
				die();
			}
			else
			{
				?>
				<script>
					alert("You have to be logged in to check out");
				</script>
				<?php
			}
			
		}
		
		
	}
	
	if($order_line = $db->query("SELECT * FROM order_line WHERE in_process = 1"))
	{
		if($order_line->num_rows)
		{
			while($row = $order_line->fetch_object())
			{
				$records[] = $row;
			}
			$order_line->free();
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
			
			foreach($urecord as $u)
			{
				$tempName = escape($u->first_name);
				$user = escape($u->id);
			}
		}
		else
		{
			
		}
	}
?>

<html>
	<head>
		<title>My Cart</title>
		<link href="Styling.css" type="text/css" rel="stylesheet" />
	</head>
	
	<script type="text/javascript">
		var idArray = new Array();
		
		function isChecked(checked_id)
		{
			var temp1 = document.getElementById(checked_id);
			if(temp1.checked)
			{
				idArray.push(checked_id);
			}
			else
			{
				for(var i = 0; i < idArray.length; i++)
				{
					if(idArray[i] == checked_id)
					{
						idArray.splice(i, 1);
						break;
					}
				}
			}
		}
		
		function removeFromCart()
		{
			var value = 0;
			if(idArray.length == 0)
			{
				alert("First select an item to be removed");
				return false;
			}
			else
			{
				for(var j = 0; j < idArray.length; j++)
				{
					value = idArray[j];
				}
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
								<div id = "logoDiv" style="background-image: url('images/Cutters.png');"></div>
							</a>
						</td>
						<td width = "970">
							<table id = "topTable" align = "right">
								<tr height = "5px">
									<td id = "topTableData" width = "50px"><p align = "center"><?php echo $tempName; ?></p></td>
									<td id = "topTableData" width = "60px"><p align = "center"><a href = "LogIn.php">Login</a></p></td>
									<td id = "topTableData" width = "60px"><p align = "center"><a href = "Register.php">Register</a></p></td>
									<td id = "topTableDataCart" width = "70px"><p align = "center">My Cart</p></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id = "innerDivCenter">
				<div id = "innerInnerDivCenter">
					<?php
					if(!count($records))
					{
						?> <br><h3 align = "center">No items in cart</h3> <?php
					}
					else
					{
					?>
						<table id = "cartTable" width = "800px" align = "center" >
							<table id = "innerCartTable" width = "800px" border = "1" align = "center">
								<thead>
									<tr>
										<th width = "20">Select</th>
										<th width = "600px" align = "left">Name</th>
										<th width = "60px">Quantity</th>
										<th align = "left">Price</th>
									</tr>
								</thead>
								
								<tbody>
									<?php
									foreach($records as $r)
									{
									?>
										<form name = "subForm" action = "" method ="post" >
											<tr>
												<td align = "center">
													<input type = "checkbox" name = "isCheck[]" value = <?php echo escape($r->cigar_id) ?> id = <?php echo escape($r->cigar_id) ?> onClick = "isChecked(this.id)">
													<input type = "hidden" name = "cigars_id" id = "cigars_id" value = <?php echo escape($r->cigar_id); ?>>
													<input type = "hidden" name = "orders_id" id = "orders_id" value = <?php echo escape($r->order_id); ?>>
													
												</td>
												<td>
													<?php
														$temp = escape($r->cigar_id);

														if($cigar_name = $db->query("SELECT name FROM cigar WHERE id = $temp"))
														{
															if($cigar_name->num_rows)
															{
																while($row = $cigar_name->fetch_object())
																{
																	$name[] = $row;
																}
																$cigar_name->free();
															}
														}
														foreach($name as $cName)
														{
															echo(escape($cName->name));
															$cName->name = "";
														}
													?>
												</td>
												<td align = "center">
													<?php echo escape($r->quantity) ?>
												</td>
												<td align = "right">
													<?php echo escape($r->price) ?>
												</td>
											</tr>
									<?php
											$totalA += $r->price;
											$totalB += $r->price;
											
											$quant += $r->quantity;
											$count += 1;
									}
									?>
								</tbody>
							</table>
						</table>
					<?php
					}
					?>	
						
											<br>
											
											<table id = "totalTable" width = "800px" border = "1" align = "center">
												<tr>
													<td width = "20">
														<b>Total</b>
													</td>
													<td>
														<p align = "right">R <?php echo $totalA?></p>
														<input type = "hidden" name = "total_cost" id = "total_cost" value = <?php echo $totalB ?>>
														<input type = "hidden" name = "quant" id = "quant" value = <?php echo $quant ?>>
													</td>
												</tr>
											</table>
											
											<br>
											
											<table id = "buttonTable" width = "800px" align = "center">
												<tr>
													<td>
														<input type = "submit" name = "btnAction" value = "Remove From Cart" onClick = "removeFromCart()">
														<input type = "submit" name = "btnAction" value = "Check Out">
													</td>
												</tr>
											</table>
										</form>
				</div>
			</div>
			<div id = "innerDivBottom"></div>
		</div>
	</body>
</html>