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


	$_SESSION['title'] = "Manage Pizzas | MegaLAN";		// Declare this page's Title
	include("../includes/template.php"); 				// Include the template page
	include("../includes/conn.php");					// Include database connection


	if (isset($_POST['action']))
	{
		// IF [attendee] PAYS FOR PIZZA LINE
		if ($_POST['action'] == 'payPizza')
		{
			// UPDATE PIZZA ORDER ROW
			$update = "UPDATE pizza_order SET paid_pizza=1 WHERE attendeeID='".$_POST['attendeeID']."' AND pizzaID='".$_POST['pizzaID']."'";
			$result = $db->query($update);
		}
	}


?>

<!-- //******************************************************

// Name of File: menumanagement.php
// Revision: 1.0
// Date: 
// Author: Luke Spartalis
// Modified: Quintin Maseyk 03/05/2012

//***********************************************************

//*************** Start of CREATE PIZZA ******************* -->

<head>
<script type='text/javascript'>
function editRow(x)
{
	// DISPLAY [this] ROW FROM TEXT -> EDITABLE
	document.getElementById(x+"_normal").style.display = 'none';
	document.getElementById(x+"_edit").style.display = 'block';
}
function closeRow(x)
{
	// DISPLAY [this] ROW FROM TEXT -> EDITABLE
	document.getElementById(x+"_normal").style.display = 'block';
	document.getElementById(x+"_edit").style.display = 'none';
}
function updateRow(index, message)
{
	var answer = confirm(message);
	if (answer == true)
	{
		// SETUP FORM WITH INPUTS TO SEND TO SERVER
		var id = document.getElementById('pizzaID_'+index).value;
		var name = document.getElementById('pizza_name_'+index).value;
		var description = document.getElementById('description_'+index).value;
		var price = document.getElementById('price_'+index).value;
		var params = "i="+id+"&name="+name+"&description="+description+"&price="+price+"&action=updateRow";

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
				document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
			}
		}

		//Now we have the xmlhttp object, get the data using AJAX.
		xmlhttp.open("POST","selectPizza.php",true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");
		xmlhttp.send(params);
	}
}
function makeRequest(params, message)
{
	var answer = confirm(message);
	if (answer == true)
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
				document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
			}
		}

		//Now we have the xmlhttp object, get the data using AJAX.
		xmlhttp.open("POST","selectPizza.php",true);
		xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
		xmlhttp.setRequestHeader("Content-length", params.length);
		xmlhttp.setRequestHeader("Connection", "close");
		xmlhttp.send(params);
	}
}

function getRequest(params, action)
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
			document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
		}
	}

	var menuID = document.getElementById('currentMenu').value;

	//Now we have the xmlhttp object, get the data using AJAX.
	params = "menuID=" + menuID + "&pizzaID=" + params + "&action=" + action;		
	xmlhttp.open("POST","selectPizza.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}


function createPizza()
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
			document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
		}
	}

	// GET FORM OBJECTS
	var name = document.getElementById('new_pizza_name').value;
	var description = document.getElementById('new_description').value;
	var price = document.getElementById('new_price').value;


	//Now we have the xmlhttp object, get the data using AJAX.
	params = "name=" + name + "&description=" + description + "&price=" + price + "&action=createPizza";		
	xmlhttp.open("POST","selectPizza.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
	parent.jQuery.colorbox.close();
}
function generalQuery(params)
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
			document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
		}
	}

	//Now we have the xmlhttp object, get the data using AJAX.
	xmlhttp.open("POST","selectPizza.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
	parent.jQuery.colorbox.close();
}
function createPizzaMenu(eventID)
{
	var menuName = document.getElementById('pizza_menu_name').value;
	var params = "menuName=" + menuName + "&eventID=" + eventID + "&action=pizzaMenu";

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
			document.getElementById("pizza_menuTable").innerHTML=xmlhttp.responseText;
		}
	}

	//Now we have the xmlhttp object, get the data using AJAX.
	xmlhttp.open("POST","selectPizza.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
	parent.jQuery.colorbox.close();
}
function payPizza(pizzaID, attendeeID)
{
	// TO SEND BY AJAX
	/* COMMENTED OUT 
		var params = "pizzaID=" + pizzaID + "&attendeeID=" + attendeeID + "&action=payPizza";
		generalQuery(params);
	*/

	// TO SEND SINGLY
	document.getElementById('pizzaID').value = pizzaID;
	document.getElementById('attendeeID').value = attendeeID;
	document.forms['payPizzaForm'].submit();
}

$(document).ajaxStop(function(){
	window.location.reload();
});

