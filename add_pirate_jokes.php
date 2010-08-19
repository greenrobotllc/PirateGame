
<?php
require_once 'includes.php';

print dashboard();

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

?>

<fb:tabs>
	<fb:tab_item href="surveys.php" title="Surveys" />
	<fb:tab_item href="offers.php" title="Free Offers" />
	<fb:tab_item href="add_pirate_jokes.php" selected='true' title="Submit a Pirate Joke"/>
</fb:tabs><br>

<center>

<h1 style='font-size:200%'>Pirate Jokes!</h1>

<br>Arrrrrrr!<br>

<p style='font-size:150%; padding:10px'>
Tell us a Pirate Joke!  If we use it, we'll credit you 200 coins!  Please limit submissions to 10 per day.
</p>

</center>


<center>

<table width='90%' cellpadding='20px'><tr><td valign='top'>
<div style='font-size:150%'>
<h1>Submit yer joke here</h1><br><br>
It should be in the form of :<br>
Question<br>
Answer<br><br>

<form action='pirate_joke_submit.php'>
<table>
<tr><td>Question:</td><td><input type='text' name='question'></td></tr>
<tr><td>Answer:</td><td><input type='text' name='answer'></td></tr>
<tr><td colspan='2'><input type='submit' name='Submit' value='Submit'></td></tr>
</table>

</form>

<br><br>
Example:<br>
Q: Why are Pirates so Cool?<br>
A: They just ARRR!<br>
</div>
<p>All jokes are reviewed for coolness and pirateness. Anything not funny will be rejected!</p>
<?php



?>
<td>


</td></tr></table>




<?php

require_once 'footer.php'; ?>