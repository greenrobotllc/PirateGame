
<?php
require_once 'includes.php';

global $DB;

//print_r($_REQUEST);
//echo $user;

if($user !=1807687 && $user != 9324337) {
	$facebook->redirect('index.php');
}

$question = $_REQUEST['question'];
$answer = $_REQUEST['answer'];

global $DB, $facebook;

print dashboard($user);

$r = $DB->GetArray('select * from jokes where approved = 0');

//print_r($r);
?>
<center>
<table border='1' style='margin:20px; padding:10px' cellpadding ='10px' cellspacing='10px' width='90%'>
<?php

foreach($r as $key => $value) {

$question = $value['question'];
$answer = $value['answer'];
$joke_id = $value['id'];
$joke_user = $value['user'];

?><tr><td>
<?
print_r($question);
?>
</td>

<td>
<?
print_r($answer);
?>
</td>

<td>
<a href='approve_the_joke.php?joke_id=<?php echo $joke_id; ?>&joke_user=<?php echo $joke_user; ?>'>approve!</a>
</td>

<td>
<a href='decline_the_joke.php?joke_id=<?php echo $joke_id; ?>&joke_user=<?php echo $joke_user; ?>'>decline!</a>
</td>


</tr>
<?php
}


//$facebook->redirect('index.php?msg=joke-added');
?>


</table>
</center>