$(document).ready(function(){
// PIZZA MENU
	$(".inlineD").colorbox({inline:true, width:"250px", height:"250px"}); 

// CREATE NEW PIZZA
	$(".inline").colorbox({inline:true, width:"280px", height:"350px", scrolling: false});

// PIZZA ORDER SUMMARY
	$(".inlineB").colorbox({inline:true, width:"700px", height:"600px"});

// PIZZA ORDER BREAK DOWN
	$(".inlineC").colorbox({inline:true, width:"840px", height:"600px"}); 
});
</script>

</head>
<body onload="getRequest(document.getElementById('currentMenu').value)">
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content">





<!-- Check for errors and print out message -->
<?php	
	if (isset($_SESSION['errMsg']))
	{
		echo $_SESSION['errMsg'];
		unset($_SESSION['errMsg']);
	}
?>





<!-- CREATE A NEW PIZZA FORM -->
<?php
	// CHECK IF [current] EVENT HAS A PIZZA MENU
	$eventID = getThisEvent($db);
		
	$check = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
	$result = $db->query($check);

	if ($result->num_rows == 0)
	{
?>
<!-- HREF : OPENS INLINE 'CREATE NEW PIZZA' FORM -->
<a class='inlineD' href='#createPizzaMenu'>Create new pizza menu</a>


<br /><br />


<div style='display: none;'>
	<div id='createPizzaMenu' style='padding: 20px; line-height: 15pt;'>
	<h3>Create a new pizza menu</h3>
	<?php
		// GET ALL CURRENT EVENTS
		$get = "SELECT * FROM event WHERE eventID='".$eventID."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();

		echo '<h3>For Event: <font class="subtitle">'.$row['event_name'].'</font></h3>';
	?>

	<br />

		Menu Name:<br />
		<input type="text" name="pizza_menu_name" id="pizza_menu_name" maxlength="28" size="28" />

	<br /><br />

		<input type="button" name="submit" value=" Add Pizza Menu " onclick="createPizzaMenu(<?php echo $eventID; ?>)" />
	</div>
</div>
<?php
	}
?>





<!-- HREF : OPENS INLINE 'CREATE NEW PIZZA' FORM -->
<a class='inline' href='#createPizza'>Create new pizza</a>

<!-- CREATE A NEW PIZZA FORM -->
<div style='display: none;'>
	<div id='createPizza'>
	<h3>Create a new pizza</h3>


	<br /><br />


		Pizza Name:<br />
		<input type="text" name="new_pizza_name" id="new_pizza_name" maxlength="32" size="32" />
		<br /><br />
		
		Description:<br />
		<input type="text" name="new_description" id="new_description" maxlength="128" size="32" />
		<br /><br />
		
		Price $:<br />
		<input type="text" name="new_price" id="new_price" maxlength="5" size="5" />
		<br /><br />
	
		<input type="button" name="submit" value="Add Pizza" onclick="createPizza()" />
	</div>
</div>





<!-- DISPLAY CURRENT MENU -->
<?php
	// GET [current] EVENT
	$eventID = getThisEvent($db);

	// GET [this] EVENTS MENU
	$query = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
	$result = $db->query($query);
	$row = $result->fetch_assoc();

	echo "<input type='hidden' name='currentMenu' id='currentMenu' value='".$row['menuID']."' />";
?>





<!-- DISPLAY AJAX: [this] PIZZA MENU -->
<div id='pizza_menuTable' style='clear: right;'></div>





<br /><br/><hr /><br /><br />





<div id='orderSummaryDIV'>
<a class='inlineB' href='#summaryPizza'>
<img class='pointer' border='0' height="50px" width="50px"
	 src='../images/layers/form.png' 
	 alt='Click here to see pizza order summary' />
	 Purchase Order</a>
</div>





<div id='orderBreakdownDIV'>
<a class='inlineC' href='#breakdownPizza'>
<img class='pointer' border='0' height="50px" width="50px"
	 src='../images/layers/form.png' 
	 alt='Click here to see pizza order break down' />
	 Order Break Down</a>
</div>





<div style='display: none;'>
	<div id='summaryPizza'>
	<br />

<?php 
	// GET [current] EVENT 
	$eventID = getThisEvent($db);
	$row = getThisEventRow($db);
	$eventStartDate = dateToScreen($row['startDate']);

	// GET [this] EVENTS MENU
	$getmenuID = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
	$result = $db->query($getmenuID);
	$row = $result->fetch_assoc();
	$menuID = $row['menuID'];
	$menuName = $row['menu_name'];

	$grandTotal = 0;
