<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start();							// Start/resume THIS session
	$_SESSION['title'] = 'Login | MegaLAN';
	include("includes/template.php"); 			// Include the template page
	include('includes/conn.php');				// Include database connection settings
?>

<!-- ******************************************************

// Name of File: loginPlus.php
// Revision: 1.0
// Date: 14/05/2012
// Author: Lyndon Smith
// Modified: Quintin Maseyk 15/05/2012

//******************************************************* -->

<head></head>
<body>
<center>
<div id='shell' class='backgroundA'>



<!-- Main Content [left] -->
<div id="content" style='position: relative; left: 250px; top: 50px;'>
	<h1>Login</h1>


<!-- Form: for user login -->
<form name="login" method="POST" onsubmit="return loginValidate();" action="/CASSA/login.php">
<div align='center' style='text-align: left'>
	<table id='loginThickBox' cellspacing='10px'>
		
		<!-- LOGIN ERRORS -->
		<?php 
		if (isset($_SESSION['errMsg']))
		{
			echo '<tr><td colspan="2">'.$_SESSION['errMsg'].'</td></tr>';
		}
		?>

		<tr>
			<td align='right'>Username </td>
			<td align='left'><input type="text" width="40px" name="username" maxlength='32' /></td>
		</tr>

		<tr>
			<td align='right'>Password </td>
			<td align='left'><input type="password" width="40px" name="password" maxlength='32' /></td>
		</tr>

		<tr>
			<td colspan='2'><a href='register.php'>Click here to register</a>
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
			<input type="submit" name='submit' value="Login" /></td>
		</tr>
	</table>
</div>
</form>




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
</body>
</html>

<!-- ********************************* -->
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->