<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<!-- //******************************************************

// Name of File: eventProgram.php
// Revision: 1.0
// Date: 12/06/2012
// Author: Quintin Maseyk
// Modified: 

//***************************************************** -->



<?php 
    session_start();								// Start/resume THIS session

    $_SESSION['title'] = "Event Details | MegaLAN"; // Declare this page's Title

    include("includes/template.php");               // Include the template page
    include("includes/conn.php");                   // Include the db connection
    include("includes/functions.php");              // Include the db connection

	if (isset($_POST['submit']))
	{
		$_POST = array_map("mysql_real_escape_string", $_POST);
		$_POST = array_map("trim", $_POST);
		$_SESSION['errMsg'] = '';
		$winner = removeBlockTags($_POST['winner']);
		

		if ($_POST['winner'] != '')
		{
			// UPDATE
			$update = "UPDATE tournament SET winner='".$winner."' WHERE tournID='".$_POST['tournID']."'";
			$result = $db->query($update);
		}
	}
?>



<head>
</head>
<body>  
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">



<?php
	// GET THIS EVENT
	$get = "SELECT * FROM event WHERE startDate >= CURDATE() ORDER BY startDate ASC";
	$result = $db->query($get);
	
	if ($result->num_rows == 0)
	{
		echo '<br /><font class="subtitle" style="font-size: 16pt;">MegaLAN have no events at this time</font>';
	}
	else
	{
		$row = $result->fetch_assoc();
		$eventID = $row['eventID'];
		$eventName = $row['event_name'];
		$eventDate = $row['startDate'];
		$seat = $row['seatQuantity'];

		// GET SEAT COUNT
		$count = "SELECT * FROM seat WHERE status=0";
		$result = $db->query($count);
		$seatCount = $result->num_rows;
?>
	<h1>Event Details 
		<font class='subtitle' style='font-size: 16pt;'> 
			<?php echo $eventName; ?>
		</font>
	</h1>

	<h3 style='line-height: 18pt;'>Event Location: <?php echo $row['event_location']; ?></h3>
	<h3 style='line-height: 18pt;'>Event Date: <?php echo dateToScreen($eventDate); ?></h3>
	<h3 style='line-height: 18pt;'>Doors Open Time: <?php echo removeSeconds($row['startTime']); ?></h3>
	<h3 style='line-height: 18pt;'>Seat Availability: <?php echo $seatCount . '/' . $seat; ?></h3>
	<h3 style='line-height: 18pt;'>Server Address: <?php echo $row['server_IP_address']; ?></h3>

	<br />

<?php
		// GET ALL OF THIS EVENTS TOURNAMENTS
		$get = "SELECT * FROM tournament WHERE eventID = '".$eventID."'";
		$result = $db->query($get);

		if ($result->num_rows == 0)
		{
			echo '<br /><font class="subtitle" style="font-size: 16pt;">This event holds no tournaments at this time</font>';
		}
		else
		{
			for ($i=0; $i<$result->num_rows; $i++)
			{
				echo "<table class='pizzaOrder tournamentStrip' >";

					echo '<tr><td colspan="2"><br /></td></tr>';

					$row = $result->fetch_assoc();

					// Tournament Name
					echo '<tr>';
						echo '<td width="130px">Tournament: </td>';
						echo '<td>'.$row['name'].'</td>';
					echo '</tr>';

					// Tournament Time
					echo '<tr>';
						echo '<td>Time <font size="2">24 hours</font>: </td>';
						echo '<td>'.substr($row['start_time'], 0, 5).' - '.substr($row['end_time'], 0, 5).'</td>';
					echo '</tr>';

				if (isset($_SESSION['isAdmin']))
				{
					if ($_SESSION['isAdmin'] == 1 || $_SESSION['isAdmin'] == 2)
					{
						// Tournament Winner
						echo '<tr>';
						echo '<form name="tourn_winner" method="post" action="eventProgram.php">';
							echo '<td>Winner: </td>';
							echo '<td>';
								// Tourn ID [hidden]
								echo '<input type="hidden" name="tournID" value="'.$row['tournID'].'" />';

								// Tourn Winner [input]
								echo '<input type="text" name="winner" value="'.$row['winner'].'" />&nbsp;&nbsp;';

								// Submit
								echo '<input type="submit" name="submit" value="  Update  " />';
							echo '</td>';
						echo '</form>';
						echo '</tr>';					
					}
				}
				else
				{
					// Tournament Winner
					echo '<tr>';
						echo '<td>Winner: </td>';
						echo '<td><font class="subtitle" style="font-size: 13pt; font-weight: bold;">'.$row['winner'].'</font></td>';
					echo '</tr>';
				}
					echo '<tr><td colspan="2"><br /></td></tr>';

				echo "</table>";

				echo '<br />';
			}
		}
	}
?>



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