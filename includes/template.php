<!-- //******************************************************

// Name of File: template.html
// Revision: 1.0
// Date: 29/03/2012
// Author: Quintin M. [demo]
// Modified: L.SMITH 1/4/12
// Modified: Quintin M 07/04/2012
// Modified: Quintin M 09/04/2012
// Modified: Quintin M 15/04/2012
// Modified: Quintin M 16/04/2012
// Modified: Quintin M 26/04/2012

//***********************************************************
//******************** Start of TEMPLATE PAGE ************** -->


<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">


<!-- H E A D --> <!-- H E A D --> <!-- H E A D -->
<head>
	<title><?php echo $_SESSION['title']; ?></title>

	<!-- CSS -->
	<link rel="stylesheet" type="text/css" href="/cassa/css/style.css" />


	<!-- Website ICON -->
	<link rel="shortcut icon" href="/cassa/images/logos/cassa_16x16.ico">


	<!-- JQuery / Lightbox 2 -->
	<script type="text/javascript" src="/cassa/js/js/jquery.js"></script>
	<script type="text/javascript" src="/cassa/js/js/lightbox.js"></script>
	<script type="text/javascript" src="/cassa/js/js/jquery.smooth-scroll.min.js"></script>
	<script type="text/javascript" src="/cassa/js/js/jquery-ui-1.8.18.custom.min.js"></script>
	
	<link rel="stylesheet" type="text/css" src="/cassa/js/css/lightbox.css" />
	<link rel="stylesheet" type="text/css" src="/cassa/js/css/screen.css" />


	<!-- Thickbox -->
	<script type="text/javascript" src="/cassa/js/colorbox/colorbox.js"></script>
	<link rel="stylesheet" type="text/css" href="/cassa/js/colorbox/colorbox.css" />


	<!-- ColorBox -->
	<script type="text/javascript" src="/cassa/js/thickbox/thickbox.js"></script>
	<link rel="stylesheet" type="text/css" href="/cassa/js/thickbox/thickbox.css" />


	<!-- Website Common Functions -->
	<?php include('functions.php'); ?>

	<!-- Printable Pages -->
	<link rel="stylesheet" href="/cassa/css/print.css" type="text/css" media="print" />

	<!-- VALIDATE: login form -->
	<script type='text/javascript'>
	function loginValidate()
	{
		if(document.login['username'].value == '')
		{
			alert('No username was entered');
			document.login['username'].focus();
			return false;
		}
		if(document.login['password'].value == '')
		{
			alert('No password was entered');
			document.login['password'].focus();
			return false;
		}
	}
	</script><!-- end of LOGIN VALIDATION-->
</head>






<!-- **************** B O D Y **************** -->
<body>
<center>






<!-- IF JAVASCRIPT IS 'disabled' SEND WARNING MESSAGE -->
<noscript>
<font class='error'>
	*WARNING* For this website to function correctly, JavaScript must be enabled.<br />
	Please turn on javascript prior to proceeding. 
</font>
</noscript>






<!-- OVERALL SHELL
	 ~CONTAINS EVERYY OBJECT 
	 ~DIV SHELL CLOSES INSIDE VERY PAGE -->
<div id='shell'>






	<!-- TOP HEADER: [Logo][Title] -->
	<table width="940px" height="100px" border='0'>
	<tr>
		<!-- Logo -->
		<td width="500px" align='left'>
			<img align="left" src="/cassa/images/logos/CASSA_Header.png" width="400px" height="80px">
		</td>

		<!-- Title -->
		<td width="440px" align="center" style='vertical-align: middle;'>
			<h1 align="center" class="subtitle" style="font-size: 16pt;">MegaLAN Management System</h1>
		</td>
	</tr>
	</table>






