<?php
require('password_protect.php');
require_once('recaptchalib.php');
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by Sherry Wu and Pavel Panchekha.
Thanks to Union College for jsMath.
Thanks to Carnegie Mellon for reCAPTCHA.
-->
<?php
$xml = simplexml_load_file('leprobs.xml');

if(isset($_POST["go"])) {
	$privatekey = "6LcIVwYAAAAAAMqZxccawplhrDu2hnvRzmwm8s5r";
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	if (!$resp->is_valid) {
		die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			"(reCAPTCHA said: " . $resp->error . ")");
	}

	$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
	$a = str_ireplace("\\\\", "\\", $a);
	$answer = str_ireplace("\\\\", "\\", trim(htmlspecialchars($_POST["answer"])));
	$author = trim(htmlspecialchars($_POST["author"]));
	$age = $_POST["agegroup"];

	// BEGIN validate fields
	if($answer == "") die("You forgot to include the answer!<br /><a href=\".\">Try again</a>");
	if($author == "") die("You forgot to put your name!<br /><a href=\".\">Try again</a>");
	if($a == "") die("You didn't submit a problem!<br />".'<a href=".">Try again</a>');
	if($age != "old" && $age != "young") die("You didn't select an age group!<br /><a href=\".\">Try again</a>");
	// END validate fields

	$newProb = $xml->addChild('problem');
	$newProb->addChild('author', $author);
	$newProb->addChild('text', $a);
	$newProb->addChild('answer', $answer);
	$newProb->addChild('grade', $age);
	$newProb->addChild('rating', 0);
	file_put_contents('leprobs.xml', $xml->asXML());
	echo 'Thanks for your submission!';
}

?>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="Content-type" content="text/html; charset=UTF-8" />
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
<div id="centre">
<h2>BCA Math Competition Problem Submissions</h2>
<span style="font-weight: 700; font-size: 14pt;">Read this first:</span>
<p>When writing a problem for the math competition, you should consider these things:</p>
<ul>
<li>Problems should be accessible to 4th, 5th, 6th, 7th, and 8th graders.</li>
<li>For 4th and 5th grade problems, emphasize elementary topics such as geometry, arithmetic, and patterns.</li>
<li>Logic questions are good.</li>
<li>Previous years' problems are available <a href="http://sites.bergen.org/mathcompetition/exams.asp">here</a>. <br />
<span style="font-weight:700;">DO NOT copy these problems or problems from anywhere else <a href="http://en.wiktionary.org/wiki/verbatim">verbatim</a></span>; change the names of the people and the setting if you are going to reuse the structure of the problem.</li>
<li>Be sure to type in the answer to your problem in the Answer field! Problems without answers will be rejected!</li>
<li>Last but not least, be creative!</li>
</ul>
<div style="border-bottom:1px dotted black;"></div><br />
<span style="font-weight: 700; font-size: 14pt;">About Community Service Hours:</span>
<p>Writing meaningful and creative problems earns you community service hours! We will need to select 250 problems for the five competitions so there will be plenty of opportunities for your problems to be picked! The number of hours you get is the integer closest to the number of problems you submitted divided by 12.
You can see how much you have contributed <a href="rank">here</a>.</p>
<div style="border-bottom:1px dotted black;"></div><br />
<span style="font-weight: 700; font-size: 14pt;">Miscellaneous:</span>
<?php
// get # problems
echo "<p>There are <span style=\"font-weight:600;\">".count($xml)."</span> problems in the database.</p>\n";
?>
<p>
	Use <code>\( latex \)</code> for inline latex and <code>\[ latex \]</code> for out-of-line latex. 
	<a href="http://en.wikipedia.org/wiki/LaTeX">(Huh?)</a>
</p>
<div style="border-bottom:1px dotted black;"></div><br />
<form name="submission" method="post" action="<?php echo $PHP_SELF; ?>">
<span style="font-weight: 700; font-size: 14pt;">Submit your own problem:</span><br />
You can be a contributor in six easy steps!
<p>0. Type your name in the text box to the right: <input type="text" name="author" /></p>
1. Type your problem into this text box:
<textarea rows="5" 
			cols="80" 
			style="white-space: normal;"
			name="space" 
			onclick="changeBg(1)" 
			onblur="changeBg(0)">
</textarea>
<p>2. Type the answer to your problem in this text box: <input type="text" name="answer" /></p>
<p>
3. Select the appropriate grade level for your problem:<br />
<input type="radio" name="agegroup" value="young" />4th, 5th, and 6th grade<br />
<input type="radio" name="agegroup" value="old" />7th and 8th grade<br />
</p>
4. Show us that you are a human by correctly completing the CAPTCHA:<br />
<?php
$publickey = "6LcIVwYAAAAAANoOVR-Yo2Jtped-AF5mN4uAYOwk"; // you got this from the signup page
echo recaptcha_get_html($publickey);
?>
<p>
5. Click the button to the right:
<input type="submit" value="Submit" name="go" />
</p>
</form>
<div style="border-bottom:1px dotted black;"></div>
<p>Kudos to:<ul>
<li>Sherry Wu and Pavel Panchekha for setting up and debugging the site!</li>
<li>git and github for making the collaboration possible!</li>
<li>Bergen County Academies Math Boosters for hosting this excellent competition!</li>
<li>Carnegie Mellon for reCAPTCHA!</li>
<li>Union College for jsMath!</li>
<li>Zubrag for easy PHP password protection!</li>
</ul></p>
<p><a href="http://validator.w3.org/check?uri=referer">
<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
</a></p>
</div>
</body>
</html>
