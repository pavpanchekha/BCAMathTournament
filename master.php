<?php
$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
if(strlen($a) != 0) 
	file_put_contents("probs.txt", "[prob]".$a."[/prob]\n", FILE_APPEND);
?>
<html>
<head>
<title>PHP Test</title>
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
$contents = explode("\n", file_get_contents("probs.txt"));
for($i = 0; $i < count($contents); $i++) {
	if(substr($contents[$i], 0, 6) == "[prob]") {
		$tmp = substr($contents[$i], 6, strlen($contents[$i])-13);
		printf("<p class=\"".(($i%2==0)?"light":"dark")."\">Problem ".($i+1).": ".$tmp."</p>\n");
	}
}
?>
<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<h3>Enter a Problem:</h3>
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
