<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by Sherry Wu and Pavel Panchekha.
Thanks to Union College for jsMath.
Thanks to Carnegie Mellon for reCAPTCHA.
-->
<?php
$xml = simplexml_load_file('leprobs.xml');

if(isset($_POST["go"])) {
	$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
	$a = str_ireplace("\\\\", "\\", $a);
	$answer = str_ireplace("\\\\", "\\", trim(htmlspecialchars($_POST["answer"])));
	$author = trim(htmlspecialchars($_POST["author"]));
	$age = $_POST["agegroup"];

	// BEGIN validate fields
	if($answer == "") die("You forgot to include the answer!<br /><a href=\"master.php\">Try again</a>");
	if($author == "") die("You forgot to put your name!<br /><a href=\"master.php\">Try again</a>");
	if($a == "") die("You didn't submit a problem!<br />".'<a href="master.php">Try again</a>');
	if($age != "old" && $age != "young") die("You didn't select an age group!<br /><a href=\"master.php\">Try again</a>");
	// END validate fields

	$newProb = $xml->addChild('problem');
	$newProb->addChild('author', $author);
	$newProb->addChild('text', $a);
	$newProb->addChild('answer', $answer);
	$newProb->addChild('grade', $age);
	$newProb->addChild('rating', 0);
	file_put_contents('leprobs.xml', $xml->asXML());
	echo 'Thanks for your submission!<br /><a href="master.php">Back</a>';
}
?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
<title>BCA Math Tournament Master Submission Site</title>
<script src="/jsMath/easy/load.js"></script>
<script src="/jsMath/jsMath-easy-load.js"></script>
<script src="/jsMath/plugins/autoload.js"></script>
<script src="/jsMath/plugins/spriteImageFonts.js"></script>
<style type="text/css">
@import 's.css';
</style>
<script type="text/javascript">
function changeBg(a) {
	document.submission.space.style.background = ((a)?'#FFFFE0':'white');
}
</script>
</head>
<body>
<div id="centre">
<h2>BCA Math Competition Problem Submission Site</h2>
<!-- toolbar
<div style="float:right;">
	See:&nbsp;<a href="javascript:changeView(0)">All</a>&nbsp;•
	<a href="javascript:changeView(1)">4,5,6</a>&nbsp;•
	<a href="javascript:changeView(2)">7,8</a>
</div>
-->
<?php

// get # problems
echo "FYI: There are <span style=\"font-weight:600;\">".count($xml)."</span> problems in the database.\n";

//=====================================================

$i = 0;
foreach($xml as $value) {
	echo '<p class="prob">';
	echo "$value->author wrote: <br />$value->text<br />";
	echo "Answer: $value->answer<br />";
	echo "Category: $value->grade<br />";
	echo "Rating: $value->rating";
	echo '<form name="rate'.$i.'" method="post" action="rate.php">';
	echo '<input type="hidden" name="formname" value="'.$i.'" />';
	echo '<input type="submit" name="up" value="+" />&nbsp;';
	echo '<input type="submit" name="down" value="-" />';
	echo "</form></p>\n";
	$i++;
}

//======================================================
?>

<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
<p>Use <code>\(\backslash( latex \backslash)\)</code> for inline latex and <code>\(\backslash[ latex \backslash]\)</code> for out-of-line latex.</p>
<p>Type your name in the text box to the right: <input type="text" name="author" /></p>
<textarea rows="5" 
			cols="80" 
			style="white-space: normal;" 
			name="space" 
			onclick="changeBg(1)" 
			onBlur="changeBg(0)">
</textarea>
<p>Type the answer to your problem in this text box: <input type="text" name="answer" /></p>
<p>
Select the appropriate grade level for your problem:<br />
<input type="radio" name="agegroup" value="young" />4th, 5th, and 6th grade<br />
<input type="radio" name="agegroup" value="old" />7th and 8th grade<br />
</p>
<input type="submit" value="Submit" name="go" />
</form>
</div>
</body>
</html>
