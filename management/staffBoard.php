<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start();										// Start/resume THIS session
	$_SESSION['title'] = "Staff Home | MegaLAN"; 			// Declare this page's Title

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	include("../includes/template.php"); 				// Include the template page
	include("../includes/conn.php"); 					// Include the database connection
?>


<!-- //******************************************************

// Name of File: staffBoard.php
// Revision: 1.0
// Date: 29/04/2012
// Author: Quintin M
// Modified: 

//***********************************************************

//******** Start of STAFF BOARD  PAGE ******************* -->

<head></head>
<body>
<center>
<div id='shell'>



<table class='boardDIV' id='staffDIV' align='center' border='0'>
<tr>
	<td width='100px;'>&nbsp;</td>
	<td>
		<!-- MANAGE EVENT -->
		<a href='MANevent.php'>
			<img class='boardButton' src='../images/buttons/event.jpg' title='Manage Event' 
				onmouseover='this.src="../images/buttons/eventO.jpg"' 
				onmouseout='this.src="../images/buttons/event.jpg"' />
		</a>
	</td>
	<td>
		<!-- MANAGE TOURNAMENT -->
		<a href='MANtournament.php'>
			<img class='boardButton' src='../images/buttons/tournament.jpg' title='Manage Tournaments'
				onmouseover='this.src="../images/buttons/tournamentO.jpg"' 
				onmouseout='this.src="../images/buttons/tournament.jpg"' />
		</a>
	</td>
	<td>
		<!-- MANAGE PIZZA MENU -->
		<a href='MANpizza.php'>
			<img class='boardButton' src='../images/buttons/pizza.jpg' title='Manage Pizza'
				onmouseover='this.src="../images/buttons/pizzaO.jpg"' 					onmouseout='this.src="../images/buttons/pizza.jpg"' />
		</a>
	</td>
	<td width='100px;'>&nbsp;</td>
</tr>
<tr>
	<td width='100px;'>&nbsp;</td>
	<td>
		<!-- MANAGE USERS -->
		<a href='MANparticipants.php'>
			<img class='boardButton' src='../images/buttons/user.jpg' title='Manage Users' 
				onmouseover='this.src="../images/buttons/userO.jpg"' 
				onmouseout='this.src="../images/buttons/user.jpg"' />
		</a>
	</td>
	<td>
		<!-- MANAGE PAYMENT -->
		<a href='MANpay.php'>
			<img class='boardButton' src='../images/buttons/pay.jpg' title='Manage Payment' 
				onmouseover='this.src="../images/buttons/payO.jpg"' 
				onmouseout='this.src="../images/buttons/pay.jpg"' />
		</a>
	</td>


	<?php 
	if ($_SESSION['isAdmin'] == 2)
	{
	?>
		<td>
			<!-- CASSA STAFF -->
			<a href='MANstaff.php'>
				<img class='boardButton' src='../images/buttons/staff.jpg' title='CASSA Staff List'
					onmouseover='this.src="../images/buttons/staffO.jpg"' 					onmouseout='this.src="../images/buttons/staff.jpg"' />
			</a>
		</td>
	<?php
	}
	?>
	<td width='100px;'>&nbsp;</td>
</tr>	
<tr><td colspan='5' height='40px'>&nbsp</td></tr>
</table>









<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

<br /><br /><br /><br /><br />


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
