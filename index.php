
<?php


require_once 'includes.php';

$in = $_REQUEST['i'];
if(isset($in)) {

	if( $network_id == 0 ) {
		$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
	}
	else if( $network_id == 1 ) {
		$facebook->redirect("http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=100506&appParams=$in");	
	}
}


global $DB, $network_id;

redirect_if_action($user);
    $action = get_current_action($user);
    //echo $action;
$was_bombed=get_was_bombed($user);
if($was_bombed != "" && $was_bombed != 0 ) {
      if($network_id == 0) {
      	$facebook->redirect('you_were_bombed.php');
      }
      else {
      	require_once "you_were_bombed.php";
      	exit();
      }
}
$was_attacked = get_was_attacked($user);
if($was_attacked != "" && $was_attacked != 0) {
      if($network_id == 0) {
      	$facebook->redirect('you_were_attacked.php');
      }
      else {
      	require_once "you_were_attacked.php";
      	exit();
      }
}

//echo "action $action";
//$secondary_action = get_secondary_action($user);
//echo "secondary action $secondary_action";

//print $memcache->get($user . 'merchant_ship_limit');

//set_level($user, 1200);

$type = get_team($user);

//do not allow bad weather effects
$ship_image_blue = get_ship_image_and_weather($type, $user, true);


//print $user;

if($_REQUEST['sent'] == "1") {
	$msg = explanation_msg_return('Yer Pirate invitations have been sent!'); 
}

if($_REQUEST['msg'] == "pillage-invite-crew") {
	$msg = explanation_msg_return('Yer Pirate invitations have been sent and you now have a new crew member!'); 
}

if($_REQUEST['msg'] == "send-limit") {
	$msg = error_msg_return("Your requests were not sent<br>You're over the limit for today.  Try again later. :(");
}

if($_REQUEST['msg'] == "try-again-buy") {
	$msg = error_msg_return("Yer crew are repairing yer ship so you can't buy anything right now!<br>Arrrr...Try again in a couple seconds.");
}

if($_REQUEST['msg'] == "cant-bomb-user-over-limit") {
	$msg = error_msg_return("Yer crew refuses to bomb this pirate!<br>Arrr.... try again in a few days.");
}
if($_REQUEST['msg'] == "sinking-ship") {
	$amount = $_REQUEST['amount'];
	$booty = $_REQUEST['booty'];
	
	if($booty == 'cannons') {
		$current_upgrade_level = get_cannons($user);
		if($amount == 1) {
			$booty_first = 'cannon';
		}
		else {
			$booty_first = $booty;
		}
	}
	else if($booty == 'crew') {
		$current_upgrade_level = get_crew_count($user);
		$booty_first = $booty;
		
	}
	else if($booty == 'sails') {
		$current_upgrade_level = get_sails($user);
		if($amount == 1) {
			$booty_first = 'sail';
		}
		else {
			$booty_first = $booty;
		}
	}
	
	
	if($booty == 'crew') {
		$msg = success_msg_return("Ahoy! You found a sinking pirate ship!<br>Yer crew were able to pillage $amount $booty_first for yer ship!  You now have $current_upgrade_level $booty");	
	}
	else {
		$msg = success_msg_return("Ahoy! You found a sinking pirate ship!<br>Yer crew were able to pillage $amount $booty_first for yer ship!  You now have level $current_upgrade_level $booty");
	}
}
if($_REQUEST['msg'] == "bottle") {
    $bottle_count = get_bottle_count($user);
	$msg = success_msg_return("Arrr... You found a message in a bottle floating in the sea!<br>You now have $bottle_count bottles!");
}
if($_REQUEST['msg'] == "trade-declined") {
    $bottle_count = get_bottle_count($user);
	$msg = success_msg_return("You declined the trade offered by the merchant ship captain. Arrr...");
}
if($_REQUEST['msg'] == "trade-accepted") {
    $bottle_count = get_bottle_count($user);
	$msg = explanation_msg_return("You accepted the merchant's trade offer and your got your new booty!");
}
if($_REQUEST['msg'] == "parrot") {
    $parrot_count = get_parrot_count($user);
    $parrot_arr = 'parrot';
    if($parrot_count > 1) {
        $parrot_arr = 'parrots';
    }
	$msg = explanation_msg_return("Arrr... You found a parrot at sea!<br>You now have $parrot_count $parrot_arr!<br>Parrots can be raced for gold at the tavern!");
}
if($_REQUEST['msg'] == "treasure-start") {
    $miles = $memcache->get($user . 'treasure_hunt_miles');
    if($miles == FALSE) { // 
      $action = get_current_action($user);
      redirect_to_index_if_not($user, "treasure_hunt");
      $miles = rand(10,15);
	  $memcache->set($user . 'treasure_hunt_miles', $miles);	       
    }
    else {
      $action = get_current_action($user);
      redirect_to_index_if_not($user, "treasure_hunt");
      //$miles = rand(10,50);
      $memcache->set($user . 'treasure_hunt_miles', $miles);
    }
    
    if($miles == 1) {
    	$miles_text = 'mile';
    }
    else {
    	$miles_text = 'miles';
    }
    $msg = explanation_msg_return("You've started on a quest for treasure!<br>You have $miles more mile to go before getting the treasure!");
    
}
else if($action == 'treasure_hunt') {
    $action = get_current_action($user);
    redirect_to_index_if_not($user, "treasure_hunt");
    $miles = $memcache->get($user . 'treasure_hunt_miles');
    $memcache->set($user . 'treasure_hunt_miles', $miles - 1);
    $nmiles = $miles - 1;
    if($nmiles < 0) {
        $nmiles = 0;
    }
    $memcache->set($user . 'treasure_hunt_miles', $nmiles);
    
    //increment miles
    increment_miles_traveled($user);
    
	$msg = explanation_msg_return("You're on a quest for treasure!<br>You have $nmiles more miles to go before getting the treasure!");
}