?>


	<!-- ORDER HEADER -->
	<div class='orderLogo'></div>
	<div class='orderHeader'>
		<div style='float: left;'>
			 Purchase Order: 
			 <font class='subtitle' style='font-size: 18pt;'>
				<?php echo $menuName; ?> 
			</font>
		</div>
		<div style='float: right;'>
			<?php echo $eventStartDate; ?>
		</div>
	</div>

	<br />
	<br />

	<!-- ORDER LINE TABLE -->
	<table class='pizzaOrder' border='1'>
	<tr>
		<td class='MANheader' width='300px'>Name</td>
		<td class='MANheader' width='60px'>QTY</td>
		<td class='MANheader' width='60px'>Price ($)</td>
		<td class='MANheader' width='70px'>Total ($)</td>
	</tr>

	<?php 
		// GET [this] EVENTS MENU PIZZA ORDER SUMMARY
		$getpizzaID = "SELECT DISTINCT pizzaID FROM pizza_order WHERE menuID='".$menuID."' ORDER BY pizzaID ASC";
		$result = $db->query($getpizzaID);

		if ($result->num_rows == 0)
		{
			echo '<tr><td colspan="4"><i>There are no orders for this pizza menu.</i></td></tr>';
		}
		else
		{
			// FOR EVERY 'DISTINCT' PIZZA TYPE, FETCH THE SUM OF EACH
			for ($i=0; $i<$result->num_rows; $i++) 
			{
				$row = $result->fetch_assoc();
				$thisPizza = $row['pizzaID'];
				
				// GET THE SUM OF [this] PIZZA TYPE
				$sum = "SELECT sum(quantity) as pizzaSum FROM pizza_order WHERE pizzaID='".$thisPizza."'";
				$resultSum = $db->query($sum);
				$rowSum = $resultSum->fetch_assoc();

				// [this] PIZZA TYPE
				$pizzaID[$i] = $thisPizza;

				// [this] PIZZA TYPE's QUANTITY
				$pizzaSum[$i] = $rowSum['pizzaSum'];
			}

			// DISPLAY SUM FOR THIS ORDER
			for ($i=0 ; $i<sizeof($pizzaID); $i++)
			{
				// GET [this] PIZZA's NAME
				$get = "SELECT `pizza_name`, `description` FROM pizza_type WHERE pizzaID='".$pizzaID[$i]."'";
				$result = $db->query($get);
				$row = $result->fetch_assoc();
				$pizzaName = $row['pizza_name'];
				$description = $row['description'];
				
				// GET [this] PIZZA's PRICE
				$get = "SELECT `price` FROM pizza_type WHERE pizzaID='".$pizzaID[$i]."'";
				$result = $db->query($get);
				$row = $result->fetch_assoc();
				$price = $row['price'];
				$total = $pizzaSum[$i] * $price;
				$grandTotal = $grandTotal + $total;

				echo '<tr>';
				echo '<td>'.ucwords($pizzaName).' <font size="2">('.$description.')</font></td>';
				echo '<td>'.$pizzaSum[$i].'</td>';
				echo '<td>'.$price.'</td>';
				echo '<td>'.number_format($total, 2).'</td>';
				echo '</tr>';
			}
		}
	?>
	<?php

	?>
		<tr><td colspan="4"><hr /></td></tr>
		<tr><td colspan="4" align="right" style="padding-right: 45px;">
			GRAND TOTAL: $<?php echo number_format($grandTotal, 2); ?></td></tr>
	</table>

	<!-- PRINT THIS PAGE -->
	<div class='print' id='printButton'>
		<a href="javascript:window.print()">Print This Page</a>
	</div>
	</div>
</div>
















