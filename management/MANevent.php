<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();									// Start/resume THIS session

	// PAGE SECURITY
	if (!isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "Event Management | MegaLAN";  // Declare this page's Title
	include("../includes/template.php");                // Include the template page
	include("../includes/conn.php");                    // Include the db connection
    

	$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_started != 2 ORDER BY startDate ASC";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);    
	$eventID = $row['eventID'];
?>


<!-- //******************************************************

// Name of File: MANevent.php
// Revision: 1.0
// Date: 16/04/2012
// Author: Lyndon Smith
// Modified: Luke Spartalis

//***********************************************************

//********** Start of MANAGE EVENTS PAGE ************** -->

<head>
	<link rel="stylesheet" href="../js/datepicker/css/datepicker.css" type="text/css" />
    <link rel="stylesheet" media="screen" type="text/css" href="../js/datepicker/css/layout.css" />
	<script type="text/javascript" src="../js/datepicker/js/datepicker.js"></script>
    <script type="text/javascript" src="../js/datepicker/js/eye.js"></script>
    <script type="text/javascript" src="../js/datepicker/js/utils.js"></script>
    <script type="text/javascript" src="../js/datepicker/js/layout.js"></script>

<script type="text/javascript">
//***************************************************************
//
// Ajax Function to create summary table on page.
//
//****************************************************************
function createRequest (eventID, params)
{
	if (eventID == "")
	{
		document.getElementById("eventTable").innerHTML="";
		return;
	}

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
			document.getElementById("eventTable").innerHTML=xmlhttp.responseText;
		}
	}

	//Now we have the xmlhttp object, get the data using AJAX.
	xmlhttp.open("POST","selectEvent.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
	parent.jQuery.colorbox.close();
}

//******************************************************************

function getEvent(eventID)
{
	//Now we have the xmlhttp object, get the data using AJAX.
	var params = "eventID=" + eventID + "&queryType=0";
	//createRequest(eventID,params);		

	if (eventID == "")
	{
		document.getElementById("eventTable").innerHTML="";
		return;
	}

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
			document.getElementById("eventTable").innerHTML=xmlhttp.responseText;
		}
	}

	//Now we have the xmlhttp object, get the data using AJAX.
	xmlhttp.open("POST","selectEvent.php",true);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);
}

//***************************************************************
//
// Ajax Function to insert a new event.
//
//****************************************************************
function checkaddEvent()
{
	var eventID = "0";

	//var eventID = document.getElementById('eventID').value;
	var event_name = document.getElementById('event_name').value;
	var event_location = document.getElementById('event_location').value;
	var startDate = document.getElementById('inputDate').value;
	var days = document.getElementById('days').selectedIndex;
	var startTime = document.getElementById('startTime').value;
	var server_IP_address = document.getElementById('server_IP_address').value;
	var seatQuantity = document.getElementById('seatQuantity').value;

   var params = "eventID=" + eventID + "&queryType=6" + "&event_name=" + event_name
				+ "&event_location=" + event_location + "&startDate=" + startDate 
				+ "&days=" + days + "&startTime=" + startTime 
				+ "&server_IP_address=" + server_IP_address 
				+ "&seatQuantity=" + seatQuantity;			
	createRequest(eventID,params);
}

//***************************************************************
//
// Ajax Function to insert a new event.
//
//****************************************************************
function addEvent()
{
	var message = "An event is about to be ";
	    message += "added. Proceed?";
	
    var answer = confirm(message);
	if (answer == true)
	{
		var eventID ="0";
		var params = "eventID=" + eventID + "&queryType=5";
		createRequest(eventID,params);
	}
	else
	{
		return;
	}
}

//***************************************************************
//
// Ajax Function to delete an event.
//
//****************************************************************
function deleteEvent(eventID, eventName)
{
	message = "Please confirm to delete '" + eventName + "'";

	var answer = confirm(message);
	if (answer == true)
	{
		var params = "eventID=" + eventID + "&queryType=7";		
		createRequest(eventID,params);
	}
	else
	{ 
		return;
	}
}

