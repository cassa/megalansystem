<!-- //******************************************************

// Name of File: selectTournament.php
// Revision: 1.0
// Date: 05/06/2012
// Author: Tinashe Masvaure
// Modified: Quintin Maseyk 07/06/2012

//***********************************************************
//This script works in conjuction with the MANtournament.php script and contact.php.
//it acts as partner script and provides the details of the tournament
//selected in the list box. It returns the data in an AJAX connection.
//
//It uses $_POST in this instance rather than $_GET as there is
//a need for security. It returns a non-editable HTML table.
//********** Start of select tournament script **************-->
<!--*******************************************************-->


<?php 
	session_start();
    include("../includes/conn.php");            // Include the db connection

	// SECURE AND UNIFORM POST VARIABLE
	$_POST = array_map("trim", $_POST);
	$_POST = array_map("mysql_real_escape_string", $_POST);

	// SETUP LOCAL VARIABLES
	if (isset($_POST['tournID'])){ $tournID = $_POST['tournID']; }
    $queryType = $_POST['queryType'];           // Retrieve the query Identifier.
	




function getCurrentTourn($db)
{
	// AVAILABLE EVENTS
	$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_completed=0 ORDER BY startDate ASC";
	$result = $db->query($query);

	if ($result->num_rows > 0)
	{
		$row = $result->fetch_assoc();

		// GET TOURNAMENT
		$query = "SELECT * FROM tournament WHERE eventID=".$row['eventID']."";
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		$tournID = $row['tournID'];
		draw_tournament_table($db, $tournID);
	}
}

function draw_tournament_table($db, $tournID)
{
	draw_current_tournament($db);

	//Create the general select query.
	$query = "SELECT * FROM tournament WHERE tournID='".$tournID."' ORDER BY start_time ASC";
	$result = $db->query($query);
	
	if ($result->num_rows == 0)
	{
		echo '<div style="border: 1px solid black; height: 30px;"><i>There are no tournaments in the system</i></div>';
		die();
	}

	//use it first for the title
	$row1 = $result->fetch_array(MYSQLI_BOTH);                        
	
	//then close it ready for the next execution
	$result->close();                                                 
	$result = $db->query($query);     
	
	// DECLARE MOUSE EVENTS
	$on = 'this.src="../images/buttons/edit_dwn.png"';
	$off = 'this.src="../images/buttons/edit_up.png"';
?>
	<br /><br />


	<table class="pizzaOrder" style="width: 650px;" border='0'>


	<tr><td colspan="2" id="headCell_left">
		&nbsp;&nbsp;<font class='subtitle' style="font-size: 14pt;"><?php echo $row1['name']; ?></font></td>
	
	<td id="headCell_right">
		<img class="pointer" 
			 src="../images/buttons/edit_dwn.png" 
			 width="30px" height="30px"
			 title="Click to edit this tournament" 
			 onclick="editTournament(' <?php echo $row1['tournID']; ?> ')" 
			 onmouseover='<?php echo $off; ?>' onmouseout='<?php echo $on; ?>' /></td>
	</tr>
			
<?php 
	// While Loop starts here - 
	// Retrieve the data for the table. There should only be one row.
	while($row = $result->fetch_assoc())                            
	{
		if ($row['description'] == ''){	$row['description'] = '-'; }

		echo '<tr>';
			echo '<td width="170px" style="vertical-align: top; border-bottom: 1px solid black;">Description: </td>';
			echo '<td colspan="2" align="left" style="border-bottom: 1px solid black;"><textarea class="tournDescriptionRead">' . $row['description'] . '</textarea></td>';
		echo '</tr>';    

		echo '<tr>';
			echo '<td>Held on day: </td>';
			echo '<td colspan="2" align="left">' . $row['day'] . '</td>';
		echo '</tr>';    

		echo '<tr>';
			echo '<td>Start Time: </td>';
			echo '<td colspan="2" align="left">' . substr($row['start_time'], 0, 5) . '</td>';
		echo '</tr>';    
		
		echo '<tr>';
			echo '<td>End Time: </td>';
			echo '<td colspan="2" align="left">' . substr($row['end_time'], 0, 5) . '</td>';
		echo '</tr>';       

		echo '<tr>';
			echo '<td>Winner: </td>';
			echo '<td colspan="2" align="left">' . $row['winner'] . '</td>';
		echo '</tr>';
													   
	
		// If the tournament has started place the stop event button in the table.
		if($row['started'] == 1)
		{
			$on = 'this.src="../images/buttons/stop_dwn.png"';
			$off = 'this.src="../images/buttons/stop.png"';

			echo '<tr>';
				echo '<td>Started: </td>';
				echo '<td>Yes</td>';
					echo '<td><img src="../images/buttons/stop_dwn.png" class="pointer"';
						echo 'width="30px" height="30px"'; 
						echo 'alt="" onclick="stopTournament(' . $row['tournID'] . ')" ';
						echo 'onmouseover='.$off.' onmouseout='.$on.' /></td>';
			echo '</tr>';
		}

		// If the tournament has not started place the start event button in the table. 
		elseif ($row['started'] == 0)
		{
			$on = 'this.src="../images/buttons/start_dwn.png"';
			$off = 'this.src="../images/buttons/start.png"';

			echo '<tr>';
				echo '<td>Tournament Started: </td>';
				echo '<td>No</td>';
				echo '<td><img src="../images/buttons/start_dwn.png" class="pointer"';
						echo 'width="30px" height="30px"';
						echo 'alt="" onclick="startTournament(' . $row['tournID'] . ')"';
						echo 'onmouseover='.$off.' onmouseout='.$on.' /></td>';
			echo '</tr>';
		}

		// If the tournament has completed or been stopped. 
		elseif ($row['started'] == 2)
		{
			$on = 'this.src="../images/buttons/start_dwn.png"';
			$off = 'this.src="../images/buttons/start.png"';

			echo '<tr>';
				echo '<td>Tournament Started: </td>';
				echo '<td colspan="2">Finished</td>';
			echo '</tr>';
		}

	}//While Loop ends here

	echo '</table>';
	echo '<br />';
}








