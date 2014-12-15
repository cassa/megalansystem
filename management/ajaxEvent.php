<?php 
	session_start();
	include("../includes/conn.php");					// Include the db connection
	include("../includes/functions.php");				// Include general functions


if (isset($_POST['subject']))
{
	// IF [user] BOOKS A TOURNAMENT
	if ($_POST['subject'] == 'book')
	{
		$insert = "INSERT INTO `attendee_tournament` (attendeeID, tournID) VALUES ('".$_POST['attendeeID']."', '".$_POST['tournID']."')";
		$result = $db->query($insert);
		
		$_POST['t'] = 2;

		/*
		*	AT THIS STAGE, THIS USER HAS BOOKED AN EVENT AND A TOURNAMENT
		*	THE USER YET HAS TO:
		*		-BOOK A SEAT
		*		-BOOK PIZZA (optional)
		*/
	}

	// IF [staff] CANCELS AN EVENT
	else if ($_POST['subject'] == 'cancelEvent')
	{
		// GET ATTENDEE SEAT
		$get = "SELECT * FROM attendee WHERE attendeeID='".$_POST['attendeeID']."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();
		$seatID = $row['seatID'];

		// REMOVE ATTENDEE ROW
		$cancel = "DELETE FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$_POST['eventID']."'";
		$result = $db->query($cancel);
	
		// REMOVE PIZZA ORDER(S)
		$cancel = "DELETE FROM pizza_order WHERE attendeeID='".$_POST['attendeeID']."'";
		$result = $db->query($cancel);

		// REMOVE SEAT STATUS
		$cancel = "UPDATE seat SET status=1 WHERE seatID='".$seatID."'";
		$result = $db->query($cancel);

		$_POST['t'] = 1;
	}

	// IF [this] USER CANCELS A TOURNAMENT
	else if ($_POST['subject'] == 'cancel')
	{
		$cancel = "DELETE FROM attendee_tournament WHERE attendeeID='".$_POST['attendeeID']."' AND tournID='".$_POST['tournID']."'";
		$result = $db->query($cancel);
		
		$_POST['t'] = 2;

		/*
		*	AT THIS STAGE, THIS USER HAS BOOKED AN EVENT
		*	THE USER YET HAS TO:
		*		-BOOK A TOURNAMENT
		*/
	}

	// IF [this] USER ORDERS A PIZZA
	else if ($_POST['subject'] == 'order')
	{
		if ($_POST['qty'] != 0)
		{
			$order = "INSERT INTO pizza_order (pizzaID, attendeeID, quantity, paid_pizza, menuID) VALUES ('".$_POST['pizzaID']."', '".$_POST['attendeeID']."', '".$_POST['qty']."', 0, '".$_POST['menuID']."')";

			$result = $db->query($order);
		}

		$_POST['t'] = 4;
	}

	// IF [this] USER CANCELS A PIZZA ORDER
	else if ($_POST['subject'] == 'cancelOrder')
	{
		$cancel = "DELETE FROM pizza_order WHERE pizzaID='".$_POST['pizzaID']."' AND attendeeID='".$_POST['attendeeID']."' AND menuID='".$_POST['menuID']."'";
		$result = $db->query($cancel);
		
		$_POST['t'] = 4;
	}
}




