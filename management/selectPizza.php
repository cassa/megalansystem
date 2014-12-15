<?php 
	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	session_start();
	include("../includes/conn.php");					// Include database connection
	include("../includes/functions.php");				// Include general functions


	if (isset($_POST))
	{
		$_POST = array_map("mysql_real_escape_string", $_POST);
		$_POST = array_map("trim", $_POST);
	}

	if (isset($_POST['action']))
	{
		// IF [user] CLICKS TO ADD PIZZA TO 'CURRENT MENU'
		if ($_POST['action'] == 'add')
		{
			$menuID = $_POST['menuID'];
			$pizzaID = $_POST['pizzaID'];

			// CHECK IF THIS ALREADY EXISTS
			$check = "SELECT * FROM menu_items WHERE menuID = '".$menuID."' AND pizzaID = '".$pizzaID."'";
			$result = $db->query($check);

			if ($result->num_rows == 0)
			{
				// INSERT [this] PIZZA to [that] MENU
				$query = "INSERT INTO menu_items VALUES ('".$menuID."', '".$pizzaID."')";
				$result = $db->query($query);
			}
		}

		// IF [user] CLICKS TO DELETE PIZZA FROM 'CURRENT MENU'
		else if ($_POST['action'] == 'delete')
		{
			// DELETE [this] FROM [that] MENU
			$del = "DELETE FROM menu_items WHERE menuID='".$_POST['menuID']."' AND pizzaID='".$_POST['pizzaID']."'";
			$result = $db->query($del);
		}

		// IF [user] CLICKS TO DELETE PIZZA FOR 'PIZZA LIST'
		else if ($_POST['action'] == 'deletePizzaType')
		{
			// DELETE [this] PIZZA @ pizza_type
			$del = "DELETE FROM pizza_type WHERE pizzaID='".$_POST['pizzaID']."'";
			$result = $db->query($del);
		}
		
		// IF [user] CLICKS TO CREATE A NEW PIZZA
		else if ($_POST['action'] == 'createPizza')
		{
			// VALIDATE ALL INPUT FILES
			$_SESSION['errMsg'] = '';

			if ($_POST['name'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza name was entered</font>";
			}
			if ($_POST['description'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza desciption was entered</font>";
			}
			if ($_POST['price'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza price was entered</font>";
			}
			if (!is_numeric($_POST['price']))
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>Pizza price must be numeric</font>";
			}

			// Make each first letter of each words capital
			$_POST = array_map('strtolower', $_POST);
			$_POST = array_map('ucwords', $_POST);

			if (empty($_SESSION['errMsg']))
			{
				// CHECK IF PIZZA NAME ALREADY EXISTS
				$check = "SELECT * FROM pizza_type WHERE pizza_name = '".$_POST['name']."'";
				$result = $db->query($check);

				if ($result->num_rows != 0)
				{
					// THIS PIZZA ALREADY EXISTS
					$_SESSION['errMsg'] .= "<br /><font class='error'>This pizza already exists in the system!</font>";
				}
				else
				{
					// INSERT NEW PIZZA TO DATABASE
					$insert = "INSERT INTO pizza_type VALUES ('', '".$_POST['name']."', '".$_POST['description']."', '".$_POST['price']."')";
					$result = $db->query($insert);
				}
			}
		}

		// IF [user] CLICKS TO UPDATE PIZZA ROW
		else if ($_POST['action'] == 'pizzaMenu')
		{
			// Make each first letter of each words capital
			$_POST = array_map('strtolower', $_POST);
			$_POST = array_map('ucwords', $_POST);

			// VALIDATE
			$_SESSION['errMsg'] = '';

			if ($_POST['menuName'] == '')
			{
				$_SESSION['errMsg'] .= '<font class="error">Pizza Menu\'s must contain a valid name</font>';
			}
			if ($_POST['eventID'] == '-')
			{
				$_SESSION['errMsg'] .= '<font class="error">Pizza Menu\'s must be associated with an event</font>';
			}
			
			if ($_SESSION['errMsg'] == '')
			{
				// CHECK IF EVENT ALREADY CONTAINS A PIZZA ORDER
				$check = "SELECT * FROM pizza_menu WHERE eventID='".$_POST['eventID']."'";
				$resultCheck = $db->query($check);

				if ($resultCheck->num_rows == 0)
				{
					// INSERT
					$insert = "INSERT INTO pizza_menu (eventID, menu_name) VALUES ('".$_POST['eventID']."', '".$_POST['menuName']."')";
					$result = $db->query($insert);
				}
				else
				{
					$_SESSION['errMsg'] .= '<font class="error">You can only create one pizza menu per event.</font>';
				}
			}
		}
		
		// IF [user] CLICKS TO UPDATE PIZZA ROW
		else if ($_POST['action'] == 'updateRow')
		{
			// VALIDATE ALL INPUT FILES
			$_SESSION['errMsg'] = '';

			if ($_POST['name'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza name was entered</font>";
			}
			if ($_POST['description'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza desciption was entered</font>";
			}
			if ($_POST['price'] == '')
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>No pizza price was entered</font>";
			}
			if (!is_numeric($_POST['price']))
			{
				$_SESSION['errMsg'] .= "<br /><font class='error'>Pizza price must be numeric</font>";
			}

			// Make each first letter of each words capital
			$_POST = array_map('strtolower', $_POST);
			$_POST = array_map('ucwords', $_POST);

			if (empty($_SESSION['errMsg']))
			{
				// UPDATE THIS ROW
				$update = "UPDATE pizza_type SET pizza_name='".$_POST['name']."', description='".$_POST['description']."', price='".$_POST['price']."' WHERE pizzaID=".$_POST['i']."";
				$result = $db->query($update);
			}
		}

		// IF [attendee] PAYS FOR PIZZA LINE
		else if ($_POST['action'] == 'payPizza')
		{
			// UPDATE PIZZA ORDER ROW
			$update = "UPDATE pizza_order SET paid_pizza=1 WHERE attendeeID='".$_POST['attendeeID']."' AND pizzaID='".$_POST['pizzaID']."'";
			$result = $db->query($update);
		}
	}
?>
<table class='pizzaTable' border='0'>
<caption align='center'>Pizza List</caption>
<tr>
	<td width='140px' class='MANheader'>&nbsp;</td>
	<td width='200px' class='MANheader'>Pizza Name</td>
	<td width='300px' class='MANheader'>Description</td>
	<td width='80px' class='MANheader'>Price ($)</td>
</tr>


<?php 
	$query = "SELECT * FROM pizza_type";
	$result = $db->query($query);


for ($i=0; $i<$result->num_rows; $i++) // create a list of all pizza's in the database
{
	$row = $result->fetch_assoc();

	echo "<tr id='".$i."_normal'>";
		echo "<td>";
?>
		<!-- ADD PIZZA TO 'current menu' -->
		<img class="pointer button" 
			 src="../images/buttons/addTo.png" 
			 alt="Add this pizza to this events menu" 
			 onclick="getRequest(<?php echo $row['pizzaID']; ?>, 'add')" />

		<!-- CLICK TO MAKE THIS ROW EDITABLE -->
		<img class='pointer button' 
			 src='../images/buttons/edit_up.png' 
			 alt='Edit' 
			 onclick='editRow("<?php echo $i; ?>")' />
		
		<!-- DELETE PIZZA ENTIRELY -->
		<img class='pointer button'
			 src='../images/buttons/delete_up.png' 
			 alt='Delete' 
			 onclick='makeRequest("pizzaID=<?php echo $row['pizzaID']; ?>&action=deletePizzaType", "Please confirm to delete this pizza entirely")' />
<?php
		echo "</td>";
		echo "<td>"	. $row['pizza_name']."</td>";
		echo "<td>" . $row['description'] . "</td>";
		echo "<td>" . $row['price'] . "</td>";
	echo "</tr>";



	// [this] EDITABLE ROW
	// CREATE FORM FOR SUBMISSION
	echo "<tr id='".$i."_edit' style='display: none;'>";
		echo "<td>";
?>
		<img class='pointer button'
			 src='../images/buttons/save_up.png' 
			 alt='Save' 
			 onclick='updateRow("<?php echo $i; ?>", "Please confirm pizza changes")' />

		<img class='pointer button'
			 src='../images/buttons/delete_up.png' 
			 alt='Cancel' 
			 onclick='closeRow("<?php echo $i; ?>")' />

<?php
		echo "<input type='hidden' name='pizzaID_".$i."' id='pizzaID_".$i."' value='".$row['pizzaID']."' />";
		echo "</td>";

		echo "<td><input type='text' name='pizza_name_".$i."' id='pizza_name_".$i."' value='".$row['pizza_name']."' size='28' /></td>";
		echo "<td><input type='text' name='description_".$i."' id='description_".$i."' value='".$row['description']."' size='45' /></td>";
		echo "<td><input type='text' name='price_".$i."' id='price_".$i."' value='".$row['price']."' size='5' maxlength='5' /></td>";
	echo "</tr>";
}
?>
</table>






<br /><hr /><br />






<!-- DISPLAY CURRENT MENU -->
<?php
	$eventID = getThisEvent($db);
	$eventName = getThisEventName($db);

	// GET [this] EVENTS MENU
	$query = "SELECT * FROM pizza_menu WHERE eventID='".$eventID."'";
	$result = $db->query($query);
	$row = $result->fetch_assoc();

	echo "<h2 align='center' class='subtitle' style='font-size:14pt'>".$eventName."'s Pizza Menu: ".$row['menu_name']."</h2>";
	echo "<input type='hidden' name='currentMenu' id='currentMenu' value='".$row['menuID']."' />";
?>





<table class='pizzaTable' border='0'>
<tr>
	<td class='MANheader' width='80px'>&nbsp;</td>
	<td align='left' class='MANheader' width='200px'>Name</td>
	<td align='left' class='MANheader' width='340px'>Description</td>
	<td align='left' class='MANheader' width='80px'>Price ($)</td>
</tr>

<?php
	// IF 'current menu' IS TRIGGERED
	// DISPLAY [this] MENU ITEMS

	// [this] MENU 
	$menuID = $row['menuID'];

	if (empty($menuID))
	{
		echo '<tr><td colspan="4" height="60px"><i>';
		echo 'This event has no pizza menu yet.</i></td></tr>';
	}
	else
	{
		// GET ALL PIZZA ITEMS IN THIS MENU
		$select = "SELECT * FROM menu_items WHERE menuID='".$menuID."'";
		$result = $db->query($select);

		for ($i=0; $i<$result->num_rows; $i++)
		{
			$row = $result->fetch_assoc();

			// GET [this] MENUS PIZZA
			$pizzaID = $row['pizzaID'];

			// GET [this] PIZZA's DESCRIPTION
			$get = "SELECT * FROM pizza_type WHERE pizzaID='".$pizzaID."'";
			$resultPizza = $db->query($get);
			$rowPizza = $resultPizza->fetch_assoc();

			// SETUP DELETE BUTTON
			$onclick = "makeRequest('menuID=".$row['menuID']."&pizzaID=".$rowPizza['pizzaID']."&action=delete', 'Please confirm to remove this pizza from the current menu')";

			echo '<tr>';
			echo '<td><img class="pointer" src="../images/buttons/delete_60.png" alt="Remove this pizza" onclick="'.$onclick.'" />';
			echo '<td>'.$rowPizza['pizza_name'].'</td>';
			echo '<td>'.$rowPizza['description'].'</td>';
			echo '<td>$'.$rowPizza['price'].'</td>';
			echo '</tr>';
		}
	}
?>
</table>