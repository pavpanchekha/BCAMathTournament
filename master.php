<?php
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
		printf("<script type=\"text/javascript\">alert(\"Please select a grade level\")</script>");
		$submitted = 0;
	}
}
?>
<html>
<head>
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
$arrO = explode("\n", file_get_contents("probs.old.txt"));
$arrY = explode("\n", file_get_contents("probs.young.txt"));
printf("So far there are <span style=\"font-weight:600;\">");
printf(count($arrO)+count($arrY)-2);
printf("</span> problems in the database.");

$contents = explode("\n", file_get_contents("probs.young.txt"));
$i = 0;
for(; $i < count($contents); $i++) {
	if(substr($contents[$i], 0, 6) == "[prob]") {
		$tmp = substr($contents[$i], 6, strlen($contents[$i])-13);
		printf("<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n");
	}
}

$contents = explode("\n", file_get_contents("probs.old.txt"));
for($j = 0; $j < count($contents); $j++) {
	if(substr($contents[$j], 0, 6) == "[prob]") {
		$tmp = substr($contents[$j], 6, strlen($contents[$j])-13);
		printf("<p class=\"".((($i+$j+1)%2==0)?"light":"dark")."\">Problem ".($i+$j).": ".$tmp."</p>\n");
	}
}
?>
<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
    <p>Use <code>\( latex \)</code> for inline latex and <code>\[ latex \]</code> for out-of-line latex.</p>
<textarea rows="5" 
			cols="80" 
			wrap="soft" 
			name="space" 
			onclick="changeBg(1)" 
			onBlur="changeBg(0)">
</textarea>
<br />
<input type="submit" value="Submit" />
</form>
</body>
</html>
