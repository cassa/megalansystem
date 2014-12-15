<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 


<?php 
	session_start();									// Start/resume THIS session
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

		if ($row['status'] != 'Y')
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
			// echo $name.'<br/>';
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
?>

<head>
<script type='text/javascript'>
	function showName(seat)
	{
		// SEAT NUMBER
		document.getElementById('seatNumber').value = seat;

		// SEAT NAME
		var name = document.getElementById(seat + 'name').value;
		document.getElementById('seatName').value = name;
	}
	function hideName()
	{
		document.getElementById('seatNumber').value = '';
		document.getElementById('seatName').value = '';
	}
	function bookSeat(seat)
	{
		if (document.getElementById(seat + 'name').value.length <= 2)
		{
			document.getElementById('seatReady').value = 'YES';
			var answer = confirm("Please confirm to book seat number "+seat);

			if (answer == true)
			{
				//document.bookThisSeat['seatID'].value = seat;
				//document.forms['bookThisSeat'].submit();

				if (window.XMLHttpRequest)
				{	
					// code for mainstream browsers
					xmlhttp=new XMLHttpRequest();
				}
				else
				{
					// code for earlier IE versions
					xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
				}

				xmlhttp.onreadystatechange=function()
				{
					if (xmlhttp.readyState==4 && xmlhttp.status==200)
					{	
						document.getElementById("seatReturn").innerHTML=xmlhttp.responseText;
					}
				}
			
				//Now we have the xmlhttp object, get the data using AJAX.
				var params = "seatID=" + seat;		
				
				xmlhttp.open("POST","seatMapServer.php",true);
				xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
				xmlhttp.setRequestHeader("Content-length", params.length);
				xmlhttp.setRequestHeader("Connection", "close");
				xmlhttp.send(params);
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



<div id='seatReturn'></div>







<div id='inline_content'>
<!-- FORM IN WHICH GETS POSTED IF A USER CLICKS TO BOOK A SEAT -->
<form name='bookThisSeat' method='POST' action='seatMapLayoutServer.php'>
	<input type='hidden' name='seatID' id='seatID' value='' />
</form>



<!-- SEAT PLAN LAYOUT -->
<img src='/cassa/images/seatPlan/layout_940.png' border='0' />






<!-- B O T T O M   D E T A I L S -->
	<!-- BOTTOM RIGHT LEGEND -->
	<div id='legend' style='float: right'>	
	<br />
		<img src='/cassa/images/seatPlan/seatTop_Y30.png' /> <b>Available</b> 
		<img src='/cassa/images/seatPlan/seatTop_N30.png' /> <b>Booked</b>
		<img src='/cassa/images/seatPlan/seatTop_R30.png' /> <b>Reserved</b>
	</div><!-- end of: LEGEND -->


	<!-- BOTTOM SEAT DETAIL -->
	<div id='seatDetails'>
	<br />
		<!-- Seat Number: -->
		<input type='hidden' name='seatNumber' id='seatNumber' value='' size='2' readonly='readonly' />
	
	&nbsp;&nbsp;&nbsp;
	
	Reserved For: 
		<input type='text' name='seatName' id='seatName' value='' size='32' readonly='readonly' />

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
	
	
	//echo "<a href='seatMapServer.php?seatID=".($i+1)."' class='ajax' title='testing'>";
					// [this] SEAT NUMBER
					echo "<div class='seatNumber numberTop'>".($i+1)."</div>";

					// [this] SEAT IMAGE
					echo "<img class='seat_sm pointer' src='".$src.$top.$seatStatus[$i].$ext;
	//echo "</a>";


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
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
		</tr>
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
		</tr>
	</table>




	<!-- TABLE SECTION 3 [MIDDLE TOP] -->
	<table id='S3' class='seat' cellspacing="0" cellpadding="0">
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
		</tr>
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
		</tr>
	</table>




	<!-- TABLE SECTION 4 [MIDDLE BOTTOM] -->
	<table id='S4' class='seat' cellspacing="0" cellpadding="0">
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
		</tr>
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
		</tr>
	</table>



	<!-- TABLE SECTION 5 [BOTTOM RIGHT] -->
	<table id='S5' class='seat' cellspacing="0" cellpadding="0">
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatTop_green.png' /></td>
		</tr>
		<tr>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
			<td class='pointer'><img class='seat_sm' src='images/seatPlan/seatBot_green.png' /></td>
		</tr>
	</table>
</div>



</body>
</html>