// DELETE TOURNAMENT
if($queryType == "delete")
{
	$tournID = $_POST['tournID'];
	$query = "DELETE FROM tournament WHERE tournID='".$tournID."';";
	$result = $db->query($query);

	// DISPLAY CURRENT EVENT->TOURNAMENT
	getCurrentTourn($db);
}








// INSERT TOURNAMENT
else if($queryType == "insert")
{
	// SETUP SUBJECTIVE VARIABLES
	$eventID = $_POST['eventID'];
	$days = $_POST['tournDays'];
	$name = $_POST['name'];
	$description = substr($_POST['description'], 0 , 255);
	$startTime = $_POST['startTime'];
	$endTime = $_POST['endTime'];
	$_SESSION['errMsg'] = '';

	// CHECK IF TOURNAMENT EXISTS
	$check = "SELECT * FROM tournament WHERE name='".$name."'";
	$result = $db->query($check);

	if ($result->num_rows > 0)
	{
		echo '<font class="error">This tournament already exists!</font>';
	}
	else
	{
		// VALIDATE INPUTS
		if ($eventID == '')
		{
			$_SESSION['errMsg'] .= '<br />No event was selected';
		}
		if ($days == '')
		{
			$_SESSION['errMsg'] .= '<br />This tournament be on a particular day';
		}
			if (!is_numeric($days))
			{
				$_SESSION['errMsg'] .= "<br />Days must be numeric only. Eg. '2'";
			}
		if ($name == '')
		{
			$_SESSION['errMsg'] .= '<br />No event name was entered';
		}
		if ($startTime < '00:00' && $startTime > '24:00' || $endTime > '00:00' && $endTime > '24:00')
		{
			$_SESSION['errMsg'] .= '<br />Start and End times must be within 24 hours';
		}
		if ($startTime == '00:00' && $endTime == '00:00')
		{
			$_SESSION['errMsg'] .= '<br />Start time and End time cannot be the same times';
		}

		// IF ERROR(S) ARE TRUE, DISPLAY ERROR MESSAGE 
		if ($_SESSION['errMsg'] != '')
		{
			echo '<div class="error" align="left">'.$_SESSION['errMsg'].'</div>';
		}
		else
		{
			// INSERT INTO DATABASE
			$insert = "INSERT INTO tournament (eventID, name, description, start_time, end_time, winner, started, finished) ";
			$insert .= "VALUES ('".$eventID."','".$name."','".$description."','".$startTime."','".$endTime."', '', 0, 0)";
			$result = $db->query($insert);

			// DISPLAY CURRENT EVENT->TOURNAMENT
			getCurrentTourn($db);
		}
	}
}