if($_REQUEST['msg'] == "send-limit-bombs") {
	$msg = explanation_msg_return("Your bombs were sent!<br>However, you're over the Facebook limit for today.  Any future bombs will not be sent.  Try again tommorow. :(</div>");
}

if($_REQUEST['msg'] == "send-limit-dynamite") {
	$msg = explanation_msg_return("Your dynamite was sent!<br>However, you're over the Facebook limit for today.  Any future dynamite will not be sent.  Try again tommorow. :(");
}

if($_REQUEST['msg'] == "ship-ran-away") {
	$msg = explanation_msg_return("The ship you were chasing sailed away before you could attack!<br>Arrrr...");
}

if($_REQUEST['msg'] == "joke-added") {
	$msg = explanation_msg_return("Yer Pirate Joke was submitted!<br>If it's funny we'll credit you 200 coins!");
}

if($_REQUEST['msg'] == "settings-saved") {
	$msg = explanation_msg_return("Yer settings were saved!");
}

if($_REQUEST['pvp'] == "on") {
	$msg = explanation_msg_return("You are exploring enemy waters!<br>If you are attacked yer crew will try to defend themselves");
}

if($_REQUEST['pvp'] == "off") {
	$msg = explanation_msg_return("You sail into safe waters!<br>You won't be attacked or find any enemy pirate ships or towns.<br>You can still be bombed by yer friends");
}

if($_REQUEST['msg'] == "tshirt-ok") {
	$msg = explanation_msg_return("Avast! Your T Shirt should arrive in a few weeks!  Thanks for playing!!!<br>Arrrr...");
}

if($_REQUEST['msg'] == "gambling-addiction") {
	$msg = explanation_msg_return("A tavern thug throws you out of the game room!  Better be comin back later!<br>Arrrr...");
}


