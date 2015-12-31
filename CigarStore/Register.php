<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	
	$urecord = array();
	$tempName = "User";
	
	if(!empty($_POST))
	{
		if(isset($_POST['first_name'], $_POST['last_name'], $_POST['email_address'], $_POST['address'], $_POST['city'], $_POST['postal_code'], $_POST['password_one'], $_POST['password_two']))
		{
			$name 		= trim($_POST['first_name']);
			$surname 	= trim($_POST['last_name']);
			$email 		= trim($_POST['email_address']);
			$address 	= trim($_POST['address']);
			$city 		= trim($_POST['city']);
			$code 		= trim($_POST['postal_code']);
			$passOne 	= trim($_POST['password_one']);
			$passTwo	= trim($_POST['password_two']);
			
			if(!empty($name) && !empty($surname) && !empty($email) && !empty($address) && !empty($city) && !empty($code) && !empty($passOne) && !empty($passTwo))
			{
				if($passOne == $passTwo)
				{
					if(ctype_digit($code))
					{
						$insert = $db->prepare("INSERT INTO user(first_name, last_name, email, address, city, postal_code, password) VALUES(?,?,?,?,?,?,?)");
						$insert->bind_param('sssssis', $name, $surname, $email, $address, $city, $code, $passOne);
						
						if($insert->execute())
						{
							header('Location: Home.php');
							die();
						}
					}
					else
					{
						?>
						<script>
							alert("The postal Code entered is not valid");
						</script>
						<?php
					}
				}
				else
				{
					?>
					<script>
						alert("The passwords entered do not match");
					</script>
					<?php
				}
			}
			else
			{
				?>
				<script>
					alert("Please enter all fields");
				</script>
				<?php
			}
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

?>
<html>
	<head>
		<title>Register</title>
		<link href="Styling.css" type="text/css" rel="stylesheet" />
	</head>
	
	<body>
		<div id = "outerDivReg" style="background-image: url('images/wood.jpg');">
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
									<td id = "topTableData" width = "60px"><p align = "center">Register</p></td>
									<td id = "topTableDataCart" width = "70px"><p align = "center"><a href = "MyCart.php">My Cart</a></p></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id = "innerDivCenterReg">
				<div id = "innerInnerDivCenterReg">
					<h1 id = "regHead">Register</h1>
					<form action = "" method = "post">
						<table width = "800px" align = "center" >
							<tr height = "50px">
								<td width = "150px">First Name</td>
								<td><input type = "text" size = "25" name = "first_name" autocomplete = "off"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">Last Name</td>
								<td><input type = "text" size = "25" name = "last_name" autocomplete = "off"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">Email Address</td>
								<td><input type = "text" size = "25" name = "email_address"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px" valign = "top">Address</td>
								<td><textarea rows = "4" cols = "27px" name = "address"></textarea></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">City</td>
								<td><input type = "text" size = "25" name = "city" autocomplete = "off"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">Postal Code</td>
								<td><input type = "text" size = "25" name = "postal_code" autocomplete = "off"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">Password</td>
								<td><input type = "password" size = "25" name = "password_one" autocomplete = "off"></td>
							</tr>
							<tr height = "50px">
								<td width = "150px">Retype Password</td>
								<td><input type = "password" size = "25" name = "password_two" autocomplete = "off"></td>
							</tr>
						</table>
						
						<br>
						
						<table width = "800px" align = "center">
							<tr>
								<td>
									<input type = "submit" id = "addUser" value = "Register Now">
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