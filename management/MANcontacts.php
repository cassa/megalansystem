<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();								// Start/resume THIS session

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "Manage Contact | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 			// Include the template page
	include("../includes/conn.php"); 				// Include the database connection

	if (isset($_POST['submit']))
	{
		$_POST = array_map("mysql_real_escape_string", $_POST);
		$_POST = array_map("trim", $_POST);

		$contactID = $_POST['contactID'];
		
		$blur = $_POST['blur'];
		
		$president = $_POST['president'];
		$pre_irc = $_POST['pre_irc'];
		$pre_email = $_POST['pre_email'];

		$v_president = $_POST['v_president'];
		$vpre_irc = $_POST['vpre_irc'];
		$vpre_email = $_POST['vpre_email'];

		$secretary = $_POST['secretary'];
		$sec_irc = $_POST['sec_irc'];
		$sec_email = $_POST['sec_email'];

		$treasurer = $_POST['treasurer'];
		$tre_irc = $_POST['tre_irc'];
		$tre_email = $_POST['tre_email'];

		$tech_admin = $_POST['tech_admin'];
		$tec_irc = $_POST['tec_irc'];
		$tec_email = $_POST['tec_email'];

		$webmaster = $_POST['webmaster'];
		$web_irc = $_POST['web_irc'];
		$web_email = $_POST['web_email'];

		$social_events = $_POST['social_events'];
		$soc_irc = $_POST['soc_irc'];
		$soc_email = $_POST['soc_email'];
		

		$insert = "UPDATE contact SET 
		president='".$president."', pre_irc='".$pre_irc."', pre_email='".$pre_email."',
		v_president='".$v_president."', vpre_irc='".$vpre_irc."', vpre_email='".$vpre_email."',
		secretary='".$secretary."', sec_irc='".$sec_irc."', sec_email='".$sec_email."',
		treasurer='".$treasurer."', tre_irc='".$tre_irc."', tre_email='".$tre_email."',
		tech_admin='".$tech_admin."', tec_irc='".$tec_irc."', tec_email='".$tec_email."',
		webmaster='".$webmaster."', web_irc='".$web_irc."', web_email='".$web_email."',
		social_events='".$social_events."', soc_irc='".$soc_irc."', soc_email='".$soc_email."',
		blur='".$blur."'
		WHERE contactID='".$contactID."'";
		$result = $db->query($insert);

		echo '<script>window.location.href="/cassa/contact.php";</script>';
	}
	
	$query = "SELECT * FROM contact";
	$result = $db->query($query);
	$row = $result->fetch_assoc();

	if($result->num_rows == 0)
	{
		$contactID = "";
		$blur = "";

		$president = "";
		$pre_irc = "";
		$pre_email = "";

		$v_president = "";
		$vpre_irc = "";
		$vpre_email = "";

		$secretary = "";
		$sec_irc = "";
		$sec_email = "";

		$treasurer = "";
		$tre_irc = "";
		$tre_email = "";

		$tech_admin = "";
		$tec_irc = "";
		$tec_email = "";

		$webmaster = "";
		$web_irc = "";
		$web_email = "";

		$social_events = "";
		$soc_irc = "";
		$soc_email = "";
		
		$insert = "INSERT INTO contact VALUES ('', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '',  '')";
		$resultC = $db->query($insert);
	}
	if($result->num_rows > 0)
	{
		$contactID = $row['contactID'];
		
		$blur = $row['blur'];
		
		$president = $row['president'];
		$pre_irc = $row['pre_irc'];
		$pre_email = $row['pre_email'];

		$v_president = $row['v_president'];
		$vpre_irc = $row['vpre_irc'];
		$vpre_email = $row['vpre_email'];

		$secretary = $row['secretary'];
		$sec_irc = $row['sec_irc'];
		$sec_email = $row['sec_email'];

		$treasurer = $row['treasurer'];
		$tre_irc = $row['tre_irc'];
		$tre_email = $row['tre_email'];

		$tech_admin = $row['tech_admin'];
		$tec_irc = $row['tec_irc'];
		$tec_email = $row['tec_email'];

		$webmaster = $row['webmaster'];
		$web_irc = $row['web_irc'];
		$web_email = $row['web_email'];

		$social_events = $row['social_events'];
		$soc_irc = $row['soc_irc'];
		$soc_email = $row['soc_email'];
	}
?>

<!-- //******************************************************

