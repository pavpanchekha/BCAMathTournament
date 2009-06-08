<?php
$probs_ed_min = 5;
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

// get # problems
$count = count(simplexml_load_file('leprobs.xml'));
echo "<p>FYI: There are <span style=\"font-weight:600;\">$count</span> problems in the database. ";
echo "That is, we're ".round($count/350*100, 1)."% there!</p>\n";

// progress bar
echo '<div id="progressframe" style="width: 700px; height: 50px; border: 2px solid black">';
echo '<div id="progress" style="width: '.($count*2).'px; background: '.(($count > 350)?'green':'yellow').'; height: 50px;"></div>';
echo '</div>';

echo "<p>If your name is bolded, then you have written <strike>25</strike>&nbsp;<strike>17</strike>&nbsp;<strike>10</strike>&nbsp;(omg this is sad T_T) $probs_ed_min or more problems and may qualify as a problem editor!</p><ol>\n";

foreach($new_arr as $key => $val) {
	if($val >= $probs_ed_min && $key != "Sherry Wu") echo "<span style=\"font-weight:700;\">";
	else if($key == "Sherry Wu") echo "<span style=\"font-style:italic;\">";
	echo "<li>".$key;
	if($key == "Sherry Wu") echo " (Student Coordinator)";
	echo ": ".$val." (".round($val/$count*100, 1)."%)</li>";
	if($val >= $probs_ed_min || $key == "Sherry Wu") echo "</span>";
	echo "\n";
}
echo "</ol>";

?>
<h3>Keep up the great work!</h3>
</body>
</html>