//http://www.facebook.com/profile.php?id=1807687
if($_REQUEST['msg'] == "threw-bomb") {
	$to = $_REQUEST['to'];
	$gold = $_REQUEST['gold'];
	$level_up = $_REQUEST['level_up'];
	//if($level_up ==1) {
   	 	$msg = "<div style='text-align: center;'><div style='margin-top: 20px; margin-left: 30px; float: left;'>";
   	 	$msg = $msg . image_return($bomb_100);

	   	$msg = $msg . "</div>
   	 		<div style='margin-top:20px; margin-right:30px; float:right'>";
   	 	$msg = $msg . image_return($bomb_100);
		if($network_id == 0) {
			$name = "<fb:name uid='$to' firstnameonly='true'/>";
		}
		else {
			//$name = get_name_for_id($to);
			$name = $_REQUEST['enemy_name'];
   	 	}
		$msg = $msg . "	</div> ";
   	 		//<fb:explanation>
			//	<fb:message>
		$msg =  $msg . "<div class='standard_message has_padding'><h1 class='explanation_note'>";
		$msg = $msg . "<div style='padding-top:25px'>You threw a bomb at $name and stole $gold coins!</div>";
		if($level_up == true) {			
			$msg .= "<div style='text-align:center'>Level +1<br><br><br></div></div>";
		}
		else {
			$msg .= "<div style='text-align:center'>You didn't level up this time.<br><br><span style='font-size:75%'>after lvl 100 bombs become less effective for leveling.<br>try trading booty for upgrades at the market to increase yer level!</span></div> <div style='clear: both;'/></h1></div></div>";
		}
		
		
		//$msg .= "</fb:message>
		//		<div style='clear:both'></div>
		//	</fb:explanation>";

	//}
}


			
			

else if($_REQUEST['msg'] == "left-dynamite") {
	$to = $_REQUEST['to'];
	$gold = $_REQUEST['gold'];
	$level_up = $_REQUEST['level_up'];
	if($network_id == 0) {
		$name = "<fb:name uid='$to' firstnameonly='true'/>";
	}
	else {
		$name = get_name_for_id($to);
   	 }
   	 	
   	 $msg = "You launched a dynamite monkey attack against $name and receive $gold coins!</div>";
					
	if($level_up == true) {			
		$msg .= "<div style='text-align:center'>Level +1</div>";
	}
	else {
		$msg .= "<div style='text-align:center'>You didn't level up this time... maybe next time.</div>";
	}
	
	$msg = explanation_msg_return($msg);

}


else if($_REQUEST['msg'] == "left-dynamite-for-pirate") {
	$to = $_REQUEST['to'];
	$gold = $_REQUEST['gold'];
	$level_up = $_REQUEST['level_up'];
	if($network_id == 0) {
		$name = "<fb:name uid='$to' firstnameonly='true'/>";
	}
	else {
		$name = get_name_for_id($to);
   	 }
   	 
   	 if($name == '' || !$name) {
   	 	$name = 'your friend';
   	 }
   	 	
   	 $msg = "You left an explosive booby trap for $name <br>When the trap goes off your monkey will steal some of their booty!</div>";
					

	$msg = explanation_msg_return($msg);

}



else if($_REQUEST['msg'] == "drank-rum") {
   	 	$msg = explanation_msg_return("You drank some Rum!<br>Your crew is ready to hit the seas.");
}
else if($_REQUEST['msg'] == "eat-ham") {
	$msg = explanation_msg_return("You ate some Salted Ham!<br>Your crew is healed and ready to hit the seas.");
}
else if($_REQUEST['msg'] == "hack") {
	$msg = error_msg_return("1337 H4x0r!<br>Arr... this is pirates, hackers beware!");
}



$in = $_REQUEST['i'];
if(isset($in)) {

	if( $network_id == 0 ) {
		$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
	}
	else if( $network_id == 1 ) {
		$facebook->redirect("http://profile.myspace.com/Modules/Applications/Pages/Canvas.aspx?appId=100506&appParams=$in");	
	}
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	//if($_REQUEST['opensocial_viewer_id'] == -1) {
		//check if there is a p referer
		$p = $_REQUEST['p'];
		if(isset($p) && $p != '') {
				//set the referer cookie
			
			$cookietime = time() + (3600 * 24 * 30);
			
			header("p3p: CP=\"ALL DSP COR PSAa PSDa OUR NOR ONL UNI COM NAV\"");
	  		if($p != -1) {
	  			setcookie('pirates_ref', $p, $cookietime, "/");
	  		}
	  


				$recruiter_name = get_name_for_id($p);
				echo "<p style='font-size:150%; text-align:center'><b>$recruiter_name</b> wants you to play pirates with them! <br><a target='_top' href='http://profile.myspace.com/index.cfm?fuseaction=user.viewprofile&friendid=329304714'>Click here to Play</a>!</p>";
			exit();	
		}
		else {
			//echo "<p style='font-size:150%; text-align:center'><a href='http://profile.myspace.com/index.cfm?fuseaction=user.viewprofile&friendid=329304714' target='_top' >Installe Pirates to Play!</a></p>";
			//exit();
		require_once 'install.php';
		exit();
		}
	//}
	//else {

	//	require_once 'install.php';
	//	exit();
	//}
}


