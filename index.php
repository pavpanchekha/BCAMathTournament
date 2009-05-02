<?php
$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
if(strlen($a) != 0) {
	file_put_contents("probs.txt", "[prob]".$a."[/prob]\n", FILE_APPEND);
	$submitted = 1;
	
	// increment
	$x = intval(file_get_contents("count.txt"));
	$x++;
	file_put_contents("count.txt", $x."\n");
}
?>
<html>
<head>
<title>Problem Submissions</title>
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
<h2>BCA Math Competition Problem Submissions</h2>
<p>Things to consider:</p>
<ul>
<li>Problems should be accessible to 4th, 5th, 6th, 7th, and 8th graders.</li>
<li>Keep trigonometry problems to a minimum.</li>
<li>Previous years' problems are available <a href="http://sites.bergen.org/mathcompetition/exams.asp">here</a>.</li>
</ul>
<?php
// get # problems
$arr = explode("\n", file_get_contents("probs.txt"));
printf("So far there are <span style=\"font-weight:600;\">");
printf(count($arr)-1);
printf("</span> problems in the database.");
?>
    <p>Use <code>\( latex \)</code> for inline latex and <code>\[ latex \]</code> for out-of-line latex.</p>
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
<?php
if($submitted) {
	printf("Thanks for your submission!\n");
	$submitted = 0;
}
?>
</body>
</html>
