<?php
	session_start();							// Start/resume THIS session
	include('includes/conn.php');				// Include database connection settings
?>

<!-- ******************************************************

// Name of File: login.php
// Revision: 1.0
// Date: 16/04/2012
// Author: Lyndon Smith
// Modified: Lyndon Smith 16/04/2012

//***********************************************************

//*************** Start of login script ******************* -->
<!-- Steps:

// 1) Check to see if event has started and if so, only progress if I.P address Range is correct or user is an admin.
// 2) Check if user credentials indicate the user is registered. If not re-direct back to home page.
// 3) If user is registered are they a staff member or a non-staff member. Set Session Flag $_SESSION['isSTAFF'] to "1".
// 4) Set Session variable $_SESSION['username'] to the $_POST['username'].
// 5) If all successful, re-direct to user / staff home page.
// 6) Create $_SESSION variables for 'fullName' & 'errMsg'

-->




<?php

if (isset($_POST['submit']))
{
	// First check if user exists
	$thisLogin = login_user($db);
	
	if ($thisLogin == 1)//user exists in database & credentials are correct
	{
		$eventStatus = event_check($db);		
		if ($eventStatus == 1) // event started when set to 1
		{
			$adminStatus = isStaff($db);
			if($adminStatus == 1) // if user is a staff member then skip IP address check
			{
				//close connection
				$db->close();
				$_SESSION['errMsg'] = "";
				
				header('Location: /CASSA/management/staffBoard.php?msg="3"');
			}
			else 
			{
				$ipStatus = check_IP_address($db);
				if ($ipStatus == 1) // if IP address is within range allow login
				{
					//close connection
					$db->close();
					$_SESSION['errMsg'] = "";
					header('Location: /CASSA/management/clientBoard.php?msg="4"');
				}
				else
				{
					// else login not allowed so close connection 
					if(isset ($_SESSION['username']))
					{
						unset ($_SESSION['username']);
					}

					$db->close();
					$_SESSION['errMsg'] = "<font class='error'>You cannot login after an event is started from a remote location.</font>";
					header('Location: home.php?msg="1"');
				}
			}
		}
		else 
		{
			$adminStatus = isStaff($db);
			
			if($adminStatus == 1) //event has not started so process login normally as an admin
			{
				//close connection
				$db->close();
				$_SESSION['errMsg'] = "";
				header('Location: /CASSA/management/staffBoard.php?msg="5"');
			}
			else
			{
				// else user is not an admin so goto client summary page and close connection
				$db->close();
				$_SESSION['errMsg'] = "";
				header('Location: /CASSA/management/clientBoard.php?msg="6"');
			}
		}
	}
	else
	{
		//close connection
		$db->close();
                if(isset ($_SESSION['username']))
                {
                    unset ($_SESSION['username']);
                }

		$_SESSION['errMsg'] = "<font class='error'>Login failed, please try again.</font>";
		header('Location: home.php?msg="2"');
	}
}



//********************************Function Event_check *********************************************
// Requires : Database connection
// Returns : 1 if event is started or 0 if not
//
//**************************************************************************************************
// Query Data Base to check if event has started.

function event_check($db)
{
	$query = "SELECT event_started FROM event WHERE event_started = 1";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$row_cnt = $result->num_rows;

	if ($row_cnt == 1) 
	{
		// close result set
    	$result->close();
		return 1;
	}
	else 
	{ 
		 // close result set
		$result->close();
		return 0;
	}
	
}
		
// ********************************************end of function event_check **********************************

		
		
//********************************  Function check_IP_address ***********************************************
// Requires : Database connection
// Returns : 1 if IP address is within range or 0 if not
//
//**************************************************************************************************
// Query Data Base to get IP Address range.
		
