<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start();									// Start/resume THIS session
	$_SESSION['title'] = "MegaLAN Management System | FAQs"; 	// Declare this page's Title
	include("includes/template.php"); 					// Include the template page
	include("includes/conn.php"); 						// Include the database connection

	if (isset($_POST['FAQID']))
	{
	// Delete a Question
	$query = "DELETE FROM faq WHERE FAQID = '".$_POST['FAQID']."'";
	$result = $db->query($query) or die(mysql_error());
	}
?>


<!-- //******************************************************

// Name of File: faq.php
// Revision: 1.0
// Date: 07/04/2012
// Author: Quintin M
// Modified: Luke Spartalis 22/04/2012

//***********************************************************

//*************** Start of HOME PAGE ******************* -->

<head>

<script type='text/javascript'>
function editArticle(x)
{
	document.getElementById('FAQID').value=x;
	document.forms['editQuestion'].submit();
}
function deleteQuestion(x)
{
	var answer = confirm("Are you sure you want to delete this Question?");
	if (answer == true)
	{
		// Delete [x]
		document.deleteQuestionForm['FAQID'].value = x;
		document.forms['deleteQuestionForm'].submit();
	}
}
</script>

</head>
<body>
<center>
<div id='shell'>



<!-- Main Content [left] -->
<div id="content">
	<h1>Frequently Asked Questions</h1>
	<br />


<!-- FETCH RECENT NEWS FROM DATABASE -->
<?php

echo '<div id="article">';
	// FETCH ALL NEWS
	$query = "SELECT * FROM `faq`";
	$result = $db->query($query);

	for ($i=0; $i<$result->num_rows; $i++)
	{
		$row = $result->fetch_assoc();
		$FAQID = $row['FAQID'];
		$date = dateToScreen($row['faqDate']);
		$question = $row['question'];
		$answer = $row['answer'];


	// DISPLAY [THIS] QUESTION
		// TITLE
		echo '<div class="articleTitle" style="border: 0px solid black;">'.$question.'<br /></div>';
		echo '<div class="articleSubTitle" > Question last updated: ' . $date. '</div>'; 

		// IF USER = STAFF, ADD TOOLBOX [EDIT/DELETE]
		if (isset($_SESSION['isAdmin']))
		{
			if ($_SESSION['isAdmin'] == 1 || $_SESSION['isAdmin'] == 2 )
			{
				// TOOLBOX
				echo '<div class="articleToolBox">';
					// EDIT [this]
					echo '<img class="pointer" src="images/buttons/edit_60.png" title="Edit" onclick="editArticle('.$FAQID.')" />';

					// DELETE [this]
					echo '<img class="pointer" src="images/buttons/delete_60.png" title="Delete" onclick="deleteQuestion('.$FAQID.')"/>';
				echo '</div>';
				echo '<br /><br />';
			}
		}


		// MESSAGE
		echo '<br><br><div class="articleMessage">'.$answer.'<br /><br /></div>';


		// IF THERE IS ANOTHER ARTICLE -add a blue line spacer
		if ($i+1 < $result->num_rows)
		{
			// ADD BLUE LINE
			echo '<br /><br />';
			echo '<div class="blueLine700"></div>';
			echo '<br /><br />';
		}
		// ELSE -add blank space
		else
		{
			// ADD BLANK SPACES
			echo '<br /><br />';
		}
	}
echo '</div>';
?>
<!-- end of: RECENT NEWS -->





<!-- FORM - for posting [this] article to be edited -->
<form name='editQuestion' method='POST' action='/cassa/management/editFAQ.php'>
<input type='hidden' name='FAQID' id='FAQID' value='' />
</form>


<!-- FORM - for posting [this] article to be deleted -->
<form name='deleteQuestionForm' method='POST' action='/cassa/faq.php'>
<input type='hidden' name='FAQID' id='FAQID' value='' />
</form>




<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
<!-- ********************************* -->

</div><!-- end of: Content -->


<!-- INSERT: rightPanel -->
<?php include('includes/rightPanel.html'); ?>


<!-- INSERT: footer -->
<div id="footer">
	<?php include('includes/footer.html'); ?>
</div>


</div><!-- end of: Shell -->

</center>
</body>
</html>

<!-- ********************************* -->
<!-- INCLUDE THIS AFTER 'MAIN CONTENT' -->
