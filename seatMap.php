<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();									// Start/resume THIS session
	$_SESSION['title'] = "Seat Availability | MegaLAN"; // Declare this page's Title
	include("includes/template.php"); 					// Include the template page
	include("includes/conn.php"); 						// Include the database con



// GET CURRENT SEAT STATUS
	$get = "SELECT * FROM seat";
	$result = $db->query($get);
	
	$seatNumber = Array();
	$seatStatus = Array();
	$client = Array();
	for ($i=0; $i<$result->num_rows; $i++)
	{
		$row = $result->fetch_assoc();
		$seatNumber[$i] = $row['seatID'];
		$seatStatus[$i] = $row['status'];

		if ($row['status'] != '1')
		{
			// GET [this] CLIENT ID
			$getClient = "SELECT clientID FROM attendee WHERE seatID = '".$seatNumber[$i]."'";
			$resultClient = $db->query($getClient);
			$row = $resultClient->fetch_assoc();
			
			$clientID = $row['clientID'];

			// GET [this] CLIENT DETAILS
			$getName = "SELECT * FROM client WHERE clientID = '".$clientID."'";
			$resultName = $db->query($getName);
			$row = $resultName->fetch_assoc();

			$name = ucwords($row['first_name']. ' ' .$row['last_name']);
			$client[$i] = $name;
		}
		else
		{
			$client[$i] = '';
		}
	}

	// SET IMAGE VARIABLE
	$src = '/cassa/images/seatPlan/seat';

		// SET TOP/BOTTOM VIEW SEATS
		$top = "Top_";
		$bot = "Bot_";

		// SET EXTENSION
		$ext = ".png' />";

	// SET MOUSE EVENTS
	$mover = " onmouseover='showName(this.id)'";
	$mout = " onmouseout='hideName()'";
	$onclick = " onclick='bookSeat(this.id)'";







// IF SOMEONE CLICKED TO BOOK A SEAT
	if (isset($_POST['seatID']))
	{
		// CHECK IF USER IS LOGGED ON
		if (!isset($_SESSION['isAdmin']))
		{
			$_SESSION['errMsg'] = "<font class='error'>You must be logged in to book a seat</font>";
		?>
			<script type='text/javascript'>
				window.location.href="loginPlus.php";
			</script>
		<?php
			die();
		}
		else
		{
		// CHECK IF USER HAS NOT REGISTERED INTO AN [EVENT]
			$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."'";
			$result = $db->query($check);
			$row = $result->fetch_assoc();

			// IF USER HAS NOT REGISTERED FOR AN EVENT
			// DIRECT THEM TO EVENT REGISTRATION PAGE
			if (!isset($row))
			{
				$_SESSION['errMsg'] = "<font class='error'>You must register to an event first</font>";
			?>
				<script type='text/javascript'>
					window.location.href="/cassa/management/eventRegistration.php?t=1";
				</script>
			<?php 
				die();
			}

		// IF USER HAS NOT BOOKED A SEAT
			if ($row['seatID'] == NULL || $row['seatID'] == '' || empty($row['seatID']))
			{
				// BOOK SEAT
				$update = "UPDATE attendee SET seatID='".$_POST['seatID']."' WHERE clientID='".$_SESSION['userID']."'";
				$result = $db->query($update);

				// UPDATE SEAT STATUS
				$update = "UPDATE seat SET status='0' WHERE seatID='".$_POST['seatID']."'";
				$result = $db->query($update);

				// DISPLAY CONFIRMATION 
				echo '<script type="text/javascript">';
				echo 'window.location.href="/cassa/management/eventRegistration.php?t=3"';
				echo '</script>';
			}

		// ELSE IF USER HAS ALREADY BOOKED A SEAT
			else
			{
				// CLEAR [this] CURRENT SEAT STATUS @ SEAT
				$clear = "UPDATE seat SET status=1 WHERE seatID='".$row['seatID']."'";
				$result = $db->query($clear);

				// BOOK SEAT @ ATTENDEE
				$update = "UPDATE attendee SET seatID='".$_POST['seatID']."' WHERE clientID='".$_SESSION['userID']."'";
				$result = $db->query($update);

				// UPDATE SEAT STATUS @ SEAT
				$update = "UPDATE seat SET status=0 WHERE seatID='".$_POST['seatID']."'";
				$result = $db->query($update);

				// RE-DIRECT TO CLIENT SUMMARY PAGE
				echo '<script type="text/javascript">';
				echo 'window.location.href="/cassa/management/eventRegistration.php?t=3"';
				echo '</script>';
			}
		}
	}
