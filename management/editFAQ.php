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

	$_SESSION['title'] = "Manage FAQs | MegaLAN";	// Declare this page's Title
	include("../includes/template.php"); 			// Include the template page
	include("../includes/conn.php"); 				// Include the database connection



	if(isset($_POST['question'])  && isset($_POST['answer']) ) 
	{
	
		$d = dateToDatabase(date("d/m/Y"));
		$query = "UPDATE faq SET date='".$d."', question='".$_POST['question']."', answer='".$_POST['answer']."' WHERE FAQID='".$_POST['FAQID']."'";
		$result = $db->query($query);

		echo '<script type="text/javascript">window.location.href="/cassa/faq.php";</script>';
	}
	if(isset($_POST['FAQID']))
	{
		$query = "SELECT * FROM faq WHERE FAQID = '".$_POST['FAQID']."'";
		$result = $db->query($query);
		$row = $result->fetch_assoc();

		$FAQID = $_POST['FAQID'];	
		$question = $row['question'];
		$answer = $row['answer'];	
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
	
	function faqVal()
	{
		var title = (document.editFAQ['question'].value).replace(/^\s*|\s*$/g,'');
		var message = (document.editFAQ['answer'].value).replace(/^\s*|\s*$/g,'');

		if (title == '')
		{
			alert('No question have been entered');
			document.editFAQ['question'].focus();
			return false;
		}
		if (message == '')
		{
			alert('No answer has been entered');
			document.editFAQ['answer'].focus();
			return false;
		}
		else
		{
			document.getElementById("question").value = question;
			document.getElementById("answer").value = answer;
			document.forms['editFAQ'].submit();
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



<!-- Interface Box -->
<div id='newsBox' align='center'>
<form name='editFAQ' enctype="multipart/form-data" method='POST' 
	  onsubmit='return faqVal()' action='editFAQ.php'>
	<br />

	<table cellpadding='5px' border='0'>
		<tr>
			<td align='right' style='color: #888888;'>
				Question <!-- <font class='redAstrix'>*</font> -->
			</td>
			<td style='vertical-align: bottom;'>
				<input  class='addNoticeBackColor addNoticeTitle' type='text' 
						name='question' maxlength='256' value='<?php echo $question;?>' />
			</td>
		</tr>

		<tr>
			<td align='right' style='vertical-align: top; color: #888888;'>
				Answer <!-- <font class='redAstrix'>*</font> --><br /><br />
			</td>
			<td>
				<textarea class='addNoticeBackColor addNoticeTextArea' 
				name='answer' rows='10' maxlength='1024' /><?php echo $answer;?></textarea>
			</td>
		</tr>

		<tr>
			<td colspan="2" align="center"><br /><input type="submit" name="submit" value="Submit FAQ" /></td>
		</tr>

		<tr><td colspan="2">&nbsp;</td></tr>

		<!-- ENSURES TO THE SERVER THERE EITHER IS OR IS NOT A FILE TO BE SAVED-->
		<input type='hidden' name='FAQID' value='<?php echo $FAQID; ?>' />
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