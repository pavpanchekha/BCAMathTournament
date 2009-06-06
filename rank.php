<?php
$probs_ed_min = 25;
?>
<html>
<head>
<title>Problem Submission Ranks!</title>
</head>
<body>
<!--<h1>[DEBUG MODE]</h1>-->
<h3>Our top contributors are:</h3>
<?php
$str = file_get_contents("leprobs.xml");
$tok = strtok($str, "\n");
$new_arr = array();
$isabsent = 1; $loc = 0;
while($tok !== false) {
	if(substr(trim($tok), 1, 6) == 'author') {
		$lePerson = substr(trim($tok), 8, strlen(trim($tok))-17);
		for($i = 0; $i < count($new_arr) && $isabsent == 1; $i++) {
			if($new_arr[$lePerson] > 0) {
				$isabsent = 0;
			}
		}
		if($isabsent == 1) $new_arr[$lePerson] = 1;
		else if($isabsent == 0) $new_arr[$lePerson]++;
	}
	$tok = strtok("\n");
	$isabsent = 1;
}
arsort($new_arr);
echo "If your name is bolded, then you have written $probs_ed_min or more problems and may qualify as a problem editor!<ol>\n";

foreach($new_arr as $key => $val) {
	if($val >= $probs_ed_min && $key != "Sherry Wu") echo "<span style=\"font-weight:700;\">";
	else if($key == "Sherry Wu") echo "<span style=\"font-style:italic;\">";
	echo "<li>".$key;
	if($key == "Sherry Wu") echo " (Student Coordinator)";
	echo ": ".$val."</li>";
	if($val >= $probs_ed_min || $key == "Sherry Wu") echo "</span>";
	echo "\n";
}
echo "</ol>";

?>
<h3>Keep up the great work!</h3>
</body>
</html>