?>


<!-- //******************************************************

// Name of File: seatMap.php
// Revision: 1.0
// Date: 15/04/2012
// Author: Quintin M
// Modified: Quintin M 26/04/2012

//***********************************************************

//*************** Start of SEAT AVAILABILITY PAGE ************ -->

<head>
<script type='text/javascript'>

	$(document).ready(function()
	{
		$(".ajax").colorbox();
		$(".inline").colorbox({inline:true, width:"950px", height:"500px", scrolling: false});
	});



	function showName(seat)
	{
		// SEAT NUMBER
		document.getElementById('seatNumber').value = seat;

		// SEAT NAME
		var name = document.getElementById(seat + 'name').value;
		document.getElementById('seatName').innerHTML = '<b>'+name+'</b>';
	}
	function hideName()
	{
		document.getElementById('seatNumber').value = '';
		document.getElementById('seatName').innerHTML = '';
	}
	function bookSeat(seat)
	{
		if (document.getElementById(seat + 'name').value.length <= 2)
		{
			document.getElementById('seatReady').value = 'YES';
			var answer = confirm("Please confirm to book seat number "+seat);

			if (answer == true)
			{
				document.bookThisSeat['seatID'].value = seat;
				document.forms['bookThisSeat'].submit();
			}
		}
		else
		{
			document.getElementById('seatReady').value = 'NO';
		}
	}
</script>
</head>
<body>
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content">
	<h1>Seat Availability</h1>
	<br />




<a class='inline' href="#inline_content">
	<!-- SMALL IMAGE -->
	<img src='images/seatPlan/layout_small.png' border='0' />
</a> 





<div id='seatReturn'></div>





<div style='display:none'>
<div id='inline_content'>
<!-- FORM IN WHICH GETS POSTED IF A USER CLICKS TO BOOK A SEAT -->
<form name='bookThisSeat' method='POST' action='seatMap.php'>
	<input type='hidden' name='seatID' id='seatID' value='' />
</form>





<!-- ERROR HANDLING -->
<div id='errorHandling'>
	<?php 
		if (isset($_SESSION['errMsg']))
		{
			echo $_SESSION['errMsg']; 
			unset($_SESSION['errMsg']);
		}
	?>
</div>





<!-- SEAT PLAN LAYOUT -->
<img src='/cassa/images/seatPlan/layout_940.png' border='0' />





<!-- B O T T O M   D E T A I L S -->
	<!-- BOTTOM RIGHT LEGEND -->
	<div id='legend' style='float: right'>	
	<br />
		<img src='/cassa/images/seatPlan/seatTop_Y30.png' /> <b>Available</b> 
		<img src='/cassa/images/seatPlan/seatTop_N30.png' /> <b>Booked</b>
		<!-- img src='/cassa/images/seatPlan/seatTop_R30.png' /> <b>Reserved</b -->
	</div><!-- end of: LEGEND -->


	<!-- BOTTOM SEAT DETAIL -->
	<div id='seatDetails'>
	<br />
		<!-- Seat Number: -->
		<input type='hidden' name='seatNumber' id='seatNumber' value='' size='2' readonly='readonly' />
	
	&nbsp;&nbsp;&nbsp;
	
	Booked For: 
		<!-- input type='text' name='seatName' id='seatName' value='' size='32' readonly='readonly' / -->
		<span name='seatName' id='seatName' value=''></span>

	&nbsp;&nbsp;&nbsp;
	
		<!-- Good to book?: -->
		<input type='hidden' name='seatReady' id='seatReady' value='' size='3' readonly='readonly' />
	</div><!-- end of: SEAT DETAIL -->