<div style='display: none;'>
	<div id='breakdownPizza'>
	<br />
	<?php 
		// GET EVENT WHERE EVENT IS NEXT TO START
		$eventID = getThisEvent($db);
		$row = getThisEventRow($db);
		$eventStartDate = dateToScreen($row['startDate']);

		// GET [this] EVENTS MENU
		$getmenuID = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
		$result = $db->query($getmenuID);
		$row = $result->fetch_assoc();
		$menuID = $row['menuID'];
		$menuName = $row['menu_name'];

		$grandTotal = 0;
	?>

	<!-- ORDER HEADER -->
	<div class='orderLogo'></div>
	<div class='orderHeader'>
		<div style='float: left;'>
			Order Break Down: 
			<font class='subtitle' style='font-size: 18pt;'>
				<?php echo $menuName; ?> 
			</font>
		</div>
		<div style='float: right;'>
			<?php echo $eventStartDate; ?>
		</div>
	</div>

	<br />
	<br />

	<!-- ORDER LINE TABLE -->
	<table class='pizzaOrder' style='width:750px;' border='1'>
	<tr>
		<td class='MANheader' width='250px'>Attendee <font size="2">(mobile)</font></td>
		<td class='MANheader' width='60px' align="center">Seat #</td>
		<td class='MANheader' width='150px'>Pizza</td>
		<td class='MANheader' width='60px' align="center">Price</td>
		<td class='MANheader' width='60px' align="center">QTY</td>
		<td class='MANheader' width='100px' align="center">Total</td>
		<td class='MANheader' width='60px' align="center">Paid</td>
		<td class='MANheader' width='60px' align="center">&nbsp;</td>
	</tr>
	<?php
		// GET [current] EVENTS ORDER BREAK DOWN
		$get = "SELECT * FROM pizza_order WHERE menuID='".$menuID."' ORDER BY attendeeID ASC";
		$result = $db->query($get);
		
		if ($result->num_rows == 0)
		{
			echo '<tr><td colspan="8"><i>There are no orders for this pizza menu.</i></td></tr>';
		}
		else
		{
			$sameAttendee = 0;
			$attendeePrice = 0;

			for ($i=0; $i<$result->num_rows; $i++)
			{
				$row = $result->fetch_assoc();
				
			// I M P O R T A N T   R E F E R E N C I N G   V A R I A B L E S
				$attendeeID = $row['attendeeID'];
				$pizzaID = $row['pizzaID'];
				$pizzaQTY = $row['quantity'];
					// PAID PIZZA?
					if ($row['paid_pizza'] == 0)
					{
						$paid = "<font style='color: red;'><b>No</b></font>";
						$paidButton = '<input type="button" name="paid" value="Paid" onclick="payPizza('.$pizzaID.', '.$attendeeID.')" />';
					}
					else
					{
						$paid = "<font style='color: blue;'><b>Yes</b></font>";
						$paidButton = '<img src="/cassa/images/layers/tick.png" alt="This person has paid for this pizza" />';
					}


				if ($attendeeID == $sameAttendee)
				{
					$sameAttendee = $sameAttendee +1;
				}
				else
				{
					$sameAttendee = 0;
				}

				// GET ATTENDEE DETAILS --> GET CLIENT DETAILS
				$getAttendee = "SELECT * FROM attendee WHERE attendeeID='".$attendeeID."'";
				$resultAttendee = $db->query($getAttendee);
				$rowAttendee = $resultAttendee->fetch_assoc();
					$seatID = $rowAttendee['seatID'];
					$clientID = $rowAttendee['clientID'];
					$resultAttendee->close();

				// GET CLIENT DETAILS
				$getClient = "SELECT * FROM client WHERE clientID='".$clientID."'";
				$resultClient = $db->query($getClient);
				$rowClient = $resultClient->fetch_assoc();
					$name = $rowClient['first_name']. ' ' .$rowClient['last_name'];
					$mobile = $rowClient['mobile'];
					$irc = $rowClient['irc'];
					$resultClient->close();

				// GET PIZZA DETAILS
				$getPizza = "SELECT * FROM pizza_type WHERE pizzaID='".$pizzaID."'";
				$resultPizza = $db->query($getPizza);
				$rowPizza = $resultPizza->fetch_assoc();
					$pizzaName = $rowPizza['pizza_name'];
					$pizzaPrice = $rowPizza['price'];
					$pizzaDescription = $rowPizza['description'];
					$resultPizza->close();

				// GET LINE PRICING
				$lineTotal = $pizzaQTY * number_format($pizzaPrice, 2);
				$grandTotal = $grandTotal + $lineTotal;

			// D I S P L A Y   I N F O R M A T I O N   I N   T A B L E
				echo '<tr class="breakDownRow">';
					echo '<td>'.$name.' <font size="2">('.$mobile.') IRC: '.$irc.' </font></td>';
					echo '<td align="center">'.$seatID.'</td>';
					echo '<td>'.$pizzaName.'</td>';
					echo '<td align="center">$'.$pizzaPrice.'</td>';
					echo '<td align="center">'.$pizzaQTY.'</td>';
					echo '<td align="center">$'.number_format($lineTotal, 2).'</td>';
					echo '<td align="center">'.$paid.'</td>';
					echo '<td align="center">'.$paidButton.'</td>';
				echo '</tr>';
			}
		}
	?>
		<tr><td colspan="8"><hr /></td></tr>
		<tr><td colspan="8" align="right" style="padding-right: 145px;">
			GRAND TOTAL: $<?php echo number_format($grandTotal, 2); ?></td></tr>
	</table>

	<form name='payPizzaForm' method='post' action='MANpizza.php'>
		<input type='hidden' name='attendeeID' id='attendeeID' value='' />
		<input type='hidden' name='pizzaID' id='pizzaID' value='' />
		<input type='hidden' name='action' id='action' value='payPizza' />
	</form>

	<!-- PRINT THIS PAGE -->
	<div class='print' id='printButton'>
		<a href="javascript:window.print()">Print This Page</a>
	</div>
	</div>
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