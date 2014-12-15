<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start(); // Start/resume THIS session

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "Manage Participants | MegaLAN";   // Declare this page's Title
	include("../includes/template.php");                        // Include the template page
        include("../includes/conn.php");

        unset($_SESSION['errMsg']);

	$username = $_SESSION['username'];
	$query = "SELECT * FROM client order by last_name ASC";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$clientID = $row['clientID'];
?>


<!-- //******************************************************

// Name of File: participants.php
// Revision: 1.0
// Date: 6/05/2012
// Author: Lyndon Smith
// Modified:

//***********************************************************

//******** Start of MANAGE PARTICIPANTS PAGE ************ -->

<head>
<script type="text/javascript">
        
 
 
//***************************************************************
//
// Calling this function creates a http request object and response.
// It is the core functionality of this script
// Author: Lyndon Smith
//****************************************************************
function createRequest (clientID, params, divName)
{


    if (clientID=="")

            {

                divName.innerHTML="";
                return;
            }
	if (window.XMLHttpRequest)
            {	// code for mainstream browsers
                    xmlhttp=new XMLHttpRequest();
                }
                else
                    {// code for earlier IE versions
                        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
                    }
                    xmlhttp.onreadystatechange=function()
                        {
                            if (xmlhttp.readyState==4 && xmlhttp.status==200)
                                {
                                    // The returned html gets placed in the location specified here
                                    divName.innerHTML=xmlhttp.responseText;


                                }
                        }
                        //Now we have the xmlhttp object, get the data using AJAX.
                        
                        

			xmlhttp.open("POST","selectclient.php",true);
			xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
			xmlhttp.setRequestHeader("Content-length", params.length);
			xmlhttp.setRequestHeader("Connection", "close");
			xmlhttp.send(params);



}

//************************************************************************************************
// Function to get initial data on load up
// Author: Lyndon Smith
//************************************************************************************************
function getClientData()
{
    var clientID ="0";
   
    var divID = document.getElementById("clientTable");
    var params = "clientID=" + clientID + "&queryType=0&startRow=0";

		document.getElementById("clientDetails").innerHTML="";
                createRequest(clientID , params, divID);
     
}

//*************************************************************************************************
//************************************************************************************************
// Function to get next 5 records
// Author: Lyndon Smith
//************************************************************************************************
function getClientNext5(startRow)
{
    var clientID ="0";
    var divID = document.getElementById("clientTable");
    var params = "clientID=" + clientID + "&queryType=0&startRow=" + startRow;

		createRequest(clientID , params, divID);
                clientID=""
                getClientInfo(clientID);
}

//*************************************************************************************************
//************************************************************************************************
// Function to find a client
// Author: Lyndon Smith
//************************************************************************************************
function searchFunction()
{
    var surname = document.getElementById('searchTerm').value;
    var clientID ="0";
    var divID = document.getElementById("clientTable");
    var params = "clientID=" + clientID + "&queryType=0&startRow=0&surname=" + surname;
   
		createRequest(clientID , params, divID);
                 clientID=""
                getClientInfo(clientID);
}

//*************************************************************************************************
//************************************************************************************************
// Function to delete a client
// Author: Lyndon Smith
//************************************************************************************************
function deleteUser(clientID)
{
    var divID = document.getElementById("clientTable");
    var params = "clientID=" + clientID + "&queryType=2&startRow=0";
    var message = "About to delete one user record. Proceed?"
    test = confirm(message);
    if (test == false)
        {
            return 0;
        }
     else{
         createRequest(clientID , params, divID);
        }
}

//*************************************************************************************************

//************************************************************************************************
// Function to load summary table
// Author: Lyndon Smith
//************************************************************************************************
function getClientInfo(clientID)
{
  
   
    var divID = document.getElementById("clientDetails");
    var params = "clientID=" + clientID + "&queryType=1&startRow=0";
      
    createRequest(clientID , params, divID);
      
}

