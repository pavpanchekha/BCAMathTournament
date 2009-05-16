<?php
$probs = 25;
?>
<html>
<head>
<title>Problem Submission Ranks!</title>
</head>
<body>
<h3>Our top contributors are:</h3>
<?php
$str = file_get_contents("leprobs.xml");
$tok = strtok($str, "\n");
$arr = array();
$isabsent = 1; $loc = 0;
while($tok !== false) {
//	echo "<pre>".htmlspecialchars($tok)."</pre>\n";
	if(substr($tok, 1, 8) == "<author>") {
		$lePerson = substr($tok, 9, strlen($tok)-18);
		for($i = 0; $i < count($arr) && $isabsent == 1; $i++) {
			if($arr[$lePerson] > 0) {
				echo $lePerson;
				$isabsent = 0;
			}
		}
		if($isabsent == 1) $arr[$lePerson] = 1;
		else if($isabsent == 0) $arr[$lePerson]++;
	}
	$tok = strtok("\n");
	$isabsent = 1;
}
arsort($arr);
echo "If your name is bolded, then you've written ".$probs." or more problems and may qualify as a problem editor!<ol>";
foreach($arr as $key => $val) {
	if($val >=$probs) echo "<span style=\"font-weight:700;\">";
	echo "<li>$key ($val)</li>\n";
	if($val >=$probs) echo "</span>";
}
echo "</ol>";
?>
<h3>Keep up the great work!</h3>
</body>
</html>
