<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by Sherry Wu and Pavel Panchekha.
Thanks to Union College for jsMath.
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

</script>
</head>
<body>
<h2>BCA Math Competition Problem Submission Site</h2>
<?php

// get # problems
$arrO = explode("[prob]", file_get_contents("probs.old.txt"));
$arrY = explode("[prob]", file_get_contents("probs.young.txt"));
array_shift($arrO); array_shift($arrY);
$contents = array_merge($arrO, $arrY);
echo "There are <span style=\"font-weight:600;\">";
echo count($contents);
echo "</span> problems in the database.";

for($i = 0; $i < count($contents); $i++) {
	if(substr($contents[$i], strlen($contents[$i])-8, 7)=="[/prob]") {
		$tmp = substr($contents[$i], 0, strlen($contents[$i])-8);
		echo "<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n";
	}
}
?>
<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
<p>Use <code>\( latex \)</code> for inline latex and <code>\[ latex \]</code> for out-of-line latex.</p>
<textarea rows="5" 
			cols="80" 
			style="white-space: normal;" 
			name="space" 
			onclick="changeBg(1)" 
			onBlur="changeBg(0)">
</textarea>
<p>
Please select the appropriate gradelevel for your problem:<br />
<input type="radio" name="agegroup" value="young" />4th, 5th, and 6th grade<br />
<input type="radio" name="agegroup" value="old" />7th and 8th grade<br />
</p>
<input type="submit" value="Submit" name="submit" />
</form>
<?php
} else {
	$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
	$a = str_ireplace("\\\\", "\\", $a);
	if(strlen($a) != 0) {
		// back up existing file to probs.back.txt
		if($_POST["agegroup"] == "young") {
			file_put_contents("probs.young.back.txt", file_get_contents("probs.young.txt"));
			file_put_contents("probs.young.txt", "[prob]".$a."[/prob]\n", FILE_APPEND);
			$submitted = 1;
		}
		else if($_POST["agegroup"] == "old") {
			file_put_contents("probs.old.back.txt", file_get_contents("probs.old.txt"));
			file_put_contents("probs.old.txt", "[prob]".$a."[/prob]\n", FILE_APPEND);
			$submitted = 1;
		}
		else {
			echo "<script type=\"text/javascript\">alert(\"Please select a grade level\")</script>";
			$submitted = 0;
		}
	}
}
?>
</body>
</html>