function check_IP_address($db)
{
	$query = "SELECT server_IP_address FROM event WHERE event_started = 1";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);	
				
	$serverIP = abs(ip2long($row['server_IP_address']));
	
	// Now create the hi and lo values of the server address
	$lowIP = abs($serverIP - 20);
	$highIP = abs($serverIP + 20);
	
	// Get the IP address of the requesting party
	
	// *******Include after testing ***********	
	
	// $userIP = abs(ip2long($_SERVER['REMOTE_ADDR']));	 
		
	// *******Remove after testing ***********
	
		$userIP = abs(ip2long("192.168.0.33"));
	// Check whether the address is within range and return the result
	
	if ( $userIP <= $lowIP || $userIP >= $highIP )
	{
		
	$_SESSION['errorMSG'] = "You have attempted to login after an event has started and from outside the MegaLAN";
                
                 if(isset ($_SESSION['username']))
                {
                    unset ($_SESSION['username']);
                }
		 // close result set
		$result->close();
		return 0; // IP address outside range
	}
	else	
	{  
	// close result set
	$result->close();
	return 1; // IP address within range.
	}
}	
		
//******************************************end of function check_IP_address ********************************		
	
//****************************************Function isStaff () *****************************************************
// Requires= Database Connection
// Returns 1 if user is an admin returns 0 if not
//*****************************************************************************************************************

function isStaff($db)
{
	$query = "SELECT * FROM client WHERE username = '" . mysql_real_escape_string($_POST['username']) . "' 
				and password = '" . mysql_real_escape_string($_POST['password']) . "'";
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
		
		// Check to see if user is a staff member	
		if ($row['admin'] == 1) // STAFF
		{
			// close result set
			$result->close();
			$_SESSION['isAdmin'] = 1;					
			return 1;
		}
		else if ($row['admin'] == 2) // ADMIN
		{
			// close result set
			$result->close();
			$_SESSION['isAdmin'] = 2;					
			return 1; // user is an admin
		}
		else 
		{ 
			// close result set
			$result->close();
			$_SESSION['isAdmin'] = 0;
			return 0; // user is not a client
		}
}

// ***************************************End of isStaff Function ***********************************************		

//*********************************Function login_user() is used for code re-use only ***************************
// Requires= Database Connection
// Returns 1 if user exists returns 0 if not
//*****************************************************************************************************************	
function login_user($db)
{
	$query = "SELECT * FROM client WHERE username = '" . mysql_real_escape_string($_POST['username']) . "' 
				and password = '" . mysql_real_escape_string($_POST['password']) . "'";
				
	$result = $db->query($query);
	$row = $result->fetch_array(MYSQLI_BOTH);
	$row_cnt = $result->num_rows;

	
	// Check username and password match stored record
	if ($row_cnt == 1) 
	{
		// Set username session variable
		$_SESSION['username'] = $_POST['username'];
		$_SESSION['err_code'] = 0;
				
		// create session vars if ok
		$_SESSION['fullName'] = $row['first_name']. " " . $row['last_name'];
		$_SESSION['userID'] = $row['clientID'];
		
		// close result set
		$result->close();			
			return 1; // user exists in database
		
	}
	else 
	{
		 // close result set
		$result->close();				
		return 0; //user does not exist.
	}
}
//*************************************End of Function login_user() ********************************************
?>





<!-- Form: for user login -->
<form name="login" method="POST" onsubmit="return loginValidate();" action="/CASSA/login.php">
<div style='text-align: center'>
	<table id='loginThickBox'>
		
		<!-- LOGIN ERRORS -->
		<?php 
		if (isset($_SESSION['errMsg']))
		{
			echo '<tr><td colspan="2">'.$_SESSION['errMsg'].'</td></tr>';
		}
		?>

		<tr>
			<td>Username </td>
			<td><input type="text" width="40px" name="username" maxlength='32' /></td>
		</tr>

		<tr>
			<td>Password </td>
			<td><input type="password" width="40px" name="password" maxlength='32' /></td>
		</tr>

		<tr>
			<td align='right' colspan='2'><input type="submit" name='submit' value="Login" /></td>
		</tr>
	</table>
</div>
</form>