<!-- MENU BAR -->
<div class='menuBar'>
	<ul>
		<!-- HOME -->
		<li onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/home.php"'>
			<a href='/cassa/home.php'>Home</a>
		</li>


		<!-- REGISTER -->
		<?php 
		if (!isset($_SESSION['username']))
		{
		?>

		<li	onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/register.php"'>
			<a href='/cassa/register.php'>Register</a>
		</li>

		<?php
		}
		?>


		<!-- FAQ -->
		<li onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/faq.php"'>
			<a href='/cassa/faq.php'>FAQ</a>
		</li>



		<!-- EVENT -->
		<li	onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/eventProgram.php"'>
			<a href='/cassa/eventProgram.php'>Event Program</a>
		</li>



		<!-- SEAT AVAILABILITY -->
		<li onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/seatMap.php"'>
			<a href='/cassa/seatMap.php'>Seat Map</a>
		</li>



		<!-- CONTACT -->
		<li	onmouseover='this.style.backgroundColor="#333333"' 
			onmouseout='this.style.backgroundColor="black"'
			onclick='window.location.href="/cassa/contact.php"'>
			<a href='/cassa/contact.php'>Contact</a>
		</li>



		<!-- LOGIN -->
		<?php
			if (!isset($_SESSION['username']))
			{
		?>

			<li	onmouseover='this.style.backgroundColor="#333333"' 
				onmouseout='this.style.backgroundColor="black"'>
				<a href='/CASSA/login.php?height=110&width=250&modal=false' 
				class='thickbox'><div class='menuUserColor'>Login</div></a>
			</li>

		<?php
			}
		?>



		<!-- STAFF -->
		<?php 
		if(isset($_SESSION['isAdmin']))
		{
			if($_SESSION['isAdmin'] == 1 || $_SESSION['isAdmin'] == 2)
			{
			?>
				<li onmouseover='this.style.backgroundColor="#333333"; 
								document.getElementById("staff").style.display="block"' 
					onmouseout='this.style.backgroundColor="black";
								document.getElementById("staff").style.display="none"'
					onclick='window.location.href="/CASSA/management/staffBoard.php"'>
					<a href='/CASSA/management/staffBoard.php'><div class='menuUserColor'>Staff</div></a>
				
					<!-- CHILD LIST -->
					<ul class='children' id='staff'>
						<!-- ADD Notices -->
						<li onclick='window.location.href="/cassa/management/ADDnotices.php"'>
							<a href='/cassa/management/ADDnotices.php'>Add Notices</a>
						</li>

						<!-- MANAGE Event -->
						<li onclick='window.location.href="/cassa/management/MANevent.php"'>
							<a href='/cassa/management/MANevent.php'>Manage Event</a>
						</li>

						<!-- MANAGE Tournaments -->
						<li onclick='window.location.href="/cassa/management/MANtournament.php"'>
							<a href='/cassa/management/MANtournament.php'>Manage Tournament</a>
						</li>

						<!-- MANAGE Pizza -->
						<li onclick='window.location.href="/cassa/management/MANpizza.php"'>
							<a href='/cassa/management/MANpizza.php'>Manage Pizza</a>
						</li>

						<!-- MANAGE Participants -->
						<li onclick='window.location.href="/cassa/management/MANparticipants.php"'>
							<a href='/cassa/management/MANparticipants.php'>Manage Participants</a>
						</li>
						

						<!-- MANAGE FAQ -->
						<li onclick='window.location.href="/cassa/management/MANFAQ.php"'>
							<a href='/cassa/management/MANFAQ.php'>Manage FAQ</a>
						</li>

						<!-- MANAGE Contacts -->
						<li onclick='window.location.href="/cassa/management/MANcontacts.php"'>
							<a href='/cassa/management/MANcontacts.php'>Manage Contacts</a>
						</li>
					</ul>
				</li>
			<?php 
			}
			if(isset($_SESSION['isAdmin']) && isset($_SESSION['username']))
			{
			?>
				<!-- PARTICIPANT -->
				<li onmouseover='this.style.backgroundColor="#333333"; 
								document.getElementById("participant").style.display="block"' 
					onmouseout='this.style.backgroundColor="black";
								document.getElementById("participant").style.display="none"'
					onclick='window.location.href="/cassa/management/clientBoard.php"'>
					<a href='/cassa/management/clientBoard.php'><div class='menuUserColor'><?php echo $_SESSION['fullName']; ?></div></a>
					
					<!-- CHILD LIST -->
					<ul class='children' id='participant'>
						<!-- MANAGE Registration -->
						<li onclick='window.location.href="/cassa/management/MANdetails.php"'>
							<a href='/cassa/management/MANdetails.php'>Manage Details</a>
						</li>

						<!-- EVENT REGISTRATION -->
						<li onclick='window.location.href="/cassa/management/eventRegistration.php"'>
							<a href='/cassa/management/eventRegistration.php'>Event Registration</a>
						</li>

						<!-- TOURNAMENT REGISTRATION -->
						<li onclick='window.location.href="/cassa/management/eventRegistration.php?t=2"'>
							<a href='/cassa/management/eventRegistration.php?t=2'>Tournament Registration</a>
						</li>

						<!-- SELECT SEAT -->
						<li onclick='window.location.href="/cassa/management/eventRegistration.php?t=3"'>
							<a href='/cassa/management/eventRegistration.php?t=3'>Seat Status</a>
						</li>

						<!-- ORDER PIZZA -->
						<li onclick='window.location.href="/cassa/management/eventRegistration.php?t=4"'>
							<a href='/cassa/management/eventRegistration.php?t=4'>Order Pizza</a>
						</li>
					</ul>
				</li>
			<?php
			}
		}

		// IF USER IS LOGGED IN, DISPLAY LOGOUT BUTTON
		if (isset($_SESSION['username']))
		{
		?>
			<!-- LOGGOUT -->
			<li onmouseover='this.style.backgroundColor="#333333"' 
				onmouseout='this.style.backgroundColor="black"'
				onclick='window.location.href="/cassa/logout.php"'>
				<a href='/cassa/logout.php'><div class='menuUserColor'>Logout</div></a>
			</li>

		<?php
		}
		?>
	</ul>
	<br style='clear: both' />
</div><!-- end of: menu Bar -->



<br /><br />

<?php
	// If there is an error, handle it
	if (isset($_SESSION['errMsg']))
	{
		echo $_SESSION['errMsg'].'<br />';
		unset($_SESSION['errMsg']);
	}
?>


</div>
</center>
</body>
</html>