print dashboard();


if($msg) {
	echo $msg;
}
?>

	
	
	
	
<?php

$team = $type;
$coins = get_coin_total($user);
$buried_coins = get_coin_total_buried($user);
$health=get_health($user);
$my_level=get_level($user);
//$real_health = $my_level - $health;
?>

<center>
<h1>Ahoy, Matey!</h1>
</center>

<h2 style="text-align:center;">where do ye want to sail today? </h2>
<br>

<center>
<table  width="90%" cellspacing="0" cellpadding="3">
	<tr>
		<td width='30%'><h5 style="text-align:left">Level <?php echo $my_level; echo " " . ucwords($team); ?> Pirate</h5></td>
		<td width='30%'><h5 style="text-align:center">Hit Points: <?php echo $health; ?></h5></td>
		<td width='30%'><h5 style="text-align:right">Coins: <?php echo number_format($coins); ?> Buried: <?php echo number_format($buried_coins); ?></h5></td>
	</tr>
</table>
</center>
<center>

<table style="background-color: #3B5998; border: 1px solid black; text-align: center; color: #FFFFFF; margin-top:0px; padding:10px;" width="90%" border="0">
<tr>
<td width='30%'><center>
<?php
	$userMiles = get_miles_traveled($user);
?>
	<div id='harborcontainer'  style='width:180px'>
	<a id='harborlink' style="color:#FFFFFF;" <?php href('harbor.php'); ?> >
	<span style="font-size:125%;">&lt;-- <?php echo $type; ?> harbor </span>
	<?php 
		echo "<br>$userMiles miles away<br>";
	?>
	(get ship upgrades!)

	</a>
	</div>
	</center>
	</td>

	<td style="text-align:center;" width='30%'>
		<center>
			<?php image($ship_image_blue); ?>
		</center>
	</td>
	<td width='30%'>
	<center>
	<?php
	if($action == 'treasure_hunt') {
	
	
	   $treasure_attack = $memcache->get($user . 'treasure_attack_mile');
	   //echo "treasure $treasure_attack";
	   if($treasure_attack == 1) {
	   	//echo "AT"
	   		update_secondary_action($user, 'attacked_by_monster');
	   		$facebook->redirect('attacked_by_monster.php');
	   }
	   else if ($treasure_attack > 1) {
	   		$treasure_attack--;
	   		$memcache->set($user . 'treasure_attack_mile', $treasure_attack);
	   }
	   
	   $miles = $memcache->get($user . 'treasure_hunt_miles');
	   //print $miles;
	   
	   if($miles < 1) {
	   update_action($user, 'treasure_booty');
	   
?>

	   	<a style="color:#FFFFFF;" <?php href('treasure_booty.php'); ?> >
	<span style="font-size:125%;">get the BOOTY--></span><br>
	(ARRR!)</a>


<?php	   
	   }
	   else {
	   ?>
	   
	   	<?php
	   	
	   	$r = rand(0,2);
	   	if($r == 0) {
	   		$m = 'search for treasure';
	   	}
	   	else if($r == 1) {
	   		$m = 'follow the map';
	   	}
	   	else {
	   		$m = 'keep searching';
	   	}
	   ?>
	   	   	<a style="color:#FFFFFF;" <?php href('index.php'); ?> >
	<span style="font-size:125%;"><?php echo $m; ?> --></span><br>
	(ARRR!)</a>


	   <?php
	   }
	   
	   ?>
	   


<?php

	}
	else {
?>
	
	<div id = 'explorecontainer' style='width:180px'>
	<a id='explorelink' style="color:#FFFFFF;" <?php href('explore.php'); ?> >
	
	<span style="font-size:125%;">explore the open sea --></span>
	
	<br>
	(adventure, treasure, danger)</a>
	</div>

<?	
	}

?>

</center>
</td>
</tr>
</table>


<table width="90%" cellspacing="0" cellpadding="3">
<tr>
<?php
global $DB;

