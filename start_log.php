
<?php

require_once 'includes.php';

global $DB;
require_once 'header.php';

print dashboard();

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

?>

<center>

<h1 style='font-size:100%'>Start logging a user</h1>

<form action='start_log_submit.php'>
<table>
<tr><td>Facebook User ID:</td><td><input type='text' name='facebook_id'></td></tr>
<tr><td colspan='2'><input type='submit' name='Submit' value='Submit'></td></tr>

</table>

</form>

<?php



?>

<td>


</td></tr></table>

