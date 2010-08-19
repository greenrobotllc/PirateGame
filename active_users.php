
<?php
//error_reporting(E_ALL);

require_once 'includes.php';

//include_once 'fbexchange.php';

echo dashboard();
echo "<center><br>";
echo "<table><tr><td valign='top'>";
echo "<h1>active users</h1>";
echo "<br><h2>up to level 100</h2>";
$active_users = $memcache->get('active_lo');
foreach($active_users as $a => $value) {
?>
<fb:name useyou='false' uid='<?php echo $value; ?>' ifcantsee='anon' /> <?php echo $value; ?> (<?php echo get_level($value); ?>) (<?php echo 
$memcache->get($user . 'pvp'); ?>)<br>
<?
}
echo "</td><td valign='top'>";


echo "<br><br><h2>over level 100</h2>";
$active_users = $memcache->get('active_hi');
//print_r($active_users);

foreach($active_users as $a => $value) {
?>
<fb:name useyou='false' uid='<?php echo $value; ?>' ifcantsee='anon' /> <?php echo $value; ?> (<?php echo get_level($value); ?>) (<?php echo 
$memcache->get($user . 'pvp'); ?>)<br>
<?
}
echo "</td><td valign='top'>";
echo "<br><br><h2>over level 1000</h2>";
$active_users = $memcache->get('active_hi_2');

foreach($active_users as $a => $value) {
?>
<fb:name useyou='false' uid='<?php echo $value; ?>' ifcantsee='anon' /> <?php echo $value; ?> (<?php echo get_level($value); ?>) (<?php echo 
$memcache->get($user . 'pvp'); ?>)<br>
<?
}

echo "</td><td valign='top'>";
echo "<br><br><h2>over level 5000</h2>";
$active_users = $memcache->get('active_hi_3');

foreach($active_users as $a => $value) {
?>
<fb:name useyou='false' uid='<?php echo $value; ?>' ifcantsee='anon' /> <?php echo $value; ?> (<?php echo get_level($value); ?>) (<?php echo 
$memcache->get($user . 'pvp'); ?>)<br>
<?
}


echo "</td></tr></table>";

?>


</center>