if (isset($_POST['t']))
{
// EVENT TRIGGER
	if($_POST['t'] == 1)
	{
		$eventID = getThisEvent($db);

		// CHECK IF [this] CLIENT HAS BOOKED A CURRENT EVENT
		$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$eventID."'";
		$result = $db->query($check);

		if ($result->num_rows == 0)
		{
			// DISPLAY ALL AVAILABLE EVENTS TO USER FOR BOOKING
			display_all_events($db);
		}
		else
		{
			$row = $result->fetch_assoc();
			// DISPLAY ALL [this] USERS BOOKED EVENTS
			display_all_booked_events($db);
		}
	}

// TOURNAMENT TRIGGER
	else if ($_POST['t'] == 2)
	{
		// DISPLAY ALL AVAILABLE EVENT-->TOURNAMENT TO USER FOR BOOKING
		display_all_event_tournaments($db);
	}

// SEAT TRIGGER
	else if ($_POST['t'] == 3)
	{
		// CHECK IF [this] CLIENT HAS BOOKED A TOURNAMENT
		$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."'";
		$result = $db->query($check);

		if ($result->num_rows == 0)
		{
			$row = $result->fetch_assoc();

			// AT THIS STAGE, A USER HAS BOOKED AN EVENT
			// HOWEVER, HAS NOT BOOKED A TOURNAMENT
			echo "<table class='displayTable chair' border='0' style='line-height: 17pt'>";
			echo "<tfoot><tr><td align='center' height='60px'>";
				echo "<font class='error'>You must book an Event before you can reserve a seat</font>"; 
				echo "<br /><br /><a href='eventRegistration.php?t=1'>Click here to book an Event</a>";
			echo "</td></tr></tfoot>";
			echo "</table>";
		}
		else
		{
			// CHECK IF [this] USER HAS BOOKED A SEAT
			$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."'";
			$result = $db->query($check);
			$row = $result->fetch_assoc();

			if ($row['seatID'] == NULL)
			{
				// AT THIS STAGE, A USER HAS BOOKED AN EVENT AND A TOURNAMENT
				// HOWEVER, HAS NOT BOOKED A SEAT
				echo "<table class='displayTable chair' border='0' style='line-height: 17pt'>";
				echo "<tr><td align='center'><a href='/cassa/seatMap.php'>Click here to book a seat</a></td></tr>";
				echo "</table>";
			}
			else
			{
				// DISPLAY THE SEAT NUMBER
				echo "<table class='displayTable chair' border='0' style='line-height: 17pt'>";
				echo "<tr><td align='center'><b>Your reserved seat number is:</b> ".$row['seatID']."";
				echo '&nbsp;&nbsp;&nbsp;&nbsp;';
				echo '<img class="pointer" src="/cassa/images/buttons/cancel.png" onclick="cancelSeat('.$row['seatID'].', '.$row['attendeeID'].')" alt="Cancel this Seat" /></td></tr>';
				echo "</table>";

				// FORM: CANCEL [this] SEAT
				echo "<form name='cancelThisSeat' method='POST' action='eventRegistration.php'>";
				echo "<input type='hidden' name='seatID' id='seatID' value='' />";
				echo "<input type='hidden' name='attendeeID' id='attendeeID' value='' />";
				echo "</form>";
			}
		}
	}




// PIZZA TRIGGER
	else if ($_POST['t'] == 4)
	{
		// GET CURRENT EVENT
		$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_completed = 0 ORDER BY startDate ASC";
		$result = $db->query($query);

		if ($result->num_rows != 0)
		{
			$row = $result->fetch_assoc();

		echo "<table class='displayTable' border='0'>";

			// CHECK IF USER HAS BOOK AN EVENT
			$query = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$row['eventID']."'";
			$resultCheck = $db->query($query);
		
			if ($resultCheck->num_rows == 0)
			{
				echo "<tr><td align='center' style='height: 230px;'>";
					echo "<font class='error'>You must book an Event before you can order pizza</font>"; 
					echo "<br /><br /><a href='eventRegistration.php?t=1'>Click here to book an Event</a>";
				echo "</td></tr>";
			}
			else
			{
				$rowATT = $resultCheck->fetch_assoc();
				$attendeeID = $rowATT['attendeeID'];

				// GET ALL OF THIS EVENTS PIZZAS
				$get = "SELECT * FROM pizza_menu WHERE eventID='".$row['eventID']."'";
				$resultPizza = $db->query($get);
				

				// No pizza menu in database
				if ($resultPizza->num_rows == 0)
				{
					echo "<tr><td align='center' style='height: 230px;'>";
						echo "<font class='error'>There is no pizza menu for this event yet</font>"; 
					echo "</td></tr>";
				}
				else
				{
					// Display all available pizzas
					$rowPizza = $resultPizza->fetch_assoc();
					$menuID = $rowPizza['menuID'];

					// GET ALL PIZZAS @ menu_items
					$get = "SELECT * FROM menu_items WHERE menuID='".$menuID."'";
					$result = $db->query($get);

					echo '<tr>';
						echo '<th align="left" width="120px">Pizza Name</th>';
						echo '<th align="left" width="250px">Description</th>';
						echo '<th align="left" width="50px">Price</th>';
						echo '<th align="left" width="50px">QTY</th>';
						echo '<th width="106px"></th>';
					echo '</tr>';

					if ($result->num_rows != 0)
					{
						for ($i=0; $i<$result->num_rows; $i++)
						{
							$row = $result->fetch_assoc();
							$pizzaID = $row['pizzaID'];

							// DISPLAY EACH PIZZA ITEM DESCRIPTION @ pizza_type
							$check = "SELECT * FROM pizza_type WHERE pizzaID='".$pizzaID."'";
							$resultPizza = $db->query($check);
							$rowPizza = $resultPizza->fetch_assoc();

							// CHECK IF THIS USER HAS ORDERED THIS pizza_type
							$checkATT = "SELECT * FROM pizza_order WHERE pizzaID='".$pizzaID."' AND attendeeID='".$attendeeID."' AND menuID='".$menuID."'";
							$resultATT = $db->query($checkATT);

							if ($resultATT->num_rows == 0)
							{
								echo '<tr>';
								echo '<td>'.ucwords($rowPizza['pizza_name']).'</td>';
								echo '<td>'.ucwords($rowPizza['description']).'</td>';
								echo '<td>'.$rowPizza['price'].'</td>';
								echo '<td>';
									echo '<select name="pizzaQTY_'.$i.'" id="pizzaQTY_'.$i.'">';
									echo '<option value="0" selected="selected">0</option>';
									echo '<option value="1">1</option>';
									echo '<option value="2">2</option>';
									echo '<option value="3">3</option>';
									echo '<option value="4">4</option>';
									echo '</select>';
								echo '</td>';
								echo '<td><img class="pointer" src="/cassa/images/buttons/book.png" alt="Click to order this pizza" onclick="bookPizza('.$rowPizza['pizzaID'].', '.$i.', '.$attendeeID.', '.$menuID.')" /></td>';
								echo '</tr>';
							}
							else
							{
								$pizzaATT = $resultATT->fetch_assoc();
								$qty = $pizzaATT['quantity'];

								echo '<tr>';
								echo '<td>'.ucwords($rowPizza['pizza_name']).'</td>';
								echo '<td>'.ucwords($rowPizza['description']).'</td>';
								echo '<td>'.$rowPizza['price'].'</td>';
								echo '<td>'.$qty.'</td>';
								echo '<td><img class="pointer" src="/cassa/images/buttons/cancel.png" alt="Click to cancel this pizza" onclick="cancelPizza('.$rowPizza['pizzaID'].', '.$attendeeID.', '.$menuID.')" /></td>';
								echo '</tr>';
							}
						}
					}
				}
			}
	echo "</table>";
		}
	}
}







