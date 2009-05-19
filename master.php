<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by Sherry Wu and Pavel Panchekha.
Thanks to Union College for jsMath.
Thanks to Carnegie Mellon for reCAPTCHA.
-->
<?php
$submitted = -1;
if(!isset($_POST["go"])) {
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
/*
function changeView(i) {
	a = document.getElementById("all");
	y = document.getElementById("young");
	o = document.getElementById("old");
	v = document.getElementById("viewing");
	if(i == 0) {
		a.style.display = "block";
		y.style.display = "none";
		o.style.display = "none";
		v.innerHTML = "All";
	}
	else if(i == 1) {
		a.style.display = "none";
		y.style.display = "block";
		o.style.display = "none";
		v.innerHTML = "4th, 5th, 6th grade ";
	}
	else if(i == 2) {
		a.style.display = "none";
		y.style.display = "none";
		o.style.display = "block";
		v.innerHTML = "7th & 8th grade ";
	}
}
*/
</script>
</head>
<body>
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
$arr = explode("<problem>", file_get_contents("leprobs.xml"));
echo "FYI: There are <span style=\"font-weight:600;\">".(count($arr) - 1)."</span> problems in the database.";
//echo "<br />Now viewing: <span id=\"viewing\">All</span> problems<br />\n";

function contents($parser, $data){ 
	echo trim(htmlspecialchars($data)); 
}

function startTag($parser, $data){ 
	if($data == "PROBLEM") echo "<p class=\"prob\">\n";
	else if($data == "TEXT") echo " wrote:<br />";
	else if($data == "ANSWER") echo "<br />Answer: ";
	else if($data == "GRADE") echo "<br />Category: ";
} 

function endTag($parser, $data){ 
	if($data == "PROBLEM") echo "\n</p>\n";
} 

$file = "leprobs.xml";
$allProbs = xml_parser_create(); 
xml_set_element_handler($allProbs, "startTag", "endTag"); 
xml_set_character_data_handler($allProbs, "contents"); 
$fp = fopen($file, "r"); 
$data = fread($fp, 80000); 
if(!(xml_parse($allProbs, $data, feof($fp))))die("Error on line " . xml_get_current_line_number($allProbs));
xml_parser_free($allProbs); 
fclose($fp);
?>

<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
<p>Use <code>&#92;( latex &#92;)</code> for inline latex and <code>&#92;[ latex &#92;]</code> for out-of-line latex.</p>
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
<?php
} else {
	$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
	$a = str_ireplace("\\\\", "\\", $a);
	$answer = trim(htmlspecialchars($_POST["answer"]));
	$age = $_POST["agegroup"];
	$author = trim(htmlspecialchars($_POST["author"]));
	if($answer == "") die("You forgot to include the answer!<br /><a href=\"master.php\">Try again</a>");
	if($author == "") die("You forgot to put your name!<br /><a href=\"master.php\">Try again</a>");
	if($a == "") die("You didn't submit a problem!<br /><a href=\"master.php\">Try again</a>");
	if($age != "young" && $age != "old") die("You didn't select an age group!<br /><a href=\"master.php\">Try again</a>");
	$tmp = file_get_contents("leprobs.xml");
	$tmp = str_replace("</db>", "", $tmp);
	file_put_contents("leprobs.xml", $tmp);
	file_put_contents("leprobs.xml", "<problem>\n\t<author>".$author."</author>\n\t<text>".$a."</text>\n\t<answer>".$answer."</answer>\n\t<grade>".$age."</grade>\n</problem>\n</db>\n", FILE_APPEND);
	echo "Thanks for your submission!<br /><a href=\"master.php\">Back</a>";
}
?>
</body>
</html>