$pvp_toggle = $_REQUEST['pvp'];
//print "aaa $pvp_toggle bbb";

if(!empty($pvp_toggle)) {
    if($pvp_toggle == 'off' && $action != 'treasure_hunt') {
          $memcache->set($user .'pvp', $pvp_toggle);
          $DB->Execute('update users set pvp_off = 1 where id = ?', array($user));
    }
    else if($pvp_toggle == 'on') {
          //echo 'setting to on';
          $memcache->set($user .'pvp', $pvp_toggle);        
          $DB->Execute('update users set pvp_off = 0 where id = ?', array($user));
    }
  }
?>
<td style="text-align:center;" colspan='2'>

<h5><?php echo get_pirate_tip(); ?></h5>
</td></tr>
</table>
</center>
<br>



<?php
if($action == 'treasure_hunt') {
  require_once 'bottom_links_no_pvp.php';  
}
else {
  require_once 'bottom_links.php';
}

//echo $userMiles;
?>
<?php

//$DB->Execute('update users set updated_at = now() where id = ?', array($user));


//FOR FIGHTING SELECTION - 
//put users in a pool if they qualify to be attacked

//echo new_ship_battle($user);
$was_attacked_2 = $memcache->get($user . 'attacked');
//print "was attacked $was_attacked";
//if attacked remove from the 

//print "pvp toggle $pvp_toggle";

//if user has coins onboard and is above level 10 put them in memcache pool
if($my_level > 50 && $health > 1 && $coins > 1 && $was_attacked_2 == false && $pvp_toggle != 'off') {
//echo 'ok';
//print_r($active_users);
//$new_active_users[] += $user;
//echo $my_level;
if($my_level > 5000) {
	$active_users = $memcache->get('active_hi_3');
	if($active_users == false) {
		$active_users = array();
	}
	//print_r($active_users);
	if(!in_array($user, $active_users)) {
		//echo 'hello';
		array_push($active_users, $user);
		$memcache->set('active_hi_3', $active_users);
	}
}
else if($my_level > 1000) {
	$active_users = $memcache->get('active_hi_2');
	if($active_users == false) {
		$active_users = array();
	}
	//print_r($active_users);
	if(!in_array($user, $active_users)) {
		//echo 'hello';
		array_push($active_users, $user);
		$memcache->set('active_hi_2', $active_users);
	}
}
else if($my_level > 100) {
	$active_users = $memcache->get('active_hi');
	if($active_users == false) {
		$active_users = array();
	}
	//print_r($active_users);
	if(!in_array($user, $active_users)) {
		//echo 'hello';
		array_push($active_users, $user);
		$memcache->set('active_hi', $active_users);
	}
}
else {
	$active_users = $memcache->get('active_lo');
	if($active_users == false) {
		$active_users = array();
	}
	if(!in_array($user, $active_users)) {
		//echo 'setting!';
		array_push($active_users, $user);
		$memcache->set('active_lo', $active_users);
	}
	else {
		//echo 'already in';
	}
}



}
else {
//echo 'not ok';
//echo "my level: $my_level";
//echo "my health: $real_health";
//echo "my coins: $coins";
//echo "was attacked: $was_attacked";


}

 //set_profile($user);
try {

 $is_set = $facebook->api_client->data_getUserPreference(1);

}
catch (Exception $e) { } //echo "is set: $is_set";

 $f = get_profile_box($user);
 $facebook->api_client->profile_setFBML(null, $user, $f, null, null, $f);

 // Don't do this again
 $facebook->api_client->data_setUserPreference(1, 'set');
 //}



        
        
 echo "<fb:if-section-not-added section='profile'><div style='overflow: hidden;'><table style='width: 50%;'><tbody><tr valign='middle'><td style='font-size: 9pt;'><strong>Ahoy Matey!</strong> Add Pirates to yer profile!</td><td><fb:add-section-button section='profile' /></td></tr></tbody></table></div></fb:if-section-not-added>";


 require_once 'footer.php';
?>
</center>
<?php
/*
<center><p>Yarr! We be experiencing technical difficulties!  Facebook has disabled one of the developer's profiles where all the pirate images are stored. This is a temporary problem which will be fixed when the profile and images are restored. 4/21/09</p></center>
*/
?>
