<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<!--
Created by Sherry Wu and Pavel Panchekha.
Thanks to Union College for jsMath.
-->
<?php
include('password_protect.php');
require_once('recaptchalib.php');
$submitted = -1;
if(!isset($_POST["go"])) {
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
<h2>BCA Math Competition Problem Submissions</h2>
<span style="font-weight: 700; font-size: 14pt;">Read this first:</span>
<p>When writing a problem for the math competition, you should consider these things:</p>
<ul>
<li>Problems should be accessible to 4th, 5th, 6th, 7th, and 8th graders.</li>
<li>For 4th and 5th grade problems, emphasize elementary topics such as geometry and arithmetic.</li>
<li>Logic questions are also good.</li>
<li>Previous years' problems are available <a href="http://sites.bergen.org/mathcompetition/exams.asp">here</a>. 
Please do not copy these problems verbatim; change the names of the people and the setting if you are going to reuse the idea.</li>
<li><span style="font-weight: 700;">Be sure to type in the answer to your problem in the Answer field! Problems without answers will be rejected!</span></li>
<li>Last but not least, be creative!</li>
</ul>
<?php
// get # problems
$arr = explode("<problem>", file_get_contents("leprobs.xml"));
printf("FYI: There are <span style=\"font-weight:600;\">");
printf(count($arr));
printf("</span> problems in the database.");
?>
<p>
	Use <code>\( latex \)</code> for inline latex and <code>\[ latex \]</code> for out-of-line latex. 
	<a href="http://en.wikipedia.org/wiki/LaTeX">(Huh?)</a>
</p>
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
<?php
} else {
	$privatekey = "6LcIVwYAAAAAAMqZxccawplhrDu2hnvRzmwm8s5r";
	$resp = recaptcha_check_answer ($privatekey, $_SERVER["REMOTE_ADDR"], $_POST["recaptcha_challenge_field"], $_POST["recaptcha_response_field"]);
	if (!$resp->is_valid) {
		die ("The reCAPTCHA wasn't entered correctly. Go back and try it again." .
			"(reCAPTCHA said: " . $resp->error . ")");
	}
	
	$a = str_ireplace("\n", "", trim(htmlspecialchars($_POST["space"])));
	$a = str_ireplace("\\\\", "\\", $a);
	$ans = trim(htmlspecialchars($_POST["answer"]));
	$author = trim(htmlspecialchars($_POST["author"]));
	if($ans == "") die("You forgot to include the answer!<br /><a href=\".\">Try again</a>");
	if($author == "") die("You forgot to put your name!<br /><a href=\".\">Try again</a>");
	if(strlen($a) != 0) {
		$tmp = file_get_contents("leprobs.xml");
		$tmp = str_replace("</db>", "", $tmp);
		file_put_contents("leprobs.xml", $tmp);
		if($_POST["agegroup"] == "young") {
			file_put_contents("leprobs.xml", "<problem>\n\t<author>".$author."</author>\n\t<text>".$a."</text>\n\t<answer>".$ans."</answer>\n\t<grade>young</grade>\n</problem>\n</db>\n", FILE_APPEND);
			$submitted = 1;
		}
		else if($_POST["agegroup"] == "old") {
			file_put_contents("leprobs.xml", "<problem>\n\t<author>".$author."</author>\n\t<text>".$a."</text>\n\t<answer>".$ans."</answer>\n\t<grade>old</grade>\n</problem>\n</db>\n", FILE_APPEND);
			$submitted = 1;
		}
		else die("You forgot to include the grade level!<br /><a href=\".\">Try again</a>");
	}
	else die("Your problem is too short!<br /><a href=\".\">Try again</a>");
}

if($submitted == 1) {
	file_put_contents("leprobs.back.xml", file_get_contents("leprobs.xml"));
	printf("Thanks for your submission!<br />\n");
	printf("<a href=\".\">Enter another problem</a>");
}
$submitted = -1;
?>
<p><a href="http://validator.w3.org/check?uri=referer">
<img src="http://www.w3.org/Icons/valid-xhtml10-blue" alt="Valid XHTML 1.0 Transitional" height="31" width="88" />
</a></p>
</body>
</html>
