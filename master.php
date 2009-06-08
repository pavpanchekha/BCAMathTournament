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
	file_put_contents('leprobs.back.xml', file_get_contents('leprobs.xml'));
	
	// BEGIN format xml
	$dom = new DOMDocument('1.0');
	$dom->preserveWhiteSpace = false;
	$dom->formatOutput = true;
	$dom->loadXML($xml->asXML());
	file_put_contents('leprobs.xml', $dom->saveXML());
	// END format xml
	echo 'Thanks for your submission!<br /><a href="master.php">Back</a>';
}
if(isset($_POST["prev"])) {
	$startprob = ($_REQUEST["start_prob"]-50<=0)?0:($_REQUEST["start_prob"]-50);
}
else if(isset($_POST["next"])) {
	$startprob = ($_REQUEST["start_prob"]+50>=count($xml))?$_REQUEST["start_prob"]:($_POST["start_prob"]+50);
}
else $startprob = 0;
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
<?php

// get # problems
echo "FYI: There are <span style=\"font-weight:600;\">".count($xml)."</span> problems in the database.\n";
echo '<p>Showing problems '.($startprob+1).' to '.(($startprob+50>count($xml))?count($xml):$startprob+50).'.</p>';
$i = 0;
//foreach($xml as $value) {
for($i = $startprob; $i < (($startprob+50>count($xml))?count($xml):$startprob+50); $i++) {
	echo '<p class="'.(($i%2==0)?"even":"odd").'">';
	echo $xml->problem[$i]->author." wrote: <br />".$xml->problem[$i]->text."<br />";
	echo "Answer: ".$xml->problem[$i]->answer."<br />";
	echo "Category: ".$xml->problem[$i]->grade."<br />";
	echo "Rating: ".$xml->problem[$i]->rating;
	echo '<form name="rate'.$i.'" method="post" action="rate.php">';
	echo '<input type="hidden" name="formname" value="'.$i.'" />';
	echo '<input type="submit" name="up" value="+" />&nbsp;';
	echo '<input type="submit" name="down" value="-" />';
	echo "</form></p>\n";
//	$i++;
}
?>
<p>Navigation: 
<form name="nav" method="post" action="<?php echo $PHP_SELF; ?>">
<input type="hidden" name="start_prob" value="<?php echo $startprob; ?>" />
<input type="submit" value="Previous" name="prev" />
<input type="submit" value="Next" name="next" />
</form>
</p>

<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
<p>Use <code>\(\backslash( latex \backslash)\)</code> for inline LaTeX and <code>\(\backslash[ latex \backslash]\)</code> for out-of-line LaTeX.</p>
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
