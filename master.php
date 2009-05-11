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
<style type="text/css">
@import 's.css';
</style>
<script type="text/javascript">
function changeBg(a) {
	document.submission.space.style.background = ((a)?'#FFFFE0':'white');
}
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
</script>
</head>
<body>
<h2>BCA Math Competition Problem Submission Site</h2>
<!-- toolbar -->
<div style="float:right;">
	See:&nbsp;<a href="javascript:changeView(0)">All</a>&nbsp;•
	<a href="javascript:changeView(1)">4,5,6</a>&nbsp;•
	<a href="javascript:changeView(2)">7,8</a>
</div>

<?php

// get # problems
$arrO = explode("[prob]", file_get_contents("probs.old.txt"));
$arrY = explode("[prob]", file_get_contents("probs.young.txt"));
array_shift($arrO); array_shift($arrY);
$contents = array_merge($arrO, $arrY);
echo "There are <span style=\"font-weight:600;\">";
echo count($contents);
echo "</span> problems in the datumsbase. <br />\n";
echo "Now viewing: <span id=\"viewing\">All</span> problems\n";

echo "<div id=\"all\">\n";
for($i = 0; $i < count($contents); $i++) {
	if(substr($contents[$i], strlen($contents[$i])-8, 7)=="[/prob]") {
		$tmp = substr($contents[$i], 0, strlen($contents[$i])-8);
		echo "<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n";
	}
} echo "</div>\n";

echo "<div id=\"young\" style=\"display: none;\">\n";
for($i = 0; $i < count($arrY); $i++) {
	if(substr($arrY[$i], strlen($arrY[$i])-8, 7)=="[/prob]") {
		$tmp = substr($arrY[$i], 0, strlen($arrY[$i])-8);
		echo "<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n";
	}	
} echo "</div>\n";

echo "<div id=\"old\" style=\"display: none;\">\n";
for($i = 0; $i < count($arrO); $i++) {
	if(substr($arrO[$i], strlen($arrO[$i])-8, 7)=="[/prob]") {
		$tmp = substr($arrO[$i], 0, strlen($arrO[$i])-8);
		echo "<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n";
	}	
} echo "</div>\n";

?>

<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
<p>Use <code>&#92;( latex &#92;)</code> for inline latex and <code>&#92;[ latex &#92;]</code> for out-of-line latex.</p>
<textarea rows="5" 
			cols="80" 
			style="white-space: normal;" 
			name="space" 
			onclick="changeBg(1)" 
			onBlur="changeBg(0)">
</textarea>
<p>
Type the answer to your problem in this text box: <input type="text" name="answer" />
</p>
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
	$ans = trim(htmlspecialchars($_POST["answer"]));
	if($ans = "") die("You forgot to include the answer!<br /><a href=\".\">Try again</a>");
	if(strlen($a) != 0) {
		// back up existing file to probs.back.txt
		if($_POST["agegroup"] == "young") {
			file_put_contents("probs.young.back.txt", file_get_contents("probs.young.txt"));
			file_put_contents("probs.young.txt", "[prob]".$a." (".$ans.")"."[/prob]\n", FILE_APPEND);
			echo "Thanks for your submission!<br /><a href=\"master.php\">Back</a>";
		}
		else if($_POST["agegroup"] == "old") {
			file_put_contents("probs.old.back.txt", file_get_contents("probs.old.txt"));
			file_put_contents("probs.old.txt", "[prob]".$a." (".$ans.")"."[/prob]\n", FILE_APPEND);
			echo "Thanks for your submission!<br /><a href=\"master.php\">Back</a>";
		}
		else {
			die("Please select a grade level");
		}
	}
}
?>
</body>
</html>
