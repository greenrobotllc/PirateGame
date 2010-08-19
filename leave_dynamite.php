<?php

require_once 'includes.php';

global $DB;

print dashboard();

$DB->('insert into booby_traps () values()');
?>

<center>
<fb:success>
     <fb:message>Ahoy!<br>You left a dynamite booby trap! You'll level up when it blows up another pirate!</fb:message>
</fb:success>
<p style='text-align:center; padding:20px'>Your monkey-dynamite team will wait here until a pirate searches for some booty.  When they blow someone up you'll level up!</p>
<table>
<tr>
<td>
<fb:photo pid='<?php echo $item_dynamite_small; ?>' uid="<?php echo $image_uid; ?>" />
</td><td>
<fb:photo pid='<?php echo $item_monkey_small; ?>' uid="<?php echo $image_uid; ?>" />
</td></tr></table>


<h2 style='padding:10px' ><a href="index.php">Arrr..... return to sailin'</a></h2>

<br>
</center>

<?php require_once 'footer.php'; ?>