// Name of File: editNotices.php
// Revision: 1.0
// Date: 09/05/2012
// Author: Quintin M
// Modified: Luke Spartalis

//***********************************************************

//*********** Start of EDIT NOTICES PAGE *************** -->

<head>
<script type='text/javascript'>
	



</script>
</head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Manage Contact Page</h1>

<br />


<!-- Interface Box -->
<div id='newsBox1' align='center'>
<form name='MANcontact' enctype="multipart/form-data" method='POST' 
	  onsubmit='return faqVal()' action='MANcontacts.php'>
<br />

	<table cellpadding='5px' border='0'>

	<tr>
		<td align='right' style='vertical-align: top; color: #888888;'>
			Blur: <br /><br />
		</td>
<td>
			<textarea class='addNoticeBackColor addNoticeTextArea' 
			name='blur' rows='10' maxlength='1024' /><?php echo $blur; ?></textarea>
		</td>
	</tr>


	

	<table border="0" style="" width="600px" cellspacing="10px">
	<tr>
		<td>President:				<input type='text' name='president' maxlength='64' value='<?php echo $president; ?>'/></td>
		<td>President IRC:			<input type='text' name='pre_irc' maxlength='64' value='<?php echo $pre_irc; ?>'/></td>
		<td>President Email:		<input type='text' name='pre_email' maxlength='64' value='<?php echo $pre_email; ?>'/></td>
	</tr>
	<tr>
		<td>Vice President:			<input type='text' name='v_president' maxlength='64' value='<?php echo $v_president; ?>'/></td>
		<td>Vice President IRC:		<input type='text' name='vpre_irc' maxlength='64' value='<?php echo $vpre_irc; ?>'/></td>
		<td>Vice President Email:	<input type='text' name='vpre_email' maxlength='64' value='<?php echo $vpre_email; ?>'/></td>
	</tr>
	<tr>
		<td>Secretary:				<input type='text' name='secretary' maxlength='64' value='<?php echo $secretary; ?>'/></td>
		<td>Secretary IRC:			<input type='text' name='sec_irc' maxlength='64' value='<?php echo $sec_irc; ?>'/></td>
		<td>Secretary Email:		<input type='text' name='sec_email' maxlength='64' value='<?php echo $sec_email; ?>'/></td>
	</tr>
	<tr>
		<td>Treasurer:				<input type='text' name='treasurer' maxlength='64' value='<?php echo $treasurer; ?>'/></td>
		<td>Treasurer IRC:			<input type='text' name='tre_irc' maxlength='64' value='<?php echo $tre_irc; ?>'/></td>
		<td>Treasurer Email:		<input type='text' name='tre_email' maxlength='64' value='<?php echo $tre_email; ?>'/></td>
	</tr>
	<tr>
		<td>Tech Admin:				<input type='text' name='tech_admin' maxlength='64' value='<?php echo $tech_admin; ?>'/></td>
		<td>Tech Admin IRC:			<input type='text' name='tec_irc' maxlength='64' value='<?php echo $tec_irc; ?>'/></td>
		<td>Tech Admin Email:		<input type='text' name='tec_email' maxlength='64' value='<?php echo $tec_email; ?>'/></td>
	</tr>
	<tr>
		<td>Webmaster:				<input type='text' name='webmaster' maxlength='64' value='<?php echo $webmaster; ?>'/></td>
		<td>Webmaster IRC:			<input type='text' name='web_irc' maxlength='64' value='<?php echo $web_irc; ?>'/></td>
		<td>Webmaster Email:		<input type='text' name='web_email' maxlength='64' value='<?php echo $web_email; ?>'/></td>
	</tr>
	<tr>
		<td>Social Coordinator:		<input type='text' name='social_events' maxlength='64' value='<?php echo $social_events; ?>'/></td>
		<td>Social Coordinator IRC:	<input type='text' name='soc_irc' maxlength='64' value='<?php echo $soc_irc; ?>'/></td>
		<td>Social Coordinator Email:<input type='text' name='soc_email' maxlength='64' value='<?php echo $soc_email; ?>'/></td>
	</tr>

</table>

	<tr><td colspan="2" align="center"><br /><input type="submit" name="submit" value="submit" /></td></tr>

	<tr><td colspan="2"><br /><br /></td></tr>

	<!-- ENSURES TO THE SERVER THERE EITHER IS OR IS NOT A FILE TO BE SAVED-->
	<input type='hidden' name='contactID' value='<?php echo $contactID; ?>' />

	</table>
</form>
</div>





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