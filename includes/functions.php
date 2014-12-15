<?php
include('conn.php');

// CONVERT DATE TO user interface DATE
if (!function_exists("dateToScreen"))
{
	function dateToScreen($date)
	{
		$year = substr($date, 0, 4);
		$month = substr($date, 5, 2);
		$day = substr($date, 8, 2);
		$date = $day.'/'.$month.'/'.$year;

		return $date;
	}
}

// CONVERT DATE TO mySQL DATE
if (!function_exists("dateToDatabase"))
{
	function dateToDatabase($date)
	{
		$day = substr($date, 0, 2);
		$month = substr($date, 3, 2);
		$year = substr($date, 6, 4);
		$date = $year.'/'.$month.'/'.$day;

		return $date;
	}
}


// REMOVE 'seconds [:00]' FROM TIME
if (!function_exists("removeSeconds"))
{
	function removeSeconds($time)
	{
		$time = substr($time, 0, 5);
		return $time;
	}
}


// REG EXPRESSION [A-Za-z] STRINGS
if (!function_exists("regLetters"))
{
	function regLetters($input)
	{
		if (!preg_match("#^[A-Za-z]+$#", $input)) 
		{ 
			// invalid [letter] input
			return false;
		}
		else
		{
			return true;
		}
	}
}


// REG EXPRESSION NO '<' '>' TAGS
if (!function_exists("removeBlockTags"))
{
	function removeBlockTags($input)
	{
		$input = str_replace("<", "", $input);
		$input = str_replace(">", "", $input);

		return $input;
	}
}


// GET [this] EVENT's ID
if (!function_exists("getThisEvent"))
{
	function getThisEvent($db)
	{
		// GET ALL OF [this] USERS CURRENTLY BOOKED EVENTS @ ATTENDEE
		$query = "SELECT * FROM event WHERE startDate >= CURDATE() AND event_started != 2";
		$result = $db->query($query);

		if ($result->num_rows == 0)
		{
			return false;
		}
		else
		{	
			$dateArray = array();

			// FOR EACH EVENT ROW
			// FIND [current] EVENT
			for ($i=0; $i<$result->num_rows; $i++)
			{
				$row = $result->fetch_assoc();
				$eventID = $row['eventID']; 

				// GET ASSOCIATED ROW @ EVENT
				$get = "SELECT * FROM event WHERE eventID='".$eventID."' AND startDate >= CURDATE()";
				$resultGet = $db->query($get);
				if ($resultGet->num_rows > 0)
				{
					$rowEvent = $resultGet->fetch_assoc();
					$startDate = $rowEvent['startDate'];
					$dateArray[$i] = $startDate;
				}
			}
			// SORT DATEARRAY TO FIND CLOSES EVENT
			natsort($dateArray);
			$dateArray = array_values($dateArray);

			//********************************************
			// TESTING PURPOSES
			//********************************************
				//print_r($dateArray);

				/*foreach($dateArray as $d => $x)
				{
					echo $d.' '.$x.'<br />';
				}*/
			//********************************************

			// GET [current] EVENT ID
			$get = "SELECT * FROM event WHERE startDate='".$dateArray[0]."'";
			$result = $db->query($get);
			$row = $result->fetch_assoc();
			$eventID = $row['eventID'];

			return $eventID;
		}
	}
}

// GET [this] EVENT's NAME
if (!function_exists("getThisEventRow"))
{
	function getThisEventRow($db)
	{
		$eventID = getThisEvent($db);
		$get = "SELECT * FROM event WHERE eventID='".$eventID."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();

		return $row;
	}
}

// GET [this] EVENT's NAME
if (!function_exists("getThisEventName"))
{
	function getThisEventName($db)
	{
		$eventID = getThisEvent($db);
		$get = "SELECT * FROM event WHERE eventID='".$eventID."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();

		$eventName = $row['event_name'];

		return $eventName;
	}
}

// GET [this] EVENT'S DAY COUNT
if (!function_exists("getDayCount"))
{
	function getDayCount($db)
	{
		$get = "SELECT `days` FROM event WHERE event_name='".$eventName."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();
		$dayCount = $row['days'];
		
		return $dayCount;
	}
}


?>