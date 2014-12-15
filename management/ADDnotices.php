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

	$_SESSION['title'] = "Add Notice | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 		// Include the template page
	include("../includes/conn.php"); 			// Include the database connection


	// IF 'ADD NOTICE FORM' IS SUBMITTED
	if (isset($_POST['submit']))
	{
		// SET INPUT VARIABLES
		$title = $db->real_escape_string($_POST['title']);
		$date = dateToDatabase(date("d/m/Y"));
		$author = ucwords($_SESSION['fullName']);
		$message = $db->real_escape_string($_POST['message']);
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
				echo 'The maxium file size is 2mb';
			}
			else
			{	
				echo 'There was an error uploading the file, please try again!';
			}
		}


		// PARAMETERIZED QUERY WITH REAL_ESCAPE_STRING
		$stmt = $db->prepare("INSERT INTO news (subject, date, author, message, image, tag) VALUES (?, ?, ?, ?, ?, ?)");
		$stmt->bind_param('ssssss', $title, $date, $author, $message, $imageName, $tag);
		$stmt->execute();
		$stmt->close();
	}
?>


<!-- //******************************************************

// Name of File: notices.php
// Revision: 1.0
// Date: 20/04/2012
// Author: Quintin M
// Modified: 

//***********************************************************
//*********** Start of MANAGE NOTICES PAGE *************** -->


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

</script>
</head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Add Notice</h1>


	Required fields are marked <font class='redAstrix'>*</font>
	<br />
	<br />


<!-- Interface Box -->
<div id='newsBox' style='text-align: left; text-indent: 20px;'>
<form name='addNews' enctype="multipart/form-data" method='POST' 
	  onsubmit='return newsVal()' action='ADDnotices.php'>
	<br />

	<table cellpadding='5px' border='0'>

	<tr>
		<td align='right' style='color: #888888;'>Author</td>
		<td style='vertical-align: bottom;'>
			<input  class='addNoticeBackColor' type='text' 
					name='author' maxlength='32' readonly='readonly'
					value='<?php echo ucwords($_SESSION['fullName']); ?>' />
		</td>
	</tr>

	<tr>
		<td align='right' style='color: #888888;'>
			Title <font class='redAstrix'>*</font>
		</td>
		<td style='vertical-align: bottom;'>
			<input  class='addNoticeBackColor addNoticeTitle' type='text' 
					name='title' maxlength='64' value='' />
		</td>
	</tr>

	<tr>
		<td align='right' style='vertical-align: top; color: #888888;'>
			Message <font class='redAstrix'>*</font><br /><br />
			<font size='2'>(<font id='messageCount'>0</font>/800)</font>
		</td>
		<td>
			<textarea class='addNoticeBackColor addNoticeTextArea' name='message' rows='10'
					  onkeyup='messageCount()'></textarea>
		</td>
	</tr>

	<tr>
		<td align='right' style='color: #888888;'>Tag</td>
		<td style='vertical-align: bottom;'>
			<input  class='addNoticeBackColor' type='text' 
					name='tag' maxlength='64' value='' />
					<font size='2' style='color: grey'>Seperate each tag with a comma</font>
		</td>
	</tr>

	<tr>
		<td align="right" style='vertical-align: top; color: #888888;'>Image</td>
		<td><input class='addNoticeBackColor' name="image" type="file" 
			accept="image/jpg,image/gif, image/jpeg, image/pjpeg, image/png" />
			<br /><br />
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center"><input type="submit" name="submit" value="Add News" /></td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>

	</table>
</form>
</div>









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