//*************************************************************************************************
//************************************************************************************************
// Function to manage client participation
// Author: Lyndon Smith
//************************************************************************************************
function manageClientEvent(clientID)
{
  
   
    var divID = document.getElementById("clientDetails");
    var params = "clientID=" + clientID + "&queryType=5&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************

//************************************************************************************************
// Function to show the manage client participation form
// Author: Lyndon Smith
//************************************************************************************************
function editClientData(clientID)
{
  
   
    var divID = document.getElementById("clientDetails");
    var params = "clientID=" + clientID + "&queryType=3&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************
//************************************************************************************************
// Function to save eidted client data
// Author: Lyndon Smith
//************************************************************************************************
function saveEdits(clientID)
{
        var first_name = document.getElementById('first_name').value;
	var last_name = document.getElementById('last_name').value;
	var email = document.getElementById('email').value;
	var mobile = document.getElementById('mobile').value;
	var active1 = document.getElementById('active').checked;
        var active = 0;
      
        if(active1== true)
            {
                active= 1;
            }
        if(active1== false)
            {
                active= 0;
            }
	
   
    var divID = document.getElementById("clientDetails");
    var params = "clientID=" + clientID + "&queryType=4&startRow=0";
        params += "&first_name=" + first_name + "&last_name=" + last_name;
        params += "&email=" + email + "&mobile=" + mobile + "&active=" + active;
    
   
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************
//************************************************************************************************
// Function to add a client to an event
// Author: Lyndon Smith
//************************************************************************************************
function joinEvent(noEvent, clientID)
{
        var e = document.getElementById("eventSelect");
        var eventID = e.options[e.selectedIndex].value;

    var divID = document.getElementById("clientDetails");
    var params = "noEvent=" + noEvent + "&eventID=" + eventID + "&clientID=" + clientID + "&queryType=6&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************
//************************************************************************************************
// Function to add a client to an team
// Author: Lyndon Smith
//************************************************************************************************
function joinTeam(noTeam, clientID)
{
       
       var e = document.getElementById("teamSelect");
        var teamID = e.options[e.selectedIndex].value;

   
    var divID = document.getElementById("clientDetails");
    var params = "noTeam=" + noTeam + "&teamID=" + teamID + "&clientID=" + clientID + "&queryType=7&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************

//************************************************************************************************
// Accepts an event payment
// Author: Lyndon Smith
//************************************************************************************************
function payEvent(payStatus, clientID)
{
    
    var divID = document.getElementById("clientDetails");
    var params = "payStatus=" + payStatus + "&clientID=" + clientID + "&queryType=8&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************


//************************************************************************************************
// Adds a client to a tournament
// Author: Lyndon Smith
//************************************************************************************************
function joinTournament(clientID)
{
    
    var e = document.getElementById("tournSelect");
        var tournID = e.options[e.selectedIndex].value;
    
    var divID = document.getElementById("clientDetails");
    var params = "tournID=" + tournID + "&clientID=" + clientID + "&queryType=10&startRow=0";
    alert (params);
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************






//************************************************************************************************
// Deletes a client from a tournament
// Author: Lyndon Smith
//************************************************************************************************
function un_joinTournament(tournID, clientID)
{
    
    var divID = document.getElementById("clientDetails");
    var params = "tournID=" + tournID + "&clientID=" + clientID + "&queryType=9&startRow=0";
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************

//************************************************************************************************
// Deletes a pizza from an order
// Author: Lyndon Smith
//************************************************************************************************
function removePizza(clientID, orderID)
{
    
    var divID = document.getElementById("clientDetails");
    var params = "orderID=" + orderID + "&clientID=" + clientID + "&queryType=11&startRow=0";
   
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************

//************************************************************************************************
// Deletes a client from a tournament
// Author: Lyndon Smith
//************************************************************************************************
function addPizza(menuID, attendeeID, seatID, clientID)
{
    
    var e = document.getElementById("pizzaSelect");
        var pizzaID = e.options[e.selectedIndex].value;
    var f = document.getElementById("pizzaQty");
        var pizzaQty = f.options[f.selectedIndex].value;
 
    var divID = document.getElementById("clientDetails");
    var params = "menuID=" + menuID + "&attendeeID=" + attendeeID + 
        "&seatID=" + seatID + "&pizzaQty=" + pizzaQty + "&pizzaID=" + pizzaID + 
        "&clientID=" + clientID + "&queryType=12&startRow=0";
    
    createRequest(clientID , params, divID);
        
}

//*************************************************************************************************
    

    
    </script>
</head>
<body onload="getClientData();">
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
<h1>Client Management</h1>
<?php
echo '<hr />';
echo '<p><h2>Client List</h2></p>';
echo '<br />';
?>
<div id="clientTable"></div>
<?php
echo '<hr />';

?>
<div id="clientDetails"></div>





<div id="eventTable"></div>
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

</div><!-- end of: Content -->


<!-- INSERT: rightPanel -->
<?php include('../includes/rightPanel.html'); ?>


<!-- INSERT: footer -->
<div id="footer">
	<?php include('..//includes/footer.html'); ?>
</div>

</div><!-- end of: Shell -->

</center>
</body>
</html>

<!-- ********************************* -->
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->