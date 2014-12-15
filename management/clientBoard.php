<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start();										// Start/resume THIS session
	$_SESSION['title'] = "Client Home | MegaLAN"; 			// Declare this page's Title

	// PAGE SECURITY
	if (!isset($_SESSION['isAdmin']))
	{
		//if ($_SESSION['isAdmin'] != 0)
		//{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		//}
	}

	include("../includes/template.php"); 				// Include the template page
	include("../includes/conn.php"); 					// Include the database connection
?>


<!-- //******************************************************

// Name of File: clientBoard.php
// Revision: 1.0
// Date: 14/04/2012
// Author: Quintin M
// Modified: 

//***********************************************************

//******** Start of CLIENT BOARD PAGE ******************* -->

<head></head>
<body>
<center>
<div id='shell'>



<table class='boardDIV' id='clientDIV' align='center' border='0'>
<tr>
	<td width='100px;'>&nbsp;</td>
	<td>
		<!-- MANAGE DETAILS -->
		<a href='MANdetails.php'>
			<img class='boardButton' src='../images/buttons/details.jpg' title='Manage your details' 
				onmouseover='this.src="../images/buttons/detailsO.jpg"' 
				onmouseout='this.src="../images/buttons/details.jpg"' />
		</a>
	</td>
	<td>
		<!-- EVENT REGISTRATION -->
		<a href='eventRegistration.php'>
			<img class='boardButton' src='../images/buttons/eventReg.jpg' title='Event registration'
				onmouseover='this.src="../images/buttons/eventRegO.jpg"' 
				onmouseout='this.src="../images/buttons/eventReg.jpg"' />
		</a>
	</td>
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