/*
 * E V E N T
 */
function display_all_events($db)
{
	// GET [current] EVENT
	$eventID = getThisEvent($db);
	$query = "SELECT * FROM event WHERE eventID='".$eventID."'";
	$result = $db->query($query);
?>
	<table class='displayTable' name='eventRegistration' border='0'>
<?php 
		if(isset($_SESSION['errMsg']))
		{
			echo '<caption>';
				echo $_SESSION['errMsg'];
				unset($_SESSION['errMsg']);
			echo '</caption>';
		}

	if ($result->num_rows == 0)
	{
		echo '<tr><td align="center" style="height: 230px;">';
		echo '<font class="error">MegaLAN have no events running at this time.</font></td></tr>';
	}
	else
	{
		?>
		<tr>
			<th align='left'>Name</th>
			<th align='left'>Location</th>
			<th align='center' width="70px">Date</th>
			<th align='center' width="80px">Start Time</th>
			<th align='center' width="90px">Seat QTY</th>
			<th width="106px">&nbsp;</th>
		</tr>
	<?php
		echo '<tfoot><td align="center" colspan="6" height="80px">';
		echo '<font class="error">You have not booked an Event yet</font></td></tfoot>';

		for ($i=0; $i<$result->num_rows; $i++)
		{
			// SETUP [this] ROW DETAILS
			$row = $result->fetch_assoc();    
			$name = $row['event_name'];
			$location = $row['event_location'];
			$date = dateToScreen($row['startDate']);
			$startTime = removeSeconds($row['startTime']);
			$seatQuantity = $row['seatQuantity'];

			// FIND OUT HOW MANY SEATS HAVE BEEN BOOKED FOR THIS EVENT
			$seatCheck = "SELECT * FROM seat WHERE status=0";
			$seatResult = $db->query($seatCheck);
			$seatCount = $seatResult->num_rows;

			// SETUP ON MOUSE EVENTS
			$on = "this.style.backgroundColor='#E0ECF8'";
			$off = "this.style.backgroundColor='transparent'";
			$onclickBook = "book(".$row['eventID'].")";

			// CHECK IF USER HAS BOOKED THIS EVENT
			$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$row['eventID']."'";
			$resultCheck = $db->query($check);

			echo '<tr onmouseover="'.$on.'" onmouseout="'.$off.'">';
				echo '<td>'.$name.'</td>';
				echo '<td>'.$location.'</td>';
				echo '<td>'.$date.'</td>';
				echo '<td align="center">'.$startTime.'</td>';
				echo '<td align="center">'.$seatCount. '/' .$seatQuantity.'</td>';
			
			if ($resultCheck->num_rows == 0)
			{
				echo '<td onclick="'.$onclickBook.'" class="pointer">';
					echo '<img src="/cassa/images/buttons/book.png" alt="Book this event" /></td>';
			}
			else
			{
				$rowAttendee = $resultCheck->fetch_assoc();
				$attendeeID = $rowAttendee['attendeeID'];
				$onclickCancel = "cancel(".$row['eventID'].", ".$attendeeID.")";

				echo '<td onclick="'.$onclickCancel.'" class="pointer">';
					echo '<img src="/cassa/images/buttons/cancel.png" alt="Cancel this event" /></td>';
			}
			echo '</tr>';
		}
	}
?>
	</table>

	<!-- FORM: BOOK [this] EVENT -->
	<form name='bookEvent' method='POST' action='eventRegistration.php'>
	<input type='hidden' name='bookID' id='bookID' value='' />
	<input type='hidden' name='subject' id='subject' value='' />
	</form>
<?php
}






