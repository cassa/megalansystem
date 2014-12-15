<?php include('initialise.php');
	$_SESSION['pageTitle'] = 'Add News';


// Set variables to autofil data for IF editing is called
$e = 0; // edit mode is off
$p = 0; // picture show is off

// Set below variables to blank
$id = ''; 
$date = '';
$title = '';
$message = '';
$imageDir = '';



// IF editing is called GET table data and SET variables
if (isset($_GET['e']) && isset($_GET['i']) && isset($_GET['p']))
{
	if ($_GET['e'] == 1 && $_GET['i'] > 0)
	{
		$e = 1; // edit mode is on
		$p = 1; // picture show is on
		$_SESSION['pageTitle'] = 'Edit News'; // update <title> 

		$get = "SELECT * FROM news WHERE newsID = '".$_GET['i']."'";
		$result = $db->query($get);
		$row = $result->fetch_assoc();

		$id = $row['newsID'];
		$date = $row['date'];
		$title = $row['title'];
		$message = $row['message'];
		$imageDir = $row['imageDir'];
	}
	if ($_GET['p'] == 0)
	{
		$p = 0;
	}
}



// IF form has been filled out, validate and insert into database
if (isset($_POST['submit']))
{
	if ($_POST['mode'] == 0 || $_POST['picture'] == 0)
	{
		if ($_POST['isPicSelected'] != 0)
		{
			// Define constants to store useful/important values used in the code
			// Defining them here make it easy to maintain the code in the future
			define('ALLOWED_TYPES', 'image/gif image/jpeg image/png image/jpg image/pjpeg');
			define('MAX_SIZE', 1048576);
			define('FILE_DEST', 'uploads/');



			// Create variables for all the submitted file attributes, for easier access
			$file		= $_FILES['image']['tmp_name'];	// The file itself
			$file_size  = $_FILES['image']['size'];		// The file size in bytes
			$file_type  = $_FILES['image']['type'];		// The file type
			$file_error = $_FILES['image']['error'];	// The file upload error code



			// If there was an error, show an appropriate error message
			// 0 indicates no error, other numers indicate errors
			if ($file_error > 0)
			{
				switch ($file_error)
				{
					// Error codes 1 and 2 both relate to restrains on the file size
					case 1:
					case 2:
						echo 'File is over max allowed size. <a href="addNews.php">Return</a>.';
						break;
					
					// For all other error codes, we'll use a generic message
					default:
						echo 'Error uploading file. <a href="addNews.php">Return</a>.';
						break;
				}
				exit;
			}

			// If the file type is not one of the allowed types, show an error message
			if (strpos(ALLOWED_TYPES, $file_type) === false)
			{
				echo 'Invalid file type. <a href="addNews.php">Return</a>.';
				exit;
			}

			// If the file size is bigger than the max size, show an error message
			if ($file_size > MAX_SIZE)
			{
				echo 'File is over max allowed size. <a href="addNews.php">Return</a>.';
				exit;
			}



			// SERVER: ERROR FREE ZONE ---------------------------
			// SERVER: ERROR FREE ZONE ---------------------------
			// SERVER: ERROR FREE ZONE ---------------------------

			// Store the uploaded image content as a string
			$image_string = file_get_contents($file);

			// Create an image resource from the string of image data
			$image = imageCreateFromString($image_string);


			// The new file name
			$new_file_name = strtolower($_FILES['image']['name']);

			// The new file name + directory
			$new_file_dir_name = "uploads/".$new_file_name;


			// Save the image as jpg in the file destination folder
			imageJPEG($image, "uploads/".$new_file_name, 100);
		}

		// INSERT DATA INTO SYSTEM
		$insert = "INSERT INTO news (title, message, imageDir) VALUES ('".$_POST['title']."', '".$_POST['message']."', '".$new_file_dir_name."')";
		$result = $db->query($insert);
	}
	else if ($_POST['mode'] == 1)
	{
		if ($_POST['picture'] == 1)
		{
			// UPDATE DATA INTO SYSTEM
			$update = "UPDATE news SET title = '".$_POST['title']."', message = '".$_POST['message']."', imageDir = '".$_POST['storedImage']."' WHERE newsID = '".$_POST['id']."'";
			$result = $db->query($update);
		}
		else if ($_POST['picture'] == 0)
		{
			// UPDATE DATA INTO SYSTEM
			$update = "UPDATE news SET title = '".$_POST['title']."', message = '".$_POST['message']."', imageDir = '".$new_file_dir_name."' WHERE newsID = '".$_POST['id']."'";
			$result = $db->query($update);
		}
	}

	echo '<script type="text/javascript">window.location.href="news.php"</script>';
}
?>