//***************************************************************
//
// Ajax Function to start an event.
//
//****************************************************************
function startEvent(eventID)
{

	
	var message = "The event is about to be ";
	message += "started. All other started events will";
	message += " be stopped. Proceed?";
	
	var answer = confirm(message);
	if (answer == true)
	{
		var params = "eventID=" + eventID + "&queryType=1";		
		createRequest(eventID,params);
	}
	else
	{ 
		return;
	}
}
//***************************************************************
//
// Ajax Function to stop / end an event.
//
//****************************************************************
function stopEvent(eventID)
{

	var message = "The event is about to be ";
	message += "stopped. It cannot be re-started. Proceed?";
	
	var answer = confirm(message );
	if (answer == true)
	{
		var params = "eventID=" + eventID + "&queryType=2";		
		createRequest(eventID,params);
	}
	else
	{ 
		return;
	}
}
//***************************************************************
//
// Ajax Function to edit an event.
//
//****************************************************************
function editEvent(eventID)
{
	var params = "eventID=" + eventID + "&queryType=3";
	createRequest(eventID,params);
}
//************************************************************************************************
//
// Ajax Function to save the edits on the page.
//
//*************************************************************************************************
function updateEvent()
{	
  	var eventID = document.getElementById('eventID').value;
	var event_name = document.getElementById('E_event_name').value;
	var event_location = document.getElementById('E_event_location').value;
	var startDate = document.getElementById('E_startDate').value;
	var days = document.getElementById('E_days').selectedIndex+1;
	var startTime = document.getElementById('E_startTime').value;
	var server_IP_address = document.getElementById('E_server_IP_address').value;
	var seatQuantity = document.getElementById('E_seatQuantity').value;

	var params = "eventID=" + eventID + "&queryType=4" + "&event_name=" + event_name
				+ "&event_location=" + event_location + "&startDate=" + startDate 
				+ "&days=" + days + "&startTime=" + startTime 
				+ "&server_IP_address=" + server_IP_address
				+ "&seatQuantity=" + seatQuantity;			

	createRequest(eventID,params);		
}

function setDate()
{
	// GET TODAYS DATE
	var fullDate = new Date(); // full date
	var twoDigitMonth = ((fullDate.getMonth().length+1) === 1)? (fullDate.getMonth()+1) : '0' + (fullDate.getMonth()+1); // 2 Digit month
	var currentDate = fullDate.getDate() + "/" + twoDigitMonth + "/" + fullDate.getFullYear(); // Absolute date nn/nn/nnnn

	// SET TODAYS DATE
	document.getElementById('inputDate').value = currentDate;
}


// COLORBOX
$(document).ready(function(){
	$(".ajax").colorbox();
	$(".inlineADD").colorbox({inline:true, width:"550px", height:"370px", scrolling: false});
	$(".inlineB").colorbox({inline:true, width:"700px", height:"900px"});

	// DATE PICKER:
	$('.inputDate').DatePicker();
});
</script>
</head>


<body onload="getEvent(<?php echo $eventID;$result->close();?>); setDate();">
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content">





<div align='left' class='error'>
<?php
if (isset($_SESSION['errMsg']))
{
	echo $_SESSION['errMsg'];
	unset($_SESSION['errMsg']);
	echo '<br /><br />';
}
?>
</div>





<!-- HREF : OPENS INLINE 'CREATE NEW PIZZA' FORM -->
<a class="inlineADD" href="#addEventTable">Create new event</a>





<br /><br /><br />





<!-- 'ADD NEW EVENT' @ colorbox -->
<div style='display: none;'>
<div id='addEventTable'>
<?php
	$on = 'this.src="../images/buttons/save_dwn.png"';
	$off = 'this.src="../images/buttons/save_up.png"';
?>
	<table cellspacing="0" class="editTable" border='0'>
	<tr>
		<th class="headText" colspan="3">Create A New Event</th>
	</tr>

	<tr>
		<td width='150px'>Event Name:</td>
		<td><input type="text" name="event_name" id="event_name" value="" size="50" maxlength="64" /></td>
	</tr>

	<tr>
		<td>Event Location: </td>
		<td><input type="text" name="event_location" id="event_location" value="" size="50" maxlength="128" /></td>
	</tr>

	<tr>
		<td>Start Date:</td>
		<td>
			<input class="inputDate" type='text' name='inputDate' id="inputDate" readonly='readonly'/>
			<label id='closeOnSelect'>
			<input type='checkbox' checked='true' style='visibility: hidden' /></label>
		</td>
	</tr>

	<tr>
		<td>Day Count:</td>
		<td>
			<select name='days' id='days'>
				<option value='1' selected='selected'>1</option>
				<option value='2'>2</option>
				<option value='3'>3</option>
				<option value='4'>4</option>
				<option value='5'>5</option>
			</select>
		</td>
	</tr>



	<tr>
		<td>Event Time: <font size='1'>(24 Hour)</font></td>
		<td><input type="text" name="startTime" id="startTime" value="00:00" size="50" maxlength="5" /></td>
	</tr>

	<tr>
		<td>Server IP Address: </td>
		<td><input type="text" name="server_IP_address" id="server_IP_address" value="" size="50" maxlength="28" /></td>
	</tr>

	<tr>
		<td>Seat Quantity: </td>
		<td><input type="text" name="seatQuantity" id="seatQuantity" value="64" size="50" maxlength="2" /></td>
	</tr>

	<tr>
		<td align="right" height="50px" colspan="2">
        <div align="right" style='position: relative; left: -20px;'>
			<!-- SAVE EVENT -->
			<img class='button'
				 src="../images/buttons/save_dwn.png" 
				 title="Create this event" onclick="checkaddEvent()" 
				 onmouseover='<?php echo $off; ?>' onmouseout='<?php echo $on; ?>' />
        </div>
		</td>
    </tr>
	</table>
</div>
</div>





<!--This is where the summary table ends up.-->
<div id="eventTable"></div>
<br /><br />





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