function display_all_booked_events($db)
{
	$eventID = getThisEvent($db);

	// GET ALL OF [this] USERS CURRENTLY BOOKED EVENTS @ ATTENDEE
	$query = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$eventID."'";
	$result = $db->query($query);
?>
	<table class='displayTable' name='eventRegistration' style='font-size: 11pt; line-height: 20pt; text-align: center'>
	<caption><?php if(isset($_SESSION['errMsg'])){echo $_SESSION['errMsg'];unset($_SESSION['errMsg']);}?></caption>
<?php
	for ($i=0; $i<$result->num_rows; $i++)
	{
		// GET ASSOCIATED ROW @ ATTENDEE
		$row = $result->fetch_assoc();

		// GET ASSOCIATED ROW @ EVENT
		$get = "SELECT * FROM event WHERE eventID = '".$row['eventID']."' AND startDate >= CURDATE() AND event_completed = 0 ORDER BY startDate";
		$result = $db->query($get);

		if ($result->num_rows == 0)
		{
			display_all_events($db);
		}
		else
		{
			$rowEvent = $result->fetch_assoc();
			$attendeeID = $row['attendeeID'];

			// GET SEAT COUNT FOR THIS EVENT
			$count = "SELECT * FROM seat WHERE status=0";
			$resultCount = $db->query($count);
			$seatCount = $resultCount->num_rows;


			// SETUP [this] ROW DETAILS
			$name = $rowEvent['event_name'];
			$location = $rowEvent['event_location'];
			$date = $rowEvent['startDate'];
			$startTime = $rowEvent['startTime'];
			$seatQuantity = $rowEvent['seatQuantity'];
			
			// SETUP MOUSE EVENTS
			$onclick = "book(".$row['eventID'].")";

			//echo '<tr><td class="displayRow">Event Name</td>';
			echo '<tr><td colspan="2"><font class="subtitle" style="font-size: 25pt;">'.$name.'</font></td></tr>';
			
			echo '<tr><td colspan="2"><hr /></td></tr>';

			echo '<tr><td class="displayRow" align="right" width="100px">Location</td>';
			echo '<td align="left" width="150px" style="text-indent: 5px">';
			echo $location.'</td></tr>';
			
			echo '<tr><td class="displayRow" align="right">Event Date</td>';
			echo '<td align="left" width="150px" style="text-indent: 5px">';
			echo dateToScreen($date).'</td></tr>';
			
			echo '<tr><td class="displayRow" align="right">Start Time</td>';
			echo '<td align="left" width="150px" style="text-indent: 5px">';
			echo removeSeconds($startTime).'</td></tr>';
			
			echo '<tr><td class="displayRow" align="right">Seat Quantity</td>';
			echo '<td align="left" width="150px" style="text-indent: 5px">';
			echo $seatCount.' / '.$seatQuantity.'</td></tr>';

			echo '<tr><td colspan="2"><br />';
			echo '<img class="pointer" src="/cassa/images/buttons/cancel.png" title="Click to cancel this event" onclick="cancel('.$eventID.', '.$attendeeID.')" /></td></tr>';
		}
	}
?>
	</table>
<?php
}





