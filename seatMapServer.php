<?php 
	session_start();									// Start/resume THIS session
	include("includes/conn.php"); 						// Include the database con


	// IF SOMEONE CLICKED TO BOOK A SEAT
	if (isset($_POST['seatID']))
	{
		// CHECK IF USER IS LOGGED ON
		if (!isset($_SESSION['isAdmin']))
		{
			echo "You must be logged in to book a seat";
			die();
			//header('Location: eventRegistration.php');
		}
		else
		{
		// CHECK IF USER HAS NOT REGISTERED INTO AN EVENT
			$check = "SELECT * FROM attendee WHERE clientID='".$_SESSION['userID']."'";
			$result = $db->query($check);
			$row = $result->fetch_assoc();

			// IF USER HAS NOT REGISTERED FOR AN EVENT
			// DIRECT THEM TO EVENT REGISTRATION PAGE
			if (!isset($row))
			{
				echo 'You must register for an event first';
				die();
				//header('Location: eventRegistration.php');
			}


		// IF USER HAS NOT BOOKED A SEAT
			if (empty($row['seatID']))
			{
				echo 'Your seat has been booked';

				// BOOK SEAT
				$update = "UPDATE attendee SET seatID='".$_POST['seatID']."' WHERE clientID='".$_SESSION['userID']."'";
				$result = $db->query($update);

				// UPDATE SEAT STATUS
				$update = "UPDATE seat SET status='0' WHERE seatID='".$_POST['seatID']."'";
				$result = $db->query($update);

				// DISPLAY CONFIRMATION 
				echo '<script type="text/javascript">';
				echo 'alert("Your seat booking has been made\nThank you");';
				echo 'window.location.href="/cassa/client_summary.php"';
				echo '</script>';

				// SEND EMAIL CONFIRMATION
				/*
				*
				*
				*/
			}

		// ELSE IF USER HAS ALREADY BOOKED A SEAT
			else
			{
				echo 'Your seat has been booked';

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
				header('Location: client_summary.php');
			}
		}
	}
?>