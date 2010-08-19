
<?php
require_once 'includes.php';


$joke_on_off = $_REQUEST['joke_on_off'];

$bomb_on_off = $_REQUEST['bomb_on_off'];
$use_old_image = $_REQUEST['use_old_image'];


$gender = $_REQUEST['gender'];

//print_r($_REQUEST);

//echo $joke_on_off;
$sql = 'update users set joke_on = ? where id = ?';
global $DB, $memcache;
$DB->Execute($sql, array($joke_on_off, $user));
//print_r($_REQUEST);

$memcache->set($user . "s_joke_on_off", $joke_on_off, false, 1 * 6);


//echo $joke_on_off;
$sql = 'update users set gender = ? where id = ?';
global $DB, $memcache;
$DB->Execute($sql, array($gender, $user));
//print_r($_REQUEST);

$memcache->set($user . "s_gender", $gender, false, 1 * 6);



//echo $joke_on_off;
$sql = 'update users set use_old_image = ? where id = ?';
global $DB, $memcache;
$DB->Execute($sql, array($use_old_image, $user));
//print_r($_REQUEST);

$memcache->set($user . "s_use_old_image", $use_old_image, false, 1 * 6);



$level = get_level($user);

if($level > 300) {
	$sql = 'update users set bomb_on = ? where id = ?';
	$DB->Execute($sql, array($bomb_on_off, $user));
	$memcache->set($user . "s_bomb_on_off", $joke_on_off, false, 1 * 6);

}

set_profile($user);

$facebook->redirect('index.php?msg=settings-saved');
