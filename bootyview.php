<?php

require_once 'includes.php';

global $DB;

$type= get_team($user);

$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

$id = $_GET['id'];
if(!$id) {
	$facebook->redirect("$facebook_canvas_url/booty.php");
}

print dashboard();

?>

<?php if($msg):?>
<fb:success>
     <fb:message><?php echo $msg; ?></fb:message>
</fb:success>
<?php endif; ?>	

<center>
<h1>Item Details</h1><h3>Aye Captain, learn your booty uses well...</h3>
</center>

<?php 
	$booty_data = get_booty_data_from_id($id); 
	$booty_info = get_booty_info($id);	
?>

<center>
<br>
<table border="0" cellpadding="3">
	<tr>
		<td valign="top">
		<?php image($booty_data[1]); ?>
		</td>
		<td valign="top" width="400"><h1><?php echo $booty_data[0]; ?></h1><br><?php echo $booty_info; ?>
			
			<?php if($id == 9) { //rum ?>
				<br><br><center><h2 style='font-size:200%'><a href="item_action.php?item=rum">Drink Rum</a></h2></center>
			<?php } else if($id == 12) { ?>
				<br><br><center><h2 style='font-size:200%'><a href="item_action.php?item=ham">Eat Ham</a></h2></center>
			<?php } ?>
		</td>
	</tr>
</table>
<br><br>

<?php

print adsense_468($user);

?>

<br><br>
<h3><a href="booty.php">Go Back to your booty</a></h3>
</center>
<br>
<?php require_once 'footer.php'; ?>