<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php 
	session_start();								// Start/resume THIS session

	// PAGE SECURITY
	if (isset($_SESSION['isAdmin']))
	{
		if ($_SESSION['isAdmin'] == 0)
		{
			echo '<script type="text/javascript">history.back()</script>';
			die();
		}
	}

	$_SESSION['title'] = "Edit Notice | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 			// Include the template page
	include("../includes/conn.php"); 				// Include the database connection



	// IF [this] ARTICLE IS SENT FROM home.php
	if (isset($_POST['editNewsID']))
	{
		$newsID = $_POST['editNewsID']; 

		$query = "SELECT * FROM news WHERE newsID = '".$newsID."'";
		$result = $db->query($query);
		$row = $result->fetch_assoc();
		
		$title = $row['subject'];
		$date = $row['date'];
		$message = $row['message'];
		
		if (isset($row['image']))
		{
			$image = $row['image'];
		}
		else
		{
			$image = 0;
		}

		$tag = $row['tag'];
	}




	// IF 'EDIT NOTICE FORM' IS SUBMITTED
	if (isset($_POST['submit']))
	{
		print_r($_POST);
		// SET INPUT VARIABLES
		$newsID = $_POST['newsID'];
		$title = $db->real_escape_string($_POST['title']);
		$message = $db->real_escape_string($_POST['message']);
		$tag = $db->real_escape_string($_POST['tag']);

		if ($_POST['isPicSelected'] == 1)
		{
			if ($_POST['samePicture'] == 0)
			{

				// A D D   N E W   I M A G E 
				if (isset($_FILES['image']))
				{
					$imageName = basename($_FILES['image']['name']);
					$imageExt = $_FILES['image']['type'];
					$MAX_FILE_SIZE = '2000000'; // maximum file size for image 2MB
					$tag = $db->real_escape_string($_POST['tag']);

					// SET IMAGE UPLOAD PATH
					$targetPath = '../NewsArticle/uploads/';

					// SET ENTIRE UPLOAD : NewsArticle/uploads/filename.extension
					$targetPath = $targetPath . $imageName;

					// MOVE [this] FILE TO UPLOAD PATH
					if (move_uploaded_file($_FILES['image']['tmp_name'], $targetPath))
					{
						// echo 'The file ' . $imageName . ' has been successfully uploaded';
					}
					else
					{
						if ($_FILES['size'] > 2000000)
						{
							echo 'The maxium file size is 2Mb';
						}
						else
						{	
							echo 'There was an error uploading the file, please try again!';
						}
					}
					// UPDATE ARTICLE -include new image
					$stmt = $db->prepare("UPDATE news SET subject=?, message=?, image=?, tag=?  WHERE newsID=?");
					$stmt->bind_param('sssss', $title, $message, $imageName, $tag, $newsID);
					$stmt->execute();
					$stmt->close();
					echo '<script type="text/javascript">window.location.href="/cassa/home.php"</script>';
				}


				// R E M O V E   O L D   I M A G E  -  A D D   N E W  I M A G E 
				else if (isset($_FILES['newImage']))
				{
					$imageName = basename($_FILES['newImage']['name']);
					$imageExt = $_FILES['newImage']['type'];
					$MAX_FILE_SIZE = '2000000'; // maximum file size for image 2MB
					$tag = $db->real_escape_string($_POST['tag']);

					// SET IMAGE UPLOAD PATH
					$targetPath = '../NewsArticle/uploads/';

					// SET ENTIRE UPLOAD : NewsArticle/uploads/filename.extension
					$targetPath = $targetPath . $imageName;

					// MOVE [this] FILE TO UPLOAD PATH
					if (move_uploaded_file($_FILES['newImage']['tmp_name'], $targetPath))
					{
						// echo 'The file ' . $imageName . ' has been successfully uploaded';
					}
					else
					{
						if ($_FILES['size'] > 2000000)
						{
							echo 'The maxium file size is 2Mb';
						}
						else
						{	
							echo 'There was an error uploading the file, please try again!';
						}
					}

					// GET [this] ARTICLES STORED IMAGE NAME
					$query = "SELECT * FROM news WHERE newsID='".$newsID."'";
					$result = $db->query($query);
					$row = $result->fetch_assoc();

					// REMOVE [this] ARTICLES CURRENT IMAGE
					$fileToDelete = '../NewsArticle/uploads/'.$row['image'];
					while(is_file($fileToDelete) == TRUE)
					{
						chmod($fileToDelete, 0666);
						unlink($fileToDelete);
					}

					// UPDATE ARTICLE -remove old image & include new image
					$stmt = $db->prepare("UPDATE news SET subject=?, message=?, image=?, tag=?  WHERE newsID=?");
					$stmt->bind_param('sssss', $title, $message, $imageName, $tag, $newsID);
					$stmt->execute();
					$stmt->close();
					echo '<script type="text/javascript">window.location.href="/cassa/home.php"</script>';
				}
			}
		}

		// S A M E   I M A G E
		else if ($_POST['isPicSelected'] == 0 && $_POST['samePicture'] == 1)
		{
			// UPDATE ARTICLE -same image
			$stmt = $db->prepare("UPDATE news SET subject=?, message=?, tag=? WHERE newsID=?");
			$stmt->bind_param('ssss', $title, $message, $tag, $newsID);
			$stmt->execute();
			$stmt->close();
			echo '<script type="text/javascript">window.location.href="/cassa/home.php"</script>';
		}

		// N O  I M A G E
		else
		{
			// GET [this] ARTICLES STORED IMAGE NAME
			$query = "SELECT * FROM news WHERE newsID='".$newsID."'";
			$result = $db->query($query);
			$row = $result->fetch_assoc();

			// REMOVE [this] ARTICLES CURRENT IMAGE
			$fileToDelete = '../NewsArticle/uploads/'.$row['image'];
			while(is_file($fileToDelete) == TRUE)
			{
				chmod($fileToDelete, 0666);
				unlink($fileToDelete);
			}

			$imageName = '';

			// UPDATE ARTICLE -remove image
			$stmt = $db->prepare("UPDATE news SET subject=?, message=?, image=?, tag=?  WHERE newsID=?");
			$stmt->bind_param('sssss', $title, $message, $imageName, $tag, $newsID);
			$stmt->execute();
			$stmt->close();
			echo '<script type="text/javascript">window.location.href="/cassa/home.php"</script>';
		}
	}
