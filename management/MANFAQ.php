<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
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

	$_SESSION['title'] = "Manage FAQs | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 			// Include the template page
	include("../includes/conn.php"); 				// Include the database connection

	// IF FAQ IS POSTED, 
	// VALIDATE, INSERT
	if (isset($_POST['submit']))
	{
		$_POST = array_map("mysql_real_escape_string", $_POST);
		$_POST = array_map("trim", $_POST);

		$q = $_POST['question'];
		$d = dateToDatabase(date("d/m/Y"));
		$a = $_POST['answer'];
		$_SESSION['errMsg'] = '';

		// VALIDATE
		if ($q == '')
		{
			$_SESSION['errMsg'] .= '<br /><font class="error">*Question must not be blank</font>';
		}
		if ($a == '')
		{
			$_SESSION['errMsg'] .= '<br /><font class="error">*Answer must not be blank</font>';
		}

		// INSERT
		if ($_SESSION['errMsg'] == '')
		{
			$insert = "INSERT INTO faq VALUES ('', '".$d."', '".$q."', '".$a."')";
			$result = $db->query($insert);
		}
	}
?>



<!-- //******************************************************

// Name of File: MANFAQ.php
// Revision: 1.0
// Date: 09/05/2012
// Author: Luke S
// Modified: 

//***********************************************************

//*********** Start of EDIT NOTICES PAGE *************** -->

<head>
<script type='text/javascript'>
	
	function faqVal()
	{
		var title = (document.addFAQ['question'].value).replace(/^\s*|\s*$/g,'');
		var message = (document.addFAQ['answer'].value).replace(/^\s*|\s*$/g,'');

		if (title == '')
		{
			alert('No question have been entered');
			document.addFAQ['question'].focus();
			return false;
		}
		if (message == '')
		{
			alert('No answer has been entered');
			document.addFAQ['answer'].focus();
			return false;
		}
		else
		{
			document.getElementById("question").value = question;
			document.getElementById("answer").value = answer;
			document.forms['addFAQ'].submit();
		}
	}

</script>
</head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Create FAQs</h1>







<?php
	if (isset($_SESSION['errMsg']))
	{
		echo $_SESSION['errMsg'];
		unset($_SESSION['errMsg']);
		echo '<br />';
	}
?>







<!-- Interface Box -->
<div id='newsBox' align='center'>
<form name='addFAQ' enctype="multipart/form-data" method='POST' 
	  onsubmit='return faqVal()' action='MANFAQ.php'>
	<br />

	<table cellpadding='5px' border='0'>

	<tr>
		<td align='right' style='color: #888888;'>
			Question
		</td>
		<td style='vertical-align: bottom;'>
			<input  class='addNoticeBackColor addNoticeTitle' type='text' 
					name='question' maxlength='256' value='' />
		</td>
	</tr>

	<tr>
		<td align='right' style='vertical-align: top; color: #888888;'>
			Answer<br /><br />
		</td>
		<td>
			<textarea class='addNoticeBackColor addNoticeTextArea' 
			name='answer' rows='10' > </textarea>
		</td>
	</tr>

	<tr>
		<td colspan="2" align="center"><br /><input type="submit" name="submit" value="Create FAQ" /></td>
	</tr>

	<tr><td colspan="2">&nbsp;</td></tr>

	<!-- ENSURES TO THE SERVER THERE EITHER IS OR IS NOT A FILE TO BE SAVED-->
	<input type='hidden' name='FAQID' value='' />

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