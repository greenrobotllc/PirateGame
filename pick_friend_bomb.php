<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

$type= get_team($user);

$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

print dashboard();
?>



<center>
	<h1 style='font-size:200%'>Throw some bombs! (5 left)</h1>
	<fb:photo pid='<?php echo $bomb_100; ?>' uid="<?php echo $image_uid; ?>" />
</center>


<h2 style="text-align:center; font-size:150%">Type a friends name to throw a bomb and steal some gold!</h2><br>

<center>
<form action="throw_action.php">
<fb:friend-selector/>
<br>
<input style="width:80px; height:60px; font-size: 140%; margin-top:20px" class="inputsubmit" type="submit" value="Throw!" name="submit"/>
</form>
<br><br>
</center>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a>  -adventure, danger and treasure await</h3>


<?php require_once 'footer.php'; ?>