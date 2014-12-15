<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	session_start();								// Start/resume THIS session
	$_SESSION['title'] = "Registration | MegaLAN"; 	// Declare this page's Title
	include("includes/template.php"); 				// Include the template page
	include("includes/conn.php"); 					// Include the database connection


	// SET DEFAULT FIELD VARIABLES
		$firstName = '';
		$lastName = '';
		$email = '';
		$irc = '';
		$mobile = '';
		$irc = '';
		$password = '';
?>


<!-- //******************************************************

// Name of File: registration.php
// Revision: 1.0
// Date: 15/04/2012
// Author: Quintin M
// Modified: Quintin M 26/04/2012

//***********************************************************

//*************** Start of REGISTRATION PAGE ******************* -->

<head>

<script type='text/javascript'>
	var change = 0;

	function updateUsername(email)
	{
		document.getElementById('username').value = email;
	}
	function checkPassword(value)
	{
		if (value.length < 8)
		{
			document.getElementById('passError').style.visibility = 'visible';
 			document.getElementById('passError').src = "/cassa/images/layers/cross.png";
		}
		else
		{
			document.getElementById('passError').src = "/cassa/images/layers/tick.png";
		}
	}
	function checkConPassword(confirm)
	{
		if (confirm != document.getElementById('password').value)
		{
			document.getElementById('conPassError').style.visibility = 'visible';		
			document.getElementById('conPassError').src = "/cassa/images/layers/cross.png";
		}
		else if (confirm.length < 1)
		{
			document.getElementById('conPassError').style.visibility = 'visible';
			document.getElementById('conPassError').src = "/cassa/images/layers/cross.png";
		}
		else
		{
			document.getElementById('conPassError').src = "/cassa/images/layers/tick.png";
		}
	}
	function randomQuestion()
	{
		var q = new Array();
		q[0] = "What is Gordon Freeman's first name?";
		q[1] = "Is Luigi Mario's brother? (yes/no)";
		q[2] = "Does CASSA stand for \n'Computer and Security Student Abomination'? (yes/no)";
	
		var random = Math.floor(Math.random()*3);
		
		if (random == 0)
		{
			var answer = prompt(q[0]).toLowerCase();
			if (answer == 'gordon')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else if (random == 1)
		{
			var answer = prompt(q[1]).toLowerCase();
			if (answer == 'yes' || answer == 'y')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
		else if (random == 2)
		{
			var answer = prompt(q[2]).toLowerCase();
			if (answer == 'no' || answer == 'n')
			{
				return true;
			}
			else
			{
				return false;
			}
		}
	}
</script>

</head>
<body>



<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>MegaLAN Registration</h1>



	<br />



	<!-- FORM: Registration -->
	<table id='registrationTable' border='0' width='700px' cellspacing='3px'>
	<form name='registration' method='POST' onsubmit='return randomQuestion()' action='register.php'>

	<?php if (isset($_SESSION['regError']))
	{
		?>
		<tr>
			<td colspan='3' align='left'>
				<font class='error'><?php echo $_SESSION['regError']; unset($_SESSION['regError']); ?></font>
			</td>
		</tr>
		<?php 
	}?>

	<tr><td colspan='3' align='left'><b>Participant Details</b></td></tr>
	<tr>
		<td width='120px' align='right'>First Name</td>
		<td width='250px'><input type='text' name='firstName' 
			value='<?php echo $firstName; ?>' size='30' maxlength='32' /></td>
		<td width='330px' class='inLeft'>
			<?php if (isset($_SESSION['errMsg'][0])) echo $_SESSION['errMsg'][0]; ?></td>
	</tr>

	<tr>
		<td width='150px' align='right'>Last Name</td>
		<td><input type='text' name='lastName' 
			value='<?php echo $lastName; ?>' size='30' maxlength='32' /></td>
		<td class='inLeft'><?php if (isset($_SESSION['errMsg'][1])) echo $_SESSION['errMsg'][1]; ?></td>
	</tr>

	<tr>
		<td width='150px' align='right'>Email</td>
		<td><input type='text' name='email' 
				   value='<?php echo $email; ?>' size='30' maxlength='256' 
				   onkeyup='updateUsername(this.value)' />
				   <?php if (isset($_SESSION['errMsg'][7]))  echo $_SESSION['errMsg'][7]; ?>
		</td>
		<td class='inLeft'><?php if (isset($_SESSION['errMsg'][2]))  echo $_SESSION['errMsg'][2]; ?></td>
	</tr>
	
	<tr>
		<td width='150px' align='right'>IRC Nickname</td>
		<td><input type='text' name='irc' 
			value='<?php echo $irc; ?>' size='30' maxlength='32' /></td>
	</tr>

	<tr>
		<td width='150px' align='right'>Mobile</td>
		<td><input type='text' name='mobile' 
			value='<?php echo $mobile; ?>' size='30' maxlength='10' /></td>
		<td class='inLeft'><?php if (isset($_SESSION['errMsg'][3])) echo $_SESSION['errMsg'][3]; ?></td>
	</tr>

	<tr>
		<td width='150px' align='right'>IRC</td>
		<td><input type='text' name='irc' 
			value='<?php echo $irc; ?>' size='30' maxlength='64' /></td>
		<td class='inLeft'>&nbsp;</td>
	</tr>

	<tr>
		<td width='150px' align='right'>Select Type</td>
		<td>
			<select name='userType'>
				<option value='0' selected='selected'>Non Member</option>
				<option value='1'>CASSA Member</option>		
			</select> <font size='1'><a href='http://www.cassa.org.au/payments/membership/'>(CASSA member?)</a></font>
		</td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td width='150px' align='right'>Username</td>
		<td><input type='text' class='muteInput' name='username' id='username' size='30' 
			maxlength='32' readonly='readonly' value='<?php echo $email; ?>' /></td>
		<td>&nbsp;</td>
	</tr>

	<tr>
		<td width='150px' align='right'>Password</td>
		<td><input type='password' name='password' id='password' size='30' 
			maxlength='30' onkeyup='checkPassword(this.value)' value='<?php echo $password; ?>' />
			<?php if (isset($_SESSION['errMsg'][5])) echo $_SESSION['errMsg'][5]; ?> 
			<img id='passError' src='' border='0' style='visibility: hidden;' />
		</td>
		<td class='inLeft'><?php if (isset($_SESSION['errMsg'][4])) echo $_SESSION['errMsg'][4]; ?></td>
	</tr>

	<tr>
		<td width='150px' align='right'>Re-enter Password</td>
		<td><input type='password' name='passwordConfirm' id='passwordConfirm' size='30' maxlength='30' onkeyup='checkConPassword(this.value)' /> 
			<img id='conPassError' src='' border='0' style='visibility: hidden;' />
		</td>
		<td class='inLeft'><?php if (isset($_SESSION['errMsg'][6])) echo $_SESSION['errMsg'][6]; ?></td>
	</tr>
	
	<?php if (isset($_SESSION['errMsg'])) unset($_SESSION['errMsg']); ?>

	<tr><td colspan='3' align='center'><br />
		<input type='submit' name='submit' value='  Register  ' />
	</td></tr>



	</form>
	</table><!-- end of: FORM Registration -->






<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

</div><!-- end of: Content -->


<!-- INSERT: rightPanel -->
<?php include('includes/rightPanel.html'); ?>


<!-- INSERT: footer -->
<div id="footer">
	<?php include('includes/footer.html'); ?>
</div>


</div><!-- end of: Shell -->

</center>

<?php

// REGISTRATION FORM SUBMISSION
if (isset($_POST['submit']))
{
// SECURE AND ASSIGN POST VARIABLES 
	// TRIM all posted values
	$_POST = array_map('trim', $_POST);
	
	// REJECT all real escape strings (security)
	$_POST = array_map('mysql_real_escape_string', $_POST);

// SET REGISTRATION VARIABLES
	$firstName = $_POST['firstName'];
	$lastName = $_POST['lastName'];
	$email = htmlspecialchars($_POST['email']);

	$mobile = $_POST['mobile'];
	$irc = $_POST['irc'];
	$userType = $_POST['userType'];

	$username = $email;
	$irc= $_POST['irc'];
	$password = $_POST['password'];
	$passwordConfirm = $_POST['passwordConfirm'];

// CHECK IF ANY INPUT ARE EMPTY OR DO NOT COMPLY
	if ($firstName == '')
	{
		$_SESSION['errMsg'][0] = '<font class="error">This field cannot be blank</font>';
	}
		else if (regLetters($firstName) == false)
		{
			$_SESSION['errMsg'][0] = '<font class="error">Name must only contain letters</font>';
		}
	if ($lastName == '')
	{
		$_SESSION['errMsg'][1] = '<font class="error">This field cannot be blank</font>';
	}
	if ($email == '' || (filter_var($email,FILTER_VALIDATE_EMAIL) == false))
	{
		$_SESSION['errMsg'][2] = '<font class="error">Invalid Email</font>';
	}
	if ($mobile == '')
	{
		$_SESSION['errMsg'][3] = '<font class="error">This field cannot be blank</font>';
	}
		else if (!is_numeric($mobile))
		{
			$_SESSION['errMsg'][3] = '<font class="error">Mobile must only contain numbers</font>';
		}
		else if (strlen($mobile) < 10)
		{
			$_SESSION['errMsg'][3] = '<font class="error">Mobile must be 10 digits long</font>';
		}
	if ($irc == '')
	{
		$_SESSION['errMsg'][3] = '<font class="error">This field cannot be blank</font>';
	}
	if ($password == '')
	{
		$_SESSION['errMsg'][4] = '<font class="error">This field cannot be blank</font>';
	}
		else if (strlen($password) < 8)
		{
			$_SESSION['errMsg'][5] = '<font class="error">Minimum 8 characters</font>';
		}
	if ($passwordConfirm != $password)
	{
		$_SESSION['errMsg'][6] = '<font class="error">Does not match</font>';
	}
// ^^^ end of empty checking


// CHECK IF EMAIL EXISTS
	$check = "SELECT * FROM client WHERE email = '".$email."'";
	$result = $db->query($check);

	if ($result->num_rows > 0)
	{
		$_SESSION['errMsg'][7] = '<font class="error">Email already exists in our system</font>'; 
	}
// IF NO ERRORS, ADD TO DATABASE
	if (!isset($_SESSION['errMsg']))
	{
		// INSERT TO DATABASE
		/*$stmt = $db->prepare("INSERT INTO client (clientID, username, password, first_name, last_name, mobile, email) VALUES (null, ?, ?, ?, ?, ?, ?");
		$stmt->bind_param('ssssss', $email, $password, $first_name, $last_name, $mobile, $email);
		$stmt->execute();
		$stmt->close();*/

		$insert = "INSERT INTO client (username, password, first_name, last_name, irc, mobile, email) VALUES ('".$email."', '".$password."', '".$firstName."', '".$lastName."', '".$irc."', '".$mobile."', '".$email."')";
		$result = $db->query($insert);

		// ALERT CLIENT
		$message = 'You have succesfully registered into our system.\n';
		$message .= 'Please write down your login details\n\n';
		$message .= 'Username: '.$email.'\n';
		$message .= 'Password: '.$password;
		echo "<script>alert('".$message."'); window.location.href='home.php';</script>";	
	}
}
?>

</body>
</html>

<!-- ********************************* -->
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->