/*
 * T O U R N A M E N T 
 */
// DISPLAY ALL TOURNAMENTS FOR [this] USER TO BOOK
function display_all_event_tournaments($db)
{
	// GET [current] EVENT
	$eventID = getThisEvent($db);

	// GET ALL OF [this] USERS CURRENTLY BOOKED EVENTS @ ATTENDEE
	$query = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."' AND eventID='".$eventID."'";
	$result = $db->query($query);
?>

<table class='displayTable' border='0' style='line-height: 17pt;'>
<?php 
	if(isset($_SESSION['errMsg']))
	{
		echo "<tfoot><tr><td colspan='5' align='center' height='60px'>";
			echo $_SESSION['errMsg'];
			unset($_SESSION['errMsg']);
		echo "</td></tr></tfoot>";
	}

	if ($result->num_rows == 0)
	{
		echo "<tr><td align='center' style='height: 230px;'>";
			echo "<font class='error'>You must book an Event before you can book a tournament</font>"; 
			echo "<br /><br /><a href='eventRegistration.php?t=1'>Click here to book an Event</a>";
		echo "</td></tr>";
	}
	else
	{
		// GET ALL [current] EVENTS INFORMATION
		$rowEvent = getThisEventRow($db);
		$name = $rowEvent['event_name'];

		// GET ATTENDEE --> [current event] DETAILS
		$row = $result->fetch_assoc();
		$attendeeID = $row['attendeeID'];

		// CHECK IF [this] EVENT HAS ANY TOURNAMENTS YET
		$check = "SELECT * FROM tournament WHERE eventID='".$eventID."'";
		$resultCheck = $db->query($check);

		if ($resultCheck->num_rows == 0)
		{
		echo "<tr><td align='center' style='height: 230px;'>";
			echo "<font class='error'>";
			echo "<font class='subtitle' style='font-size: 16pt;'>".$name."</font>";
			echo " contains no tournaments at this time.</font>"; 
		echo "</td></tr>";
		}
		else
		{

			echo '<tr><td colspan="5"><b>'.$name.'</b> Tournament List:</td></tr>';
			echo '<tr><td colspan="5"><br /></td></tr>';
		?>
			<tr>
				<td><b>Tournament Name</b></td>
				<td><b>Date</b></td>
				<td><b>Start Time</b></td>
				<td><b>End Time</b></td>
				<td>&nbsp;</td>
			</tr>
		<?php
		}
	?>


	<?php
		// GET ALL RELATED TOURNAMENTS TO [this] EVENT
		$get = "SELECT * FROM tournament WHERE eventID='".$row['eventID']."' AND started != 2 ORDER BY start_time ASC";
		$result = $db->query($get);

		for ($i=0; $i<$result->num_rows; $i++)
		{
			$rowTourn = $result->fetch_assoc();

			// CHECK IF USER HAS BOOKED [this] TOURNAMENT
			$check = "SELECT * FROM attendee_tournament WHERE attendeeID='".$row['attendeeID']."' AND tournID='".$rowTourn['tournID']."'";
			$resultCheck = $db->query($check);
			$rowAttendee = $resultCheck->fetch_assoc();

			echo '<tr>';
				echo '<td>'.$rowTourn['name'].'</td>';
				echo '<td>'.dateToScreen($rowEvent['startDate']).'</td>';
				echo '<td>'.removeSeconds($rowTourn['start_time']).'</td>';
				echo '<td>'.removeSeconds($rowTourn['end_time']).'</td>';
				
				if (empty($rowAttendee))
				{
					echo '<td><img class="pointer" src="/cassa/images/buttons/book.png"  onclick="bookTournament('.$rowTourn['tournID'].', '.$row['attendeeID'].')" alt="Book this tournament" /></td>';
				}
				else
				{
					echo '<td><img class="pointer" src="/cassa/images/buttons/cancel.png"  onclick="cancelTournament('.$rowTourn['tournID'].', '.$row['attendeeID'].')" alt="Cancel this tournament" /></td>';
				}
			echo '</tr>';
		}
	}
	?>
</table>
	<!-- FORM: BOOK [this] TOURNAMENT -->
	<!-- form name='bookTourn' method='POST' action='eventRegistration.php'>
	<input type='hidden' name='bookTournamentID' id='bookTournamentID' value='' />
	<input type='hidden' name='attendeeID' id='attendeeID' value='' />
	</form -->
<?php
}