?>



<!-- //******************************************************

// Name of File: editNotices.php
// Revision: 1.0
// Date: 09/05/2012
// Author: Quintin M
// Modified: 

//***********************************************************

//*********** Start of EDIT NOTICES PAGE *************** -->

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

		// CHECK IF THERE IS SOMETHING IN newPicture
		var newImage = document.addNews['newImage'].value;
		if (newImage == '' || document.addNews['image'].value == '')
		{
			document.addNews['isPicSelected'].value = 0;

			if (document.addNews['samePicture'].value == 'removed')
			{
				document.addNews['samePicture'].value = 0;
			}
			else
			{
				document.addNews['samePicture'].value = 1;
			}
		}
		else
		{
			document.addNews['isPicSelected'].value = 1;
		}
	}

	function removeImage()
	{
		document.getElementById('imageOFF').style.display = 'none';
		document.getElementById('imageON').style.display = 'block';
		document.getElementById('isPicSelected').value = 0;
		document.getElementById('samePicture').value = 'removed';
	}
	function messageCount()
	{
		msg = document.addNews['message'].value;
		msgLength = document.addNews['message'].value.length;

		if (msgLength > 800)
		{
			msg = msg.substring(0, 800);
			document.addNews['message'].value = msg;
		}
		else
		{
			document.getElementById('messageCount').innerHTML = msgLength;
		}
	}
	function checkImage(x)
	{
		if (document.getElementById(x).value != '')
		{
			document.getElementById('isPicSelected').value = 1;
		}
		else
		{
			document.getElementById('isPicSelected').value = 0;
		}
	}

</script>
</head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Edit Notice</h1>


	Required fields are marked <font class='redAstrix'>*</font>
	<br />
	<br />


<!-- Interface Box -->
<div id='newsBox' style='text-align: left; padding-left: 20px;'>
<form name='addNews' enctype="multipart/form-data" method='POST' 
	  onsubmit='return newsVal()' action='editNotices.php'>
	<br />

	<table cellpadding='5px' border='0'>

	<tr>
		<td align='right' style='color: #888888;'>
			Title <font class='redAstrix'>*</font>
		</td>
		<td style='vertical-align: bottom;'>
			<input  class='addNoticeBackColor addNoticeTitle' type='text' 
					name='title' maxlength='64' value='<?php echo $title; ?>' />
		</td>
	</tr>

	<tr>
		<td align='right' style='vertical-align: top; color: #888888;'>
			Message <font class='redAstrix'>*</font><br /><br />
			<font size='2'>(<font id='messageCount'>0</font>/800)</font>
		</td>
		<td>
			<textarea class='addNoticeBackColor addNoticeTextArea' name='message' rows='10'
					  onkeyup='messageCount()'><?php echo $message; ?></textarea>
		</td>
	</tr>

	<tr>
		<td align='right' style='color: #888888;'><br/>Tag</td>
		<td style='vertical-align: bottom;'><br/>
			<input  class='addNoticeBackColor' type='text' 
					name='tag' maxlength='64' value='<?php echo $tag; ?>' />
					<font size='2' style='color: grey'>Seperate each tag with a comma</font>
		</td>
	</tr>



	<tr>
		<td align="right" style='vertical-align: top; color: #888888;'><br/><br/>Image</td>

	<?php 
	if (empty($image))
	{
	?>
		<td><br/><br/>

			<input class='addNoticeBackColor' name="image" id='image' type="file" 
			accept="image/jpg, image/gif, image/jpeg, image/pjpeg, image/png" 
			onchange='checkImage(this.id)' />
			<br /><br />
		</td>
	<?php
	}
	else
	{
	?>
		<td id='imageON' style='display: none;'><br/><br/>
			<input class='addNoticeBackColor' name="newImage" id='newImage' type="file" 
			accept="image/jpg, image/gif, image/jpeg, image/pjpeg, image/png" 
			onchange='checkImage(this.id)' />
			<br /><br />
		</td>

		<td id='imageOFF'><br /><br/>
			<img src='/cassa/NewsArticle/uploads/<?php echo $image; ?>' 
				 width='250px' />
			<img src='/cassa/images/buttons/remove_s.png' 
				 style='vertical-align: top; position: relative; top: -15px; left: -22px;' 
				 class='pointer'
				 onclick='removeImage()' />
		</td>
	<?php
	}
	?>
	</tr>


	<tr>
		<td colspan="2" align="center"><br /><input type="submit" name="submit" value="Update News" /></td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>

	<!-- ENSURES TO THE SERVER THERE EITHER IS OR IS NOT A FILE TO BE SAVED-->
	<input type='hidden' name='isPicSelected' id='isPicSelected' value='0' />
	<input type='hidden' name='samePicture' id='samePicture' value='<?php echo $image; ?>' />
	<input type='hidden' name='newsID' value='<?php echo $newsID; ?>' />

	</table>
</form>
</div>






<br />







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