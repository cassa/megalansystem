<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"> 
<html xmlns="http://www.w3.org/1999/xhtml"> 

<?php
	session_start();									// Start/resume THIS session
	$_SESSION['title'] = "MegaLAN Management System"; 	// Declare this page's Title
	include("includes/template.php"); 					// Include the template page
	include("includes/conn.php"); 						// Include the database connection
	include("includes/functions.php"); 					// Include common functions

	if (isset($_POST['deleteNews']))
	{
		// DELETE [this] ARTICLE
		$stmt = $db->prepare("DELETE FROM news WHERE newsID=?");
		$stmt->bind_param('s', $_POST['deleteNews']);
		$stmt->execute();
		$stmt->close();
	}
?>

<!-- //******************************************************

// Name of File: home.php
// Revision: 1.0
// Date: 07/04/2012
// Author: Quintin M
// Modified: Quintin M 09/04/2012

//***********************************************************

//*************** Start of HOME PAGE ******************* -->

<head>

<script type='text/javascript'>
function editArticle(x)
{
	document.getElementById('editNewsID').value=x;
	document.forms['editNewsArticle'].submit();
}
function deleteArticle(x)
{
	var answer = confirm("Are you sure you want to delete this article?");
	if (answer == true)
	{
		// Delete [x]
		document.deleteNewsArticle['deleteNews'].value = x;
		document.forms['deleteNewsArticle'].submit();
	}
}
</script>

</head>
<body>
<center>
<div id='shell'>





<!-- Main Content [left] -->
<div id="content">





<!-- FETCH RECENT NEWS FROM DATABASE -->
<?php

echo '<div id="article">';
	// FETCH ALL NEWS
	$query = "SELECT * FROM news ORDER BY `newsID` DESC";
	$result = $db->query($query);

	for ($i=0; $i<$result->num_rows; $i++)
	{
		$row = $result->fetch_assoc();
		$newsID = $row['newsID'];
		$subject = $row['subject'];
		$date = $row['date'];
		$author = $row['author'];
		$message = $row['message'];
		$imageDir = $row['image'];
		$tag = $row['tag'];


	// DISPLAY [THIS] ARTICLE
		// TITLE
		echo '<div class="articleTitle" style="border: 0px solid black;">'.$subject.'<br /></div>';

		// DATE - AUTHOR
		echo '<div class="articleSubTitle">';
			echo 'Posted on <u>'.dateToScreen($date).'</u> by <u>'.$author.'</u>';
		echo '</div><br/>';

		// IF USER = STAFF, ADD TOOLBOX [EDIT/DELETE]
		if (isset($_SESSION['isAdmin']))
		{
			if ($_SESSION['isAdmin'] == 1 || $_SESSION['isAdmin'] == 2 )
			{
				// TOOLBOX
				echo '<div class="articleToolBox">';
					// EDIT [this]
					echo '<img class="pointer" src="images/buttons/edit_60.png" title="Edit" onclick="editArticle('.$newsID.')" />';

					// DELETE [this]
					echo '<img class="pointer" src="images/buttons/delete_60.png" title="Delete" onclick="deleteArticle('.$newsID.')"/>';
				echo '</div>';
				echo '<br /><br />';
			}
		}


		// MESSAGE
		echo '<div class="articleMessage">'.$message.'<br /><br /></div>';

		// IMAGE
		if (!empty($imageDir))
		{
			echo '<div class="articleImage"><img src="NewsArticle/uploads/'.$imageDir.'" title="'.$subject.'" /></div>';
		}

		// TAGS
		echo '<div class="articleTag">Tagged <u>'.$tag.'</u><br /><br /></div>';


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
<form name='editNewsArticle' method='POST' action='/cassa/management/editNotices.php'>
<input type='hidden' name='editNewsID' id='editNewsID' value='' />
</form>





<!-- FORM - for posting [this] article to be deleted -->
<form name='deleteNewsArticle' method='POST' action='/cassa/home.php'>
<input type='hidden' name='deleteNews' id='deleteNews' value='' />
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