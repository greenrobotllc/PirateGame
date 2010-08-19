<?php
error_reporting(E_ERROR | E_PARSE | E_WARNING);

require_once 'includes.php';

$page = urldecode($_REQUEST['page']);
$item = $_REQUEST['item'];
global $user;

global $DB, $base_url, $network_id;

if($network_id == 0) {
$base_url_captcha = 'http://gamesecuritywords.com/pirates/fb';


}
else {
  
	require_once 'style.php';
	$base_url_captcha = 'http://gamesecuritywords.com/pirates/myspace';
	require_once 'captcha_page_myspace.php';
	exit();
	
}
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

//don't let users come to this page without thier action being set correctly
$action = get_current_action($user);
if($action != "captcha") {
	$facebook->redirect("$facebook_canvas_url/index.php");
}

 if($msg): ?>
	<?php success_msg($msg); ?>
<?php endif; ?>	
<br><br>
<center>
<h1>Arrr ye a human pirate?</h1><h3>Let us be making sure!</h3>

<h3 style='padding-top:10px'>There are two words in the picture below.</h3>
<h3>Type them into the box and hit submit to continue</h3>
</center>
<br><br>
<center>
<?php if($network_id == 0) {
?>
<fb:iframe style='border:0px' border ='0px' width='550px' height='300px' src= '<?php echo $base_url_captcha; ?>/captcha_frame.php<?php if($page != "") { echo "?page=$page"; } ?><?php if($item != "") { echo "&item="; echo $item; } ?>' />

<?php
}
else {
?>

<iframe name='captcha_frame' style='border:0px' border ='0px' width='550px' height='300px' src= '<?php echo $base_url_captcha; ?>/captcha_frame.php?user=<?php echo $user; ?><?php if($page != "") { echo "&page=$page"; } ?><?php if($item != "") { echo "&item="; echo $item; } ?>'></iframe>


?>

<?

}
?>	
	<!-- br><h2>Problems? <a href="captcha_page.php?page=<?php echo $page; ?><?php if($item != "") { echo "&item="; echo $item; } ?>">REFRESH PAGE</a></h2><br><br -->

<div style='padding:20px'>
<h3 style='padding:5px; padding-bottom:0px; margin-bottom:0px;'>Can't see the image above?</h3>
<h5 style='padding:5px; padding-top:0px; margin-top:0px;'>Make sure you have no adblock software or 3rd party extensions installed.<br>Javascript should be enabled<br>

<br><h2>Problems? <a href="captcha_page.php?page=<?php echo $page; ?><?php if($item != "") { echo "&item="; echo $item; } ?>">REFRESH PAGE</a></h2>

<br>
<h2><a href="clear_action.php">Nevermind.  Go Back to Sailin'</a></h2><br><br>




<h5 style='padding:5px'>There are no advertisements on this page<br>The image above is used only to stop bots.</h5>

</div>

</center>
<br>
<?php require_once 'footer_nolinks.php'; ?>
