<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();										// Start/resume THIS session

	// PAGE SECURITY
	if (!isset($_SESSION['isAdmin']))
	{
		echo '<script type="text/javascript">history.back()</script>';
		die();
	}

	$_SESSION['title'] = "Event Registration | MegaLAN"; 	// Declare this page's Title
	include("../includes/template.php"); 					// Include the template page
	include("../includes/conn.php"); 						// Include the db connection

// IF [this] USER BOOKS FOR AN EVENT
	if (isset($_POST['bookID']) && isset($_POST['subject']))
	{
		if ($_POST['subject'] == 'bookEvent')
		{
		// CHECK IF USER HAS BOOKED THIS EVENT
			$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$_POST['bookID']."'";
			$result = $db->query($check);

			// IF THEY HAVE BOOKED [this] EVENT, DEFER THEM
			if ($result->num_rows > 0)
			{
				// SEE IF THEY HAVE BOOKED 'this' EVENT
				$row = $result->fetch_assoc();
				if ($row['eventID'] == $_POST['bookID'])
				{
					$_SESSION['errMsg'] = '<font class="error">Sorry you have already booked this event</font>';
				}
			}
			// BOOK [this] EVENT
			else
			{
				$book = "INSERT INTO attendee (eventID, clientID, paid) VALUES (".$_POST['bookID'].", ".$_SESSION['userID'].", 0)";
				$result = $db->query($book);


				/*
				*	AT THIS STAGE, THIS USER HAS BOOKED AN EVENT
				*	THE USER YET HAS TO:
				*		-BOOK A TOURNAMENT
				*		-BOOK A SEAT
				*		-BOOK PIZZA (optional)
				*/
			}
		}
	}

// IF [this] USER CANCELS A SEAT
	if (isset($_POST['seatID']))
	{
		$cancel = "UPDATE attendee SET seatID=NULL WHERE attendeeID='".$_POST['attendeeID']."' AND clientID='".$_SESSION['userID']."'";
		$result = $db->query($cancel);

		$cancel = "UPDATE seat SET status='1' WHERE seatID='".$_POST['seatID']."'";
		$result = $db->query($cancel);

		/*
		*	AT THIS STAGE, THIS USER HAS BOOKED AN EVENT
		*	THE USER YET HAS TO:
		*		-BOOK A TOURNAMENT
		*		-BOOK A SEAT
		*		-BOOK PIZZA (optional)
		*/
	}

// CHECK IF A CURRENT EVENT EXISTS
	// GET ALL CURRENT EVENTS
	$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_completed=0 ORDER BY startDate ASC";
	$result = $db->query($query);

	if ($result->num_rows == 0)
	{
		$eventStatus = 0;
	}
	else
	{
		$eventStatus = 1;
	}
?>


<!-- //******************************************************

// Name of File: eventRegister.php
// Revision: 1.0
// Date: 14/05/2012
// Author: Quintin
// Modified: 

//***********************************************************

//********** Start of EVENT REGISTRATION PAGE ************** -->

<head>

<script type='text/javascript'>

