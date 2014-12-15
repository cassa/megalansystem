<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();							// Start/resume THIS session
	$_SESSION['title'] = "Contact | MegaLAN"; 		// Declare this page's Title
	include("includes/template.php"); 					// Include the template page
	include("includes/conn.php"); 	
	
	
?>


<!-- //******************************************************

// Name of File: contact.php
// Revision: 1.0
// Date: 15/04/2012
// Author: Quintin M
// Modified: 

//***********************************************************

//*************** Start of CONTACT PAGE ******************* -->





<head></head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Contact</h1>



<br />



<h3 style='line-height: 20pt;'>Clubroom</h3>

<div style='height: 146px;  border: 0px solid black'>
	<div style='float: left;'>
		<img src='images/layers/clubroom.jpg' width='120px' height='146px' />
		<br />
	</div>
<div style='float: right; width: 500px; margin-left: 15px; word-spacing: 2px; border: 0px solid black'>
<br/>
<br/>
	
<?php

	// FETCH ALL CONTACT RESULTS
	$query = "SELECT * FROM `contact`";
	$result = $db->query($query);

	if($result->num_rows == 0)
	{
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
	}
	else
	{
		$row = $result->fetch_assoc();
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

		echo '<div style="line-height: 18pt;">'.$blur.'<br /></div>';
	}
?>
	<br />
	<br />
	<br />
</div>
</div>



<div style='line-height: 20pt; word-spacing: 2px; float: left;' >
	<b>Executive Committee for 2012</b><br />
	<b><?php echo $president; ?></b> President (IRC: <?php echo $pre_irc; ?> Email: <a href="mailto:<?php echo $pre_email; ?>"><?php echo $pre_email; ?></a>)<br />  
	<b><?php echo $v_president; ?></b> Vice-President (IRC: <?php echo $vpre_irc; ?> Email: <a href="mailto:<?php echo $vpre_email; ?>"><?php echo $vpre_email; ?></a>)<br />  
	<b><?php echo $secretary; ?></b> Secretary (IRC: <?php echo $sec_irc; ?> Email: <a href="mailto:<?php echo $sec_email; ?>"><?php echo $sec_email; ?></a>)<br />  
	<b><?php echo $treasurer; ?></b> Treasurer (IRC: <?php echo $tre_irc; ?> Email: <a href="mailto:<?php echo $tre_email; ?>"><?php echo $tre_email; ?></a>)<br />  
	<b><?php echo $tech_admin; ?></b> Tech-Admin (IRC: <?php echo $tec_irc; ?> Email: <a href="mailto:<?php echo $tec_email; ?>"><?php echo $tec_email; ?></a>)<br />  
	<b><?php echo $webmaster; ?></b> Webmaster (IRC: <?php echo $web_irc; ?> Email: <a href="mailto:<?php echo $web_email; ?>"><?php echo $web_email; ?></a>)<br />  
	<b><?php echo $social_events; ?></b> Social Events Coordinator (IRC: <?php echo $soc_irc; ?> Email: <a href="mailto:<?php echo $soc_email; ?>"><?php echo $soc_email; ?></a>)<br />  
</div>







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