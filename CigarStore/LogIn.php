<?php
	error_reporting(0);
	require 'connections/connection.php';
	require 'functions/security.php';
	$temp = "";
	
	$urecord = array();
	$tempName = "User";
	
	if(!empty($_POST))
	{
		if(isset($_POST['email'], $_POST['password']))
		{
			$email 		= trim($_POST['email']);
			$password 	= trim($_POST['password']);
			if(!empty($email) && !empty($password))
			{
				//echo 'Inside empty <br>';
				if($user_email = $db->query("SELECT password FROM user WHERE email = '$email'"))
				{
					//echo 'Inside select <br>';
					
					if($user_email->num_rows)
					{
						//echo 'Inside num rows <br>';
						while($row = $user_email->fetch_object())
						{
							$name[] = $row;
						}
						$user_email->free();
					}
					
					foreach($name as $eName)
					{
						//echo $eName->password, '<br>';
						$temp = trim(escape($eName->password));
						$eName->password = "";
					}
					if($password === $temp)
					{
						$updateEveryone = $db->query("UPDATE user SET status = 0");
						$updateStatus = $db->query("UPDATE user SET status = 1 WHERE email = '$email'");
						echo 'Yay';
					}
					else
					{
						?>
						<script>
							alert("The email address or password entered is incorrect");
						</script>
						<?php
					}
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
		<title>LogIn</title>
		<link href="Styling.css" type="text/css" rel="stylesheet" />
	</head>

	<body>
		<div id = "outerDivLog" style="background-image: url('images/wood.jpg');">
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
									<td id = "topTableData" width = "60px"><p align = "center">Login</p></td>
									<td id = "topTableData" width = "60px"><p align = "center"><a href = "Register.php">Register</a></p></td>
									<td id = "topTableDataCart" width = "70px"><p align = "center"><a href = "MyCart.php">My Cart</a></p></td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</div>
			<div id = "innerDivCenterLog">
				<div id = "innerInnerDivCenterLog">
					<br>
					<h3 id = "logHead">Existing users</h3>
					<hr width = "400px">
					<form action = "" method = "post">
						<table width = "400px" align = "center">
							<tr>
								<td width = "150px">My email address:</td>
							</tr>
							<tr>
								<td><input type = "text" size = "25" name = "email" autocomplete = "off"></td>
							</tr>
							<tr height = "40px">
								<td width = "150px" valign = "bottom">My password:</td>
							</tr>
							<tr>
								<td><input type = "password" size = "25" name = "password" autocomplete = "off"></td>
							</tr>
						</table>
						
						<br>
						
						<table width = "400px" align = "center">
							<tr>
								<td>
									<input type = "submit" id = "login" value = "Login">
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