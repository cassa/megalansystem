<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();


	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}


	$_SESSION['title'] = "Manage Payment | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 				// Include the template page
	include("../includes/conn.php");					// Include database connection


	if (isset($_POST['subject']))
	{
		if ($_POST['subject'] == 'payEvent')
		{
			// UPDATE ATTENDEE.paid
			$update = "UPDATE attendee SET paid='1' WHERE attendeeID='".$_POST['attendeeID']."'";
			$result = $db->query($update);
		}
		else if ($_POST['subject'] == 'payPizza')
		{
			// UPDATE PIZZA_ORDER.paid_pizza
			$update = "UPDATE pizza_order SET paid_pizza='1' WHERE attendeeID='".$_POST['attendeeID']."'";
			$result = $db->query($update);
		}
	}


?>

<!-- //******************************************************

// Name of File: MANpay.php
// Revision: 1.0
// Date: 20/06/2012
// Author: Quintin Maseyk

//***********************************************************

//*************** Start of CREATE PIZZA ******************* -->

<head>
<script type='text/javascript'>
function payEvent(attendeeID)
{
	var answer = confirm("Please confirm this person's Event payment");

	if (answer == true)
	{
		document.getElementById('attendeeID').value = attendeeID;
		document.getElementById('subject').value = 'payEvent';
		document.forms['payment'].submit();
	}
}
function payPizza(attendeeID)
{
	var answer = confirm("Please confirm this person's Pizza Total payment");

	if (answer == true)
	{
		document.getElementById('attendeeID').value = attendeeID;
		document.getElementById('subject').value = 'payPizza';
		document.forms['payment'].submit();
	}
}
</script>
</head>
<body>
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content" style='min-height: 600px; width: 900px;'>





<!-- Check for errors and print out message -->
<?php	
	if (isset($_SESSION['errMsg']))
	{
		echo $_SESSION['errMsg'];
		unset($_SESSION['errMsg']);
	}

	// GET ALL OF [current] EVENT's ATTENDEE STATUS'S
	$eventRow = getThisEventRow($db);
	
	if (empty($eventRow))
	{
		$eventName = 'There are no current events at this time';
		$go = 0;
	}
	else
	{
		$eventName = $eventRow['event_name'];
		$eventID = $eventRow['eventID'];

		// GET ALL ATTENDEE'S RELATING TO THIS EVENT
		$get = "SELECT * FROM attendee WHERE eventID='".$eventID."'";
		$result = $db->query($get);
		$go = 1;
	}

	echo '<div align="center">Payment list for: ';
	echo '<font class="subtitle" style="font-size: 16pt;">';
	echo '<b>'.$eventName . '</b> ';
	echo '</font></div>';
?>

<?php 
if ($go == 1)
{
?>
<table class='pizzaOrder' rules='rows' style='width: 900px;'>
<tr style='background-color: black; color: white;'>
	<th align='left'>Name</th>
	<th align='left'>Email</th>
	<th>Mobile</th>
	<th> IRC Nickname </th>
	<th>Seat #</th>
	<th>Event Pay</th>
	<th style="border-left: 1px solid black">Pizza Total</th>
	<th>Pizza Pay</th>
</tr>

<?php 
	if ($result->num_rows == 0)
	{
		echo '<tr><td colspan="7"><i>There are no attendee registrations at this time</i></td></tr>';
	}
	else
	{
		for($i=0; $i<$result->num_rows; $i++)
		{
			$row = $result->fetch_assoc();
			$attendeeID = $row['attendeeID'];
			$seatID = $row['seatID'];
				if ($seatID == '')
				{
					$seatID = '-';
				}

			$clientID = $row['clientID'];
			if ($row['paid'] == 0)
			{
				$button = "<input type='button' name='payEvent' value='  Pay Now  ' onclick='payEvent(".$attendeeID.")' />";
				$eventPaid = $button;
			}
			else if ($row['paid'] == 1)
			{
				$eventPaid = "<img src='/cassa/images/layers/tick.png' title='This event has been paid for' />";
			}

			// GET ALL [this] CLIENT'S INFORMATION
			$get = "SELECT * FROM client WHERE clientID='".$clientID."'";
			$resultC = $db->query($get);
			$rowC = $resultC->fetch_assoc();
				$name = $rowC['first_name'] . ' ' .$rowC['last_name'];
				$email = $rowC['email'];
				$irc = $rowC['irc'];
				$mobile = $rowC['mobile'];
			
			// GET ALL [this] ATTENDEE'S PIZZA LINE ORDERS
			$get = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
			$resultM = $db->query($get);
			
			if ($resultM->num_rows == 0) 
			{
				$pizzaPaid = 'No pizza Menu for this event';
			}
			else 
			{
				$rowM = $resultM->fetch_assoc(); $menuID = $rowM['menuID'];
				$get = "SELECT * FROM pizza_order WHERE menuID='".$menuID."' AND attendeeID='".$attendeeID."'";
				$resultP = $db->query($get);
				
				if ($resultP->num_rows == 0)
				{
					$pizzaPaid = 'No pizza orders';
				}
				else
				{
				
					$pizzaTotal = 0;
					for ($x=0; $x<$resultP->num_rows; $x++)
					{
						$rowP = $resultP->fetch_assoc();
						$pizzaID = $rowP['pizzaID'];
						$quantity = $rowP['quantity'];
						
						// GET PIZZA PRICE
						$get = "SELECT * FROM pizza_type WHERE pizzaID='".$pizzaID."'";
						$resultPP = $db->query($get);
						$rowPP = $resultPP->fetch_assoc();
						$price = $rowPP['price'];

						$tempPrice = $quantity*$price;
						$pizzaTotal = $pizzaTotal + $tempPrice;
					}

					// PIZZA PAID
					$rowSinglePizza = $resultP->fetch_assoc();
					$pizzaPaid = $rowP['paid_pizza'];
					if ($pizzaPaid == 0)
					{
						$button = "<input type='button' name='payEvent' value='  Pay Now  ' onclick='payPizza(".$attendeeID.")' />";
						$pizzaPaid = $button;
					}
					else if ($pizzaPaid == 1)
					{
						$pizzaPaid = "<img src='/cassa/images/layers/tick.png' title='This pizza total has been paid for' />";
					}
				}
			}

			echo '<tr>';
				echo '<td style="min-width:120px;">'.$name.'</td>';
				echo '<td style="min-width:120px;">'.$email.'</td>';
				echo '<td align="center" style="min-width:60px;">'.$mobile.'</td>';
				echo '<td align="center" style="min-width:60px;">'.$irc.'</td>';
				echo '<td align="center" style="min-width:60px;">'.$seatID.'</td>';
				echo '<td align="center" style="min-width:60px;">'.$eventPaid.'</td>';

				if ($pizzaPaid == 'No pizza orders')
				{
					echo '<td align="center" style="min-width:120px; border-left: 1px solid black" colspan="2">'.$pizzaPaid.'</td>';
				}
				else
				{
					echo '<td align="center" style="min-width:60px; border-left: 1px solid black">$';
					echo number_format($pizzaTotal, 2).'</td>';
					echo '<td align="center" style="min-width:120px;">'.$pizzaPaid.'</td>';
				}
			echo '</tr>';
		}
	}
}
?>
</table>


<form name='payment' method='post' action='MANpay.php'>
	<input type='hidden' name='attendeeID' id='attendeeID' />
	<input type='hidden' name='subject' id='subject' />
</form>








<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

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