<!-- L A Y O U T   T A B L E -->
	<!-- TABLE SECTION 1 [TOP LEFT] -->
	<table id='S1' class='seat' cellspacing="0" cellpadding="0" border='0'>
		<!-- [this] TABLE TOP ROW -->
		<tr>
		<?php
			for ($i=0; $i<5; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";
					// [this] SEAT NUMBER
					echo "<div class='seatNumber numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
				echo "</td>";
			}
		?>
		</tr>
		<!-- [this] TABLE BOTTOM ROW -->
		<tr>
		<?php
			for ($i=5; $i<10; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";

					// [this] SEAT NUMBER
					echo "<img class='seat_sm pointer' src='".$src.$bot.$seatStatus[$i].$ext;

					// [this] SEAT IMAGE
					echo "<div class='seatNumber numberBot'>".($i+1)."</div>";
				echo "</td>";
			}
		?>		
		</tr>
	</table>



	<!-- TABLE SECTION 2 [TOP RIGHT] -->
	<table id='S2' class='seat' cellspacing="0" cellpadding="0">
		<!-- [this] TABLE TOP ROW -->
		<tr>
		<?php
			for ($i=10; $i<15; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";
					// [this] SEAT NUMBER
					echo "<div class='numWide numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
				echo "</td>";
			}
		?>
		</tr>
		<!-- [this] TABLE BOTTOM ROW -->
		<tr>
		<?php
			for ($i=15; $i<20; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";

					// [this] SEAT NUMBER
					echo "<img class='seat_sm pointer' src='".$src.$bot.$seatStatus[$i].$ext;

					// [this] SEAT IMAGE
					echo "<div class='numWide numberBot'>".($i+1)."</div>";
				echo "</td>";
			}
		?>		
		</tr>
	</table>




	<!-- TABLE SECTION 3 [MIDDLE TOP] -->
	<table id='S3' class='seat' cellspacing="0" cellpadding="0">
		<!-- [this] TABLE TOP ROW -->
		<tr>
		<?php
			for ($i=20; $i<30; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";
					// [this] SEAT NUMBER
					echo "<div class='numWide numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
				echo "</td>";
			}
		?>
		</tr>
		<!-- [this] TABLE BOTTOM ROW -->
		<tr>
		<?php
			for ($i=30; $i<40; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";

					// [this] SEAT NUMBER
					echo "<img class='seat_sm pointer' src='".$src.$bot.$seatStatus[$i].$ext;

					// [this] SEAT IMAGE
					echo "<div class='numWide numberBot'>".($i+1)."</div>";
				echo "</td>";
			}
		?>		
		</tr>
	</table>




	<!-- TABLE SECTION 4 [MIDDLE BOTTOM] -->
	<table id='S4' class='seat' cellspacing="0" cellpadding="0">
		<!-- [this] TABLE TOP ROW -->
		<tr>
		<?php
			for ($i=40; $i<50; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";
					// [this] SEAT NUMBER
					echo "<div class='numWide numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
				echo "</td>";
			}
		?>
		</tr>
		<!-- [this] TABLE BOTTOM ROW -->
		<tr>
		<?php
			for ($i=50; $i<60; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";

					// [this] SEAT NUMBER
					echo "<img class='seat_sm pointer' src='".$src.$bot.$seatStatus[$i].$ext;

					// [this] SEAT IMAGE
					echo "<div class='numWide numberBot'>".($i+1)."</div>";
				echo "</td>";
			}
		?>		
		</tr>
	</table>



	<!-- TABLE SECTION 5 [BOTTOM RIGHT] -->
	<table id='S5' class='seat' cellspacing="0" cellpadding="0">
		<!-- [this] TABLE TOP ROW -->
		<tr>
		<?php
			for ($i=60; $i<65; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";
					// [this] SEAT NUMBER
					echo "<div class='numWide numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
				echo "</td>";
			}
		?>
		</tr>
		<!-- [this] TABLE BOTTOM ROW -->
		<tr>
		<?php
			for ($i=65; $i<70; $i++)
			{
			// SET HIDDEN FIELD WITH CLIENT NAME
			echo "<input type='hidden' name='".($i+1)."name' id='".($i+1)."name' value='".$client[$i]."' />";

				// [this] SEAT 
				echo "<td id='".($i+1)."' ".$mover.$mout.$onclick.">";

					// [this] SEAT NUMBER
					echo "<img class='seat_sm pointer' src='".$src.$bot.$seatStatus[$i].$ext;

					// [this] SEAT IMAGE
					echo "<div class='numWide numberBot'>".($i+1)."</div>";
				echo "</td>";
			}
		?>		
		</tr>
	</table>
</div>
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