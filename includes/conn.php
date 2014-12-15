<?php

$hostname   = 'localhost';      // MySQL hostname.
$username   = 'reader';         // Your database username.
$password   = 'pass123';        // User Reader Password
$database   = 'cassa_lan'; 		// database name.
//$database   = 'lan_cassa_empty'; 		// database name.


	// CONNECT TO THE DATABASE: cassa_lan
	// $db = connection variable to use for every query
	@ $db = new mysqli($hostname, $username, $password, $database);

	// Handle connection error
	if (mysqli_connect_error())
	{
		echo '<p align="center">Error connecting to database.<br /></p>';
		echo '<p align="center">Error Message: '.mysqli_connect_error().'</p>';
		exit;
	}
?>