// UPDATE TOURNAMENT
else if($queryType == "update")
{
	// SETUP SUBJECTIVE VARIABLES
	$tournID = $_POST['tournID'];
	$day = $_POST['day'];
	$name = $_POST['name'];
	$description = substr($_POST['description'], 0, 255);
	$startTime = $_POST['startTime'];
	$endTime = $_POST['endTime'];
	$_SESSION['errMsg'] = '';


	// CHECK IF TOURNAMENT EXISTS
	$check = "SELECT * FROM tournament WHERE name='".$name."' AND tournID !='".$tournID."'";
	$result = $db->query($check);

	if ($result->num_rows > 0)
	{
		echo '<font class="error">This tournament name already exists!</font>';
	}
	else
	{
		// VALIDATE INPUTS
		if ($name == '')
		{
			$_SESSION['errMsg'] .= '<br />No event name was entered';
		}
		if ($day == '')
		{
			$_SESSION['errMsg'] .= '<br />Day this tournament is held on cannot be blank';
		}
			if (!is_numeric($day))
			{
				$_SESSION['errMsg'] .= '<br />Day this tournament is held must only be numeric';
			}
		if ($startTime < '00:00' && $startTime > '24:00' || $endTime > '00:00' && $endTime > '24:00')
		{
			$_SESSION['errMsg'] .= '<br />Start and End times must be within 24 hours';
		}
		if ($startTime == '00:00' && $endTime == '00:00')
		{
			$_SESSION['errMsg'] .= '<br />Start time and End time cannot be the same times';
		}

		// IF ERROR(S) ARE TRUE, DISPLAY ERROR MESSAGE 
		if ($_SESSION['errMsg'] != '')
		{
			echo '<div class="error" align="left">'.$_SESSION['errMsg'].'</div>';
		}
		else
		{
			// UPDATE TOURNAMENT
			$update = "UPDATE tournament SET name='".$name."', day='".$day."', description='".$description."', start_time='".$startTime."', end_time='".$endTime."' WHERE tournID='".$tournID."'";
			$result = $db->query($update);
			$queryType='0';
		}
	}
}







//If querytype = start then change the tournament to started											
	if ($queryType == "start")
    {
        $query2  =  "UPDATE tournament "; 
        $query2 .=  "SET started = 1 ";
        $query2 .=  "WHERE tournID =" . $tournID;
        
		//Then Execute the second Query then move on
        $result = $db->query($query2);  
		$queryType='0';
    }
	//If querytype = end then change the tournament to finished
    else if ($queryType == "stop")
    {
        $_SESSION['errMsg'] = "";
        $query2  = "UPDATE tournament "; 
        $query2 .=	"SET started = 2 ";
        $query2 .=	"WHERE tournID =" . $tournID;

        $result = $db->query($query2);
        	
		$queryType='0';
		//Then Execute the Query then move on
    }