<html>
<head>
<script type='text/javascript'>
	
	function newsVal()
	{
		var title = (document.addNews['title'].value).replace(/^\s*|\s*$/g,'');
		var message = (document.addNews['message'].value).replace(/^\s*|\s*$/g,'');

		if (title == '')
		{
			alert('No title has been entered');
			document.addNews['title'].focus();
			return false;
		}
		if (message == '')
		{
			alert('No message has been entered');
			document.addNews['message'].focus();
			return false;
		}
		if (document.addNews['image'].value == false)
		{
			var answer = confirm('Are you sure you would NOT like to insert a picture?');
			if (answer == false)
			{
				document.addNews['message'].focus();
				return false;
			}
			else
			{
				document.addNews['isPicSelected'].value = 0;
			}
		}
	}

	function removeP(e, i, p)
	{
		window.location.href='addNews.php?e='+e+'&p=0&i='+i;
	}

</script>
</head>

<body>
<center>

<a href='news.php'>Show News</a>
<br /><br />




<!-- Interface Box -->
<div id='newsBox'>
<form name='addNews' enctype="multipart/form-data" method='POST' onsubmit='return newsVal()' action='addNews.php'>

<!-- Hidden mode inputs -->
<input type='hidden' name='mode' value='<?php echo $e; ?>' /><!-- check if Edit mode is on/off -->
<input type='hidden' name='picture' value='<?php echo $p; ?>' /><!-- check if picture is on/off -->
<input type='hidden' name='isPicSelected' value='1' /><!-- see if picture is selected -->
<input type='hidden' name='id' value='<?php echo $_GET['i']; ?>' /><!-- set newsID -->



<br />
<table cellpadding='10px'>

<?php
	if ($e == 1)
	{
		echo '<tr>';
		echo '<td colspan="2" align="center">EDIT MODE</td>';
		echo '</tr>';
	}
?>

<tr>
	<td align='right'>Title</td>
	<td><input type='text' name='title' size='60' maxlength='60' value='<?php echo $title; ?>' class='inputFont' /><br /></td>
</tr>

<tr>
	<td align='right' style='vertical-align: top'>Message</td>
	<td><textarea name='message' cols='62' rows='10'><?php echo $message; ?></textarea><br /></td>
</tr>

<?php
	if ($e == 1 && $p == 1)
	{
		if ($imageDir != '')
		{
			echo '<tr>';
			echo '<td align="right">Image</td>';
			echo '<td><img class="newsImageImage" src="'.$imageDir.'" />';
			echo '<div id="removeButton"><input type="button" value="Remove" onclick="removeP('.$e.','.$_GET['i'].',0)" /></div><br /></td>';
			echo '</tr>';
			
			echo "<input type='hidden' name='storedImage' value='".$imageDir."' />";
		}
		else
		{
			echo '<tr>';
			echo '<td align="right">Image</td>';
			echo '<td><input name="image" type="file" accept="image/jpg,image/gif, image/jpeg, image/pjpeg, image/png" /><br /></td>';
			echo '</tr>';
		}
	}
	else
	{
		echo '<tr>';
		echo '<td align="right">Image</td>';
		echo '<td><input name="image" type="file" accept="image/jpg,image/gif, image/jpeg, image/pjpeg, image/png" /><br /></td>';
		echo '</tr>';
	}


	if ($e == 1)
	{
		echo '<tr>';
		echo '<td colspan="2" align="center"><br /><input type="submit" name="submit" value="Update" /></td>';
		echo '</tr>';
	}
	else
	{
		echo '<tr>';
		echo '<td colspan="2" align="center"><br /><input type="submit" name="submit" value="Submit" /></td>';
		echo '</tr>';
	}
?>
</table>


<br />
</form>
</div>

</center>
</body>
</html>