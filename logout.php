
<!-- ******************************************************

// Name of File: logout.php
// Revision: 1.0
// Date: 06/04/2012
// Author: Lyndon Smith
// Modified: Quintin Maseyk 26/04/2012 

//***********************************************************

//*************** Start of login script ******************* -->

<?php
	// Inialise [this] session
	session_start();

	// Overlap [this] session ID
	session_regenerate_id();

	// Clear all [this] sessions variables
	session_unset();
	$_SESSION = array();

	// Destroy [this] overall session
	session_destroy();

	// Now Back to login page
	header('Location: home.php');
?>