// DRAW CURRENT TOURNAMENT TABLE [top]
function draw_current_tournament($db)
{
?>
<table class='pizzaOrder'>
<tr>
	<td class='MANheader' width='600px' colspan='2'>
	&nbsp;&nbsp;Current Tournaments: 
	<font size="2" class="subtitle">Click on a tournament to see more information below</font></td>
</tr>

<?php
	// AVAILABLE EVENTS
	$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_completed != 2 ORDER BY startDate ASC";
	$result = $db->query($query);

	// FOR EACH AVAILABLE EVENTS, LOOP THROUGH AND DISPLAY EACH TOURNAMENT
	for ($x=0; $x<$result->num_rows; $x++)
	{
		// EVENT ROW
		$row = $result->fetch_assoc();
		$eventName = $row['event_name'];

		// GET TOURNAMENT
		$queryTourn = "SELECT * FROM tournament WHERE eventID=".$row['eventID']." ORDER BY start_time ASC";
		$resultTourn = $db->query($queryTourn);
		
		// FOR EACH TOURNAMENT IN [this] EVENT, DISPLAY
		for ($i=0; $i<$resultTourn->num_rows; $i++) 
		{
			$rowTourn = $resultTourn->fetch_assoc();    

			// GET START / END TIME
			$startTime = substr($rowTourn['start_time'], 0, 5);
			$endTime = substr($rowTourn['end_time'], 0 , 5);
			echo '<tr>';
				echo '<td width="70px">';
					echo '<div style="position: relative; top: 5px;">';
				?>
					<!-- // DELETE EVENT BUTTON -->
					<img class="pointer"
						src="../images/buttons/delete_60.png"';
						alt="Delete this tournament" 
						onclick="deleteTournament(<?php echo $rowTourn["tournID"]; ?>, '<?php echo $rowTourn["name"]; ?>')" />
				<?php
					echo '</div>';
				echo '</td>';
				echo '<td class="pointer" id="tournRow_'.$i.'" onclick="getTournament('.$rowTourn["tournID"].')">';
					echo $rowTourn['name'];
					echo '&nbsp;-&nbsp;<font size="1"><b>'.$eventName.'</b> ['.$startTime.' - '.$endTime.']</font>';
				echo '</td>';
			echo '</tr>';
		}
	}
echo '</table>';
}








// DRAW BASIC TOURNAMENT TABLE [bottom]
if($queryType == '0')
{
	draw_tournament_table($db, $tournID);
}








// DRAW EDIT TOURNAMENT TABLE
else if($queryType == '2')
{
	// DRAW TOP TABLE FIRST
	draw_current_tournament($db);

	// PREPARE EDIT TABLE
	$sql = "SELECT * FROM tournament WHERE tournID='".$tournID."'";
	$result = $db->query($sql);                                             
	$num = $result->fetch_array(MYSQLI_BOTH);    
?>

    <br />
    <br />
         
<table class="pizzaOrder" style='width: 650px;' border='0'>
	<tr><td colspan="2" class="tableTitle">&nbsp;&nbsp;Edit Tournament: 
		<font class='subtitle'><?php echo $num['name']; ?></font></td>
	</tr>

	<tr>
		<td width='200px'>Tournament Name: </td>
		<td><input type="text" name="E_name" id='E_name' 
				   value="<?php echo $num['name']?>" 
				   maxlength="128" /></td>
	</tr>

	<tr>
		<td>Held on day: </td>
		<td><input type="text" name="E_day" id='E_day' 
				   value="<?php echo $num['day']; ?>" 
				   size="1" maxlength="1" /></td>
	</tr>

	<tr>
		<td width='200px' style='vertical-align: top'>Description: </td>
		<td><textarea name="E_description" id='E_description' 
				   class="tournDescription"
				   onkeyup="checkTextEdit()" /><?php echo $num['description']?></textarea>
		</td>
	</tr>

	<tr>
		<td>Start Time: </td>
		<td><input type="text" name="E_start_time" id='E_start_time' 
				   value="<?php echo substr($num['start_time'], 0, 5) ?>" 
				   size="5" maxlength="5" /></td>
	</tr>

	<tr>
		<td>End Time: </td>
		<td><input type="text" name="E_end_time" id='E_end_time' 
				   value="<?php echo substr($num['end_time'], 0, 5) ?>" 
				   size="5" maxlength="5" /></td>
	</tr>

	<tr><td colspan='2' align='center'>
		<br />
		<input type='submit' value='Update' onclick='updateTourn(<?php echo $num['tournID']; ?>)' /></td>
	</tr>
</table>
<br />
<?php                 
}
?>