// DISPLAY EVENT DETAILS FIRST
function getEvent(params)
{
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
			document.getElementById("ajaxDIV").innerHTML=xmlhttp.responseText;
		}
	}

	//Now we have the xmlhttp object, get the data using AJAX.
	xmlhttp.open("POST","ajaxEvent.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}

// BOOK EVENT
function book(id)
{
	var answer = confirm("Please confirm to book this Event");

	if (answer == true)
	{
		document.getElementById('bookID').value = id;
		document.getElementById('subject').value = "bookEvent";
		document.bookEvent.submit();
	}
}
// CANCEL EVENT
function cancel(id, attendeeID)
{
	var answer = confirm("Please confirm to cancel this Event");

	if (answer == true)
	{	
		var params = "eventID=" + id + "&attendeeID=" + attendeeID + "&subject=cancelEvent";
		//alert(params);
		getEvent(params);
	}
}
// BOOK TOURNAMENT
function bookTournament(tournID, attendeeID)
{
	var answer = confirm("Please confirm to book this Tournament");

	if (answer == true)
	{
		var params = "tournID=" + tournID + "&attendeeID=" + attendeeID + "&subject=book";
		getEvent(params);
	}
}
// CANCEL TOURNAMENT
function cancelTournament(tournID, attendeeID)
{
	var answer = confirm("Please confirm to cancel this Tournament");

	if (answer == true)
	{
		var params = "tournID=" + tournID + "&attendeeID=" + attendeeID + "&subject=cancel";
		getEvent(params);
	}
}
// CANCEL SEAT
function cancelSeat(seatID, attendeeID)
{
	var answer = confirm("Please confirm to cancel this Seat");

	if (answer == true)
	{
		document.getElementById('seatID').value = seatID;
		document.getElementById('attendeeID').value = attendeeID;
		document.cancelThisSeat.submit();
	}
}
// BOOK PIZZA
function bookPizza(pizzaID, row, attendeeID, menuID)
{
	var answer = confirm("Please confirm to order this pizza");

	if (answer == true)
	{
		var pizzaQTY = document.getElementById("pizzaQTY_"+row).selectedIndex;

		var params = "pizzaID=" + pizzaID + "&qty=" + pizzaQTY + "&attendeeID=" + attendeeID + "&menuID=" + menuID + "&subject=order";
		getEvent(params);
	}
}
// CANCEL PIZZA
function cancelPizza(pizzaID, attendeeID, menuID)
{
	var answer = confirm("Please confirm to cancel this pizza order");

	if (answer == true)
	{
		var params = "pizzaID=" + pizzaID + "&attendeeID=" + attendeeID + "&menuID=" + menuID + "&subject=cancelOrder";
		getEvent(params);
	}
}
</script>

</head>

<body onload='getEvent("t=<?php if(isset($_GET['t'])){echo $_GET['t'];}else{echo "1";}?>");'>	
<center>

<div id='shell'>
<!-- Main Content [left] -->

<div id="content">
<h2>
	Event Registration For: 
	<font size="4" style='font-family: Segoe Print;'><?php echo $_SESSION['fullName']; ?></font>
</h2>






<br />






<!-- GET [this] USERS BOOKED EVENT -->
<?php
	// [current] EVENT ID
	$eventID = getThisEvent($db);

	// GET ATTENDEE EVENT DETAILS
	$get = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$eventID."'";
	$result = $db->query($get);

	if ($result->num_rows == 0)
	{
		$rowEvent = 'No';
		$tournID = 'No';
		$seatID = 'No';
		$pizzaID = 'No';
		$eventStatus = 0;
	}
	else
	{
		for ($i=0; $i<$result->num_rows; $i++)
		{
			$row = $result->fetch_assoc();

			// CHECK IF USER HAS BOOKED IN [this current] EVENT
			$check = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_completed = 0 AND eventID='".$eventID."'";
			$resultCheck = $db->query($check);

			// [this] attendee has not booked into [this] event
			if ($resultCheck->num_rows == 0)
			{
				$rowEvent = 'No';
				$tournID = 'No';
				$seatID = 'No';
				$pizzaID = 'No';
			}
			else
			{
				$thisEventRow = $resultCheck->fetch_assoc();
				$rowEvent = 'Yes';

				// GET TOURNAMENT DETAILS
					// Get [this] event's tournaments
					$check = "SELECT * FROM tournament WHERE eventID='".$eventID."'";
					$resultCheck = $db->query($check);

					if ($resultCheck->num_rows != 0)
					{
						for ($i=0; $i<$resultCheck->num_rows; $i++)
						{		
							$rowTournament = $resultCheck->fetch_assoc();
							$rowTournID = $rowTournament['tournID'];

							$getT = "SELECT * FROM attendee_tournament WHERE attendeeID='".$row['attendeeID']."' AND tournID='".$rowTournID."'";
							$resultT = $db->query($getT);

							if ($resultT->num_rows != 0)
							{
								// User is booked into one of this events tournaments
								$tournID = 'Yes';
								$i = $resultCheck->num_rows;
							}
							else
							{
								// Tournament == False
								$tournID = 'No';
							}
						}
					}
					else
					{
						$tournID = 'No';
					}

				// GET SEAT DETAILS
					if ($row['seatID'] == '' || empty($row['seatID'])) { $seatID = 'No'; } else { $seatID = 'Yes'; }

				// GET PIZZA DETAILS
					// Get [this] event's menu
					$check = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
					$result = $db->query($check);
					if ($result->num_rows == 0)
					{
						$pizzaID = 'No';
					}
					else
					{
						$rowMenu = $result->fetch_assoc();
						$menuID = $rowMenu['menuID'];

						// Check if user has ordered a pizza for [this] menu
						$get = "SELECT * FROM pizza_order WHERE attendeeID = '".$row['attendeeID']."' AND menuID='".$menuID."'";
						$result = $db->query($get);
						if ($result->num_rows == 0) { $pizzaID = 'No'; } else { $pizzaID = 'Yes'; }
					}
			}
		}
	}
?>






<div class='eventSlider' align='center'>

	<!-- AJAX DYNAMIC DIV -->
	<div class='event'><div id='ajaxDIV'></div></div>


	<?php 
		// SETUP MOUSE CLICK CLASSES
		$onclick = 
		'document.getElementById("eventBUT").className="eBAR pointer"; 		document.getElementById("tournBUT").className="eBAR pointer"; document.getElementById("seatBUT").className="eBAR pointer"; document.getElementById("pizzaBUT").className="eBAR pointer"; this.className="eBAR_ON pointer";';

		$imgTick = "<div class='eSTATUS'><img src='/cassa/images/layers/tick.png' /></div>";
		$imgCross = "<div class='eSTATUS'><img src='/cassa/images/layers/cross.png' /></div>";

		// CHECK IF OUTSIDE PAGE IS TRYING TO ACCESS A CERTAIN MENU BAR 
		// 1 = EVENT
		// 2 = TOURNAMENT
		// 3 = SEAT
		// 4 = PIZZA
		// DEFAULT = 1
		$eBAR1 = 'eBAR_ON'; $eBAR2 = 'eBAR'; $eBAR3 = 'eBAR'; $eBAR4 = 'eBAR';
		if(isset($_GET['t']))
		{
			if ($_GET['t'] == 1){$eBAR1 = 'eBAR_ON';}else{$eBAR1 = 'eBAR';}
			if ($_GET['t'] == 2){$eBAR2 = 'eBAR_ON';}else{$eBAR2 = 'eBAR';}
			if ($_GET['t'] == 3){$eBAR3 = 'eBAR_ON';}else{$eBAR3 = 'eBAR';}
			if ($_GET['t'] == 4){$eBAR4 = 'eBAR_ON';}else{$eBAR4 = 'eBAR';}
		}
	?>


	<!-- LEFT CONTROL PANEL -->
	<div class='eventBAR'>
		<div id='eventBUT' 
			 class='pointer; <?php echo $eBAR1; ?>' 
			 <?php if ($eventStatus == 1) { ?> onclick='getEvent("t=1"); <?php echo $onclick; }?>'>
			<div class='eFONT'><font size='2'>1-</font> EVENT</div>

			<?php if ($rowEvent == 'No') { echo $imgCross; } else { echo $imgTick; } ?>
		</div>

		<div id='tournBUT' 
			 class='pointer; <?php echo $eBAR2; ?>' 
			 <?php if ($eventStatus == 1) { ?> onclick='getEvent("t=2"); <?php echo $onclick; }?>'>
			<div class='eFONT'><font size='2'>2-</font> TOURNAMENT</div>

			<?php if ($tournID == 'No') { echo $imgCross; } else { echo $imgTick; } ?>
		</div>

		<div id='seatBUT' 
			 class='pointer; <?php echo $eBAR3; ?>' 
			 <?php if ($eventStatus == 1) { ?> onclick='getEvent("t=3"); <?php echo $onclick; }?>'>
			<div class='eFONT'><font size='2'>3-</font> SEAT</div>

			<?php if ($seatID == 'No') { echo $imgCross; } else { echo $imgTick; } ?>
		</div>

		<div id='pizzaBUT' 
			 class='pointer; <?php echo $eBAR4; ?>' 
			 <?php if ($eventStatus == 1) { ?> onclick='getEvent("t=4"); <?php echo $onclick; }?>'>
			<div class='eFONT'><font size='2'>4-</font> PIZZA</div>

			<?php if ($pizzaID == 'No') { echo $imgCross; } else { echo $imgTick; } ?>
		</div>
	</div>
</div>






<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!--**************************************** -->
<br /><br /><br /><br />

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