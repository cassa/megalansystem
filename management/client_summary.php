<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();									// Start/resume THIS session

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "Client Summary | MegaLAN";		// Declare this page's Title
	include("../includes/template.php"); 					// Include the template page
	include("../includes/conn.php"); 	

	if (!isset($_SESSION['username'])) 
	{
		header('Location: home.php');
	}
?>

<!-- //******************************************************

// Name of File: client_summary.php
// Revision: 1.0
// Date: 16/04/2012
// Author: Quintin M
// Modified: L.Smith.
// Modified: Quintin M 04/05/2012

//***********************************************************
//******** Start of Client Summary PAGE ************ -->

<head></head>
<body>
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content">
	<h1>Registration Summary</h1>




<table width="400" border="0" cellpadding="5px">
<form name="update" method="POST" action="user_update.php">
<?php
	// GET [this] CLIENTS DETAILS
	$query = "SELECT * FROM client WHERE clientID='".$_SESSION['userID']."'";
	$result = $db->query($query);
		
	while($row = $result->fetch_array(MYSQLI_BOTH))
	{
		echo '<tr><td width="100px">Username: </td><td><input type="text" name="username" value="'.$row['username'].'" /></td></tr>';
					
		echo '<tr><td>Name: </td><td><input type="text" name="first_name" value="'.$row['first_name'].'" /></td></tr>';
		
		echo '<tr><td>Surname: </td><td><input type="text" name="last_name" value="'.$row['last_name'].'" /></td></tr>';
		
		echo '<tr><td>Mobile #: </td><td><input type="text" name="mobile" value="'.$row['mobile'].'" /></td></tr>'; 
		
		echo '<tr><td>Email: </td><td><input  type="text" name="email" value="'.$row['email'].'" /></td></tr>'; 
		echo '<input type="hidden" name="clientID" value="'.$row['clientID'].'" />';
	}
	echo '<tr><td colspan="2" align="center"><input type="submit" value="Update"></td></tr>';

	// $_SESSION['clientID'] = $row['clientID'];
	$result->close();
?>
</table>
</form>


	<hr /><br />


	<table width="400px" border="1">
	<th>
		<td>Event Name</td>
		<td>Paid (Yes/No)</td>
	</th>
<?php 
	$query = "SELECT e.event_name, a.paid_Admission FROM (event e INNER JOIN attendee a ON e.eventID = a.eventID) WHERE a.clientID = '".$_SESSION['userID']."'";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);

	echo '<tr>';
		//echo '<td>Event Registrations Active: </td>';
		echo '<td colspan="2">'.$row['event_name'].'</td>';
		echo '<td>'.$row['paid_Admission'].'</td>';
	echo '</tr>';

	$result->close();
?>
</table>







<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

</div><!-- end of: Content -->


<!-- INSERT: rightPanel -->
<?php include('../includes/rightPanel.html'); ?>


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