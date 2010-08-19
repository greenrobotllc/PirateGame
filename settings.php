
<?php
require_once 'includes.php';

print dashboard();

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$joke_on = get_joke_on($user);

$bomb_on = get_bomb_on($user);
$use_old_image = get_use_old_image($user);
$gender = get_gender($user);
//echo "gen: $gender";
?>

<center>

<h1 style='font-size:200%'>Pirate Settings!</h1>

<br>Arrrrrrr!<br>



<form action='settings_post.php'>
<table style='border:1px dotted black; padding:10px; margin:10px; width:400px'>



<tr><td><p style='font-size:150%; padding:10px'>
Do you want Pirate<br>Jokes in yer profile?
</p></td><td>

<select name='joke_on_off'>
<option value='1'<?php if($joke_on) { echo 'selected'; } ?> >Yes</option>
<option value='0'
<?php if(!$joke_on) { echo 'selected'; } ?> >No</option>
</input>
</td></tr>



<tr><td><p style='font-size:150%; padding:10px'>
Gender of yer Pirate
</p></td><td>

<select name='gender'>
<option value='m'<?php if($gender == 'm') { echo 'selected'; } ?> >Male</option>
<option value='f'
<?php if($gender == 'f') { echo 'selected'; } ?> >Female</option>
</input>
</td></tr>



<tr><td><p style='font-size:150%; padding:10px'>
Use 'Old-style' Pirate Image
</p></td><td>

<select name='use_old_image'>
<option value='0'

<?php if($use_old_image == '0') { echo 'selected'; } ?> >No</option>

<option value='1'<?php if($use_old_image == '1') { echo 'selected'; } ?> >Yes</option>

</input>

</td></tr>
<?php

$level = get_level($user);

if($level > 300) {

?>

<tr><td><p style='font-size:150%; padding:10px'>
Do you want want bombs and dynamite traps enabled?</p><p style='padding:10px'>This option can only be turned off after level 300.  If you disable it you won't be presented with options to throw bombs or throw dynamite.  You also won't get hit by bombs or dynamite.
</p></td><td>

<select name='bomb_on_off'>
<option value='1'<?php if($bomb_on) { echo 'selected'; } ?> >Yes</option>
<option value='0'
<?php if(!$bomb_on) { echo 'selected'; } ?> >No</option>
</input>
</td></tr>


<?php

}

?>


<tr>

<td align='right'>

&nbsp;

</td>



<td align='right'>

<div style='text-align:right'><input type='submit' name='Submit' value='Submit'></div>

</td>

</tr>
</table>

</form>



<?php

require_once 'footer.php'; ?>