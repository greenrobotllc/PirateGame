
<?php

require_once 'includes.php';
$id = $_REQUEST['id'];


print dashboard($user);

$coin_log = $DB->GetArray('select * from coin_log where user_id = ? order by date desc limit 5000', array($id));

$level_log = $DB->GetArray('select * from level_log where user_id = ? order by date desc limit 5000 ', array($id));

//print_r($r);
?>
<center>

<h1>COIN LOG for <fb:userlink uid='<?php echo $id; ?>' /> (last 5000) max miles: <?php echo get_max_miles($id); ?></h1>
<h2>log started on: <?php echo $DB->GetOne('select date from coin_log where user_id = ? order by date asc', array($id)); ?> </h2>

<table border='1' style='margin:5px; padding:5px' cellpadding ='5px' cellspacing='5px' width='90%'>

<tr>
<th>amount</th>
<th>date</th>
<th>action</th>
<th>secondary_user</th>
<th>current coin total</th>
<th>current buried coin total</th>
</tr>

<?php

foreach($coin_log as $key => $value) {

$user_id = $value['user_id'];
$amount = $value['amount'];
$date = $value['date'];
$action = $value['action'];
$secondary_user = $value['secondary_user'];
$current_coin_total = $value['current_coin_total'];
$current_buried_coin_total = $value['current_buried_coin_total'];

?><tr><td>
<?
print_r($amount);
?>
</td>

<td>
<?
print_r($date);
?>
</td>

<td>
<?
print_r($action);
?>
</td>

<td>
<?
print_r($secondary_user);
?>
</td>

<td>
<?
print_r(number_format($current_coin_total));
?>
</td>

<td>
<?
print_r(number_format($current_buried_coin_total));
?>
</td>

</tr>

<?php
}


//$facebook->redirect('index.php?msg=joke-added');
?>

</table>

<br><br>
<h1>LEVEL LOG for <fb:userlink uid='<?php echo $id; ?>' /> (last 5000)</h1>
<center>
<table border='1' style='margin:5px; padding:5px' cellpadding ='1px' cellspacing='5px' width='90%'>

<tr>
<th>date</th>
<th>action</th>
<th>secondary_user</th>
</tr>

<?php

foreach($level_log as $key => $value) {

$user_id = $value['user_id'];
$date = $value['date'];
$action = $value['action'];
$secondary_user = $value['secondary_user'];

?><tr>

<td>
<?
print_r($date);
?>
</td>

<td>
<?
print_r($action);
?>
</td>

<td>
<?
print_r($secondary_user);
?>
</td>


</tr>
<?php
}

?>

</table>
</center>