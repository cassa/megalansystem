<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">

<?php 
	session_start();								// Start/resume THIS session

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] != 2)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "CASSA Staff List | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 				// Include the template page
	include("../includes/conn.php"); 					// Include the database connection

	if (isset($_POST['subject']))
	{
		if ($_POST['subject'] == "convertToStaff")
		{
			// CONVERT [this] CLIENT TO A CASSA STAFF MEMBER
			$update = "UPDATE client SET admin=1 WHERE clientID='".$_POST['clientID']."'";
			$result = $db->query($update);
		}
		if ($_POST['subject'] == "convertToClient")
		{
			// CONVERT [this] CLIENT TO A CASSA STAFF MEMBER
			$update = "UPDATE client SET admin=0 WHERE clientID='".$_POST['clientID']."'";
			$result = $db->query($update);
		}
	}
?>

<!-- //******************************************************

// Name of File: MANstaff.php
// Revision: 1.0
// Date: 13/06/2012
// Author: Quintin
// Modified: 

//***********************************************************

//*********** Start of CASSA STAFF PAGE  *************** -->

<head>
	<script type='text/javascript'>
	function convertStaff(clientID)
	{
		document.getElementById('clientID').value = clientID;
		document.getElementById('subject').value = "convertToStaff";
		document.forms['convertStaffForm'].submit();
	}
	function convertClient(clientID)
	{
		document.getElementById('clientID').value = clientID;
		document.getElementById('subject').value = "convertToClient";
		document.forms['convertStaffForm'].submit();
	}
	</script>
</head>
<body>
<center>
<div id='shell'>






<!-- Main Content [left] -->
<div id="content" style='min-height: 600px;'>
	<h1>CASSA Staff</h1>
	<br />






<?php
	if (isset($_SESSION['errMsg']))
	{
		echo $_SESSION['errMsg'];
		unset($_SESSION['errMsg']);
		echo '<br />';
	}
?>






<!-- STAFF LIST -->
<table class='staffList'>
<caption>Staff List</caption>
<tr>
	<th align='left' width='100px'>Surname</th>
	<th align='left' width='100px'>First Name</th>
	<th align='left' width='230px'>Username</th>
	<th align='left' width='100px'>Mobile</th>
	<th align='left' width='230px'>Email</th>
	<th align='left' width='100px'>&nbsp;</th>
</tr>

<?php
// GET ALL CLIENTS (NON STAFF)
$get = "SELECT * FROM client WHERE admin=1 ORDER BY last_name ASC";
$result = $db->query($get);

if ($result->num_rows != 0)
{
	$on = "this.className='rowON'";
	$off = "this.className='rowOFF'";

	for ($i=0; $i<$result->num_rows; $i++)
	{
		$row = $result->fetch_assoc();
		echo '<tr class="rowOFF" onmouseover="'.$on.'" onmouseout="'.$off.'">';
			echo '<td>'.$row['last_name'].'</td>';
			echo '<td>'.$row['first_name'].'</td>';
			echo '<td>'.$row['username'].'</td>';
			echo '<td>'.$row['mobile'].'</td>';
			echo '<td>'.$row['email'].'</td>';
			echo '<td><input type="button" name="convert" value="Convert to Client" onclick="convertClient('.$row['clientID'].')" /></td>';
		echo '</tr>';
	}
}
else
{
	echo '<tr height="50px"><td colspan="6"><i>There are no staff in the system at this time</i></td></tr>';
}
?>
</table>






<br />
<br />






<!-- CLIENT LIST -->
<table class='staffList'>
<caption>Client List</caption>
<tr>
	<th align='left' width='100px'>Surname</th>
	<th align='left' width='100px'>First Name</th>
	<th align='left' width='230px'>Username</th>
	<th align='left' width='100px'>Mobile</th>
	<th align='left' width='230px'>Email</th>
	<th align='left' width='100px'>&nbsp;</th>
</tr>

<?php
// GET ALL CLIENTS (NON STAFF)
$get = "SELECT * FROM client WHERE admin=0 ORDER BY last_name ASC";
$result = $db->query($get);

if ($result->num_rows != 0)
{
	$on = "this.className='rowON'";
	$off = "this.className='rowOFF'";

	for ($i=0; $i<$result->num_rows; $i++)
	{
		$row = $result->fetch_assoc();
		echo '<tr class="rowOFF" onmouseover="'.$on.'" onmouseout="'.$off.'">';
			echo '<td>'.$row['last_name'].'</td>';
			echo '<td>'.$row['first_name'].'</td>';
			echo '<td>'.$row['username'].'</td>';
			echo '<td>'.$row['mobile'].'</td>';
			echo '<td>'.$row['email'].'</td>';
			echo '<td><input type="button" name="convert" value="Convert to Staff" onclick="convertStaff('.$row['clientID'].')" /></td>';
		echo '</tr>';
	}
}
else
{
	echo '<tr height="50px"><td colspan="6"><i>There are no clients in the system at this time</i></td></tr>';
}
?>
</table>






<!-- FORM TO SUBMIT CONVERTION -->
<form name='convertStaffForm' method='post' action='MANstaff.php'>
<input type='hidden' name='clientID' id='clientID' value='' />
<input type='hidden' name='subject' id='subject' value='' />
</form>






<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

</div><!-- end of: Content -->


<!-- INSERT: footer -->
<div id="footer">
	<?php include('../includes/footer.html'); ?>
</div>


</div><!-- end of: Shell -->

</center>
</body>
</html>

<!-- ********************************* -->
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->