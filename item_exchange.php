<?php
//error_reporting(E_ALL);

require_once 'includes.php';

redirect_if_action($user);

$was_bombed = get_was_bombed($user);
if($was_bombed != "" && $was_bombed != 0 ) {
	$facebook->redirect("you_were_bombed.php");
}
$was_attacked = get_was_attacked($user);
if($was_attacked != "" && $was_attacked != 0) {
      $facebook->redirect("you_were_attacked.php");
}

print dashboard();

$action = $_REQUEST['action'];
$item = $_REQUEST['item'];
$num = $_REQUEST['num'];

$item_count = 30;  //if we have more items than this, increase this #

if(isset($_POST['buy'])) {
	if(isset($_POST['id'])) {
		$uid = $_POST['id'];
		$success = complete_buy_action($user, $uid);
		if($success == "no coins") {
			echo_error("Not enough coins to purchase that item.");
		}
		else if($success == "fail") {
			echo_error("The item doesn't exist. It was probably purchased by someone else!");
		}
		else {
			echo_success("Item successfully purchased!");
		}
		$action = 'buy';
	}
}

if(isset($_POST['sell'])) {	
	if(isset($_POST["u"])) {
		if(isset($_POST["price"])) {
			$price = $_POST["price"];
			if($price < 1) {
				$price = 1;
			}
			if($price > 1000) {
				$price = 1000;
			}

			$item = $_POST["u"];
			$action = 'sale_confirmation';
		}
	}
}

if(isset($_POST['sale_declined'])) {
	$action = 'sell';
}

if(isset($_POST['update_declined'])) {
	$action = 'manage';
}

if(isset($_POST['sale_confirmation'])) {
	if(isset($_POST['u'])) {
		if(isset($_POST['price'])) {
			$price = $_POST['price'];
			$item = $_POST['u'];
			if($price < 1) {
				$price = 1;
			}
			if($price > 1000) {
				$price = 1000;
			}
			$price_to_pay = ceil($price * .05);
			if(get_is_item_owned($user, $item)) {
				$coin_total = get_coin_total($user);
				if($price_to_pay > $coin_total) {
					echo_error("Not enough coins to list that item.");
				}
				else {
					$new_coin_total = $coin_total - $price_to_pay;
					if($new_coin_total < 0) {
						$new_coin_total = 0;
					}
					set_coins($user, $new_coin_total);
					log_coins($user, -$price_to_pay, 'item exchange listing fee');
					sell_item($user, $item, $price);
					echo_success("Your item is posted!<br>It will appear for sale shortly.");
				}	
			}
			else {
				echo_error("The item doesn't exist. It was probably purchased by someone else!");
			}
			$action = 'sell';
		}
	}
}

if(isset($_POST['manage_update'])) {
	if(isset($_POST['u'])) {
		if(isset($_POST['price'])) {
			$price = $_POST['price'];
			if($price < 1) {
				$price = 1;
			}
			$uid = $_POST['u'];
			$item = $_POST['id'];
			$action = 'update_confirmation';
		}
	}
}

if(isset($_POST['manage_delete'])) {
	if(isset($_POST['u'])) {
		$uid = $_POST['u'];
		if(get_is_owned($user, $uid)) {
			$success = delete_sell_item($user, $uid, true);
			if($success == false) {
				echo_error("The item doesn't exist. It was probably purchased by someone else!");
			}
			else {
				echo_success("Item sale cancelled!");
			}
		}
		else {
			echo_error("The item doesn't exist. It was probably purchased by someone else!");
		}
	}
}

if(isset($_POST['update_confirmation'])) {
	if(isset($_POST['u'])) {
		if(isset($_POST['price'])) {
			$price = $_POST['price'];
			$uid = $_POST['id'];
			if($price < 1) {
				$price = 1;
			}
			if(get_is_owned($user, $uid)) {
				$price_to_pay = ceil($price * .05);
				$coin_total = get_coin_total($user);
				if($price_to_pay > $coin_total) {
					echo_error("Not enough coins to update the listing!");
				}
				else {
					$new_coin_total = $coin_total - $price_to_pay;
					if($new_coin_total < 0) {
						$new_coin_total = 0;
					}
					log_coins($user, -$price_to_pay, 'item exchange listing fee');
					set_coins($user, $new_coin_total);
					//check to see if it's in indexing string, if not- do not allow them to update! (hack)
					$stuff_id = get_stuff_id_from_uid($user, $uid);
					if($stuff_id != false) {
						$success = delete_sell_item($user, $uid, true);
						if($success == true) {
						
							//print "user has item: ";
							//print user_has_item($user, $stuff_id);

							$sale_id = sell_item($user, $stuff_id, $price);
							update_memcache_sell_item_indexing($user, $sale_id, false, $price, false);
							$success = update_db_and_memcache_buying($user, $sale_id);
							if($success == false) {
								echo_error("The item doesn't exist. It was probably purchased by someone else!");
							}
							else {
								echo_success("Your sale has been updated!<br>It will appear for sale shortly.");
							}
						}
						else {
							echo_error("The item doesn't exist. It was probably purchased by someone else!");
						}
					}
					else {
						echo_error("The item doesn't exist. It was probably purchased by someone else!");
					}
				}
			}
			else {
				echo_error("The item doesn't exist. It was probably purchased by someone else!");
			}
			$action = 'manage';
		}
	}
}

$team = get_team($user);

$pirates_selling = 932;
$total_items = 3943;
?>

<div style="padding: 15px;">
	<center><h1>Welcome to the Trading Post!</h1>
	<h4>You have <?php echo number_format(get_coin_total($user)); ?> coins<br><a href="<?php echo $facebook_canvas_url; 
?>/retrieve_coins.php"><?php echo 
number_format(get_coin_total_buried($user)); ?> coins buried</a></h4></center>
</div>
<fb:tabs>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy" title="Buy" <?php if($action == "buy" or $action == "") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=sell" title="Sell" <?php if($action == "sell") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=manage" title="My Sales" <?php if($action == "manage") { echo 'selected="true"'; } ?>/>
	<fb:tab_item href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=discussion" title="Discussion" align="right" <?php if($action == "discussion") { echo 'selected="true"'; } ?>/>
</fb:tabs>

<div style="padding: 10px; background-color: #f7f7f7;">
	<?php if($action == "buy" or $action == "") { ?>
		<table cellpadding="0" cellspacing="0" border="0">
			<tr>
				<td width="150" valign="top" rowspan="2" style="padding: 0px 10px 0px 0px;">
					<h3 style="padding: 0px 0px 5px 0px;">Items for sale:</h3>
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
					<ul style="margin: 0pt; padding-left: 5px; list-style-type: none;">
						<li <?php if(!isset($item) or $item == 6) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=6" <?php if(!isset($item) or $item == 6) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Bomb</a></li>					
						<li <?php if($item == 4) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=4" <?php if($item == 4) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Dynamite</a></li>
						<li <?php if($item == 2) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=2" <?php if($item == 2) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Gold Bars</a></li>
						<li <?php if($item == 3) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=3" <?php if($item == 3) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Message in a Bottle</a></li>
						<li <?php if($item == 7) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=7" <?php if($item == 7) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Monkey</a></li>
						<li <?php if($item == 11) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=11" <?php if($item == 11) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Parrot</a></li>
						<li <?php if($item == 5) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=5" <?php if($item == 5) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Pirate Flag</a></li>
						<li <?php if($item == 8) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=8" <?php if($item == 8) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Pistol</a></li>
						<li <?php if($item == 9) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=9" <?php if($item == 9) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Rum</a></li>
						<li <?php if($item == 12) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=12" <?php if($item == 12) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Salted Ham</a></li>
						<li <?php if($item == 13) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=13" <?php if($item == 13) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Sextant</a></li>																																															
						<li <?php if($item == 10) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=10" <?php if($item == 10) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Sword</a></li>
						<li <?php if($item == 1) { echo 'style="background-color: #3B5998; padding: 3px 3px 3px 3px;"'; } ?>><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=1" <?php if($item == 1) { echo 'style="color: #FFFFFF; font-weight: bold;"'; } ?>>Treasure Map</a></li>
					</ul>
					<br>
					<?php echo adsense_125_125($user); ?>
					</div>
				</td>
				<td width="460" valign="top" height="100%" style="padding: 0px 0px 3px 0px;">
					<table cellpadding="0" cellspacing="0" border="0">
						<tr>
							<td width="155"><h3 style="padding: 0px 0px 5px 0px;">&nbsp;Seller</h3></td>
							<td width="150"><h3 style="padding: 0px 0px 5px 0px;">Time Left</h3></td>
							<td width="90"><h3 style="padding: 0px 0px 5px 0px;">Sale Price</h3></td>
						</tr>
					</table>
					<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
					<table cellpadding="0" cellspacing="0">
					
						<?php
							if(!isset($item)) {
								$item = 6;
							}
							$buy_indexing_string = get_buy_indexing_string($item);
							
							if($buy_indexing_string != false) {
							
								$buy_array = explode(",", $buy_indexing_string);
								
								$number_to_show = 15;
								if(!isset($num)) {
									$num = 0;
								}
								$beginning = $num * $number_to_show;
								$end = ($num + 1) * $number_to_show;
								
								if($end > count($buy_array)) {
									$end = count($buy_array);	
								}
								
								for($i = $beginning; $i < $end; $i++) {
									$price = get_from_memcache("b:" . $buy_array[$i] . ":" . $item . ":p");
									$created_at = get_from_memcache("b:" . $buy_array[$i] . ":" . $item . ":ca");
									$sell_user = get_from_memcache("b:" . $buy_array[$i] . ":" . $item . ":u");
									
									//only display if we can find all the stuff
									if($price != false and $created_at != false and $sell_user != false) {
										$team_name = ucwords(get_team($sell_user));
										echo "
											<tr>
												<td width=\"150\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\"><fb:name uid=\"$sell_user\" firstnameonly=\"false\" shownetwork=\"false\" ifcantsee=\"A $team_name Pirate\"useyou=\"false\" linked=\"true\"/></td>
												<td width=\"150\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\">";
										echo time_left_of_24hours($created_at);
										echo "</td>
												<td width=\"90\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\">$price gold</td>
												<td width=\"60\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\"><form action=\"$facebook_canvas_url/item_exchange.php?action=buy&item=$item\" method=\"post\"><input type=\"hidden\" name=\"id\" value=\"$buy_array[$i]\"><input type=\"Submit\" class='inputsubmit' value=\"Buy\" name=\"buy\"></form></td>
											</tr>
										";
									}
								}
							}
							else {
								echo "<center>There are currently no items for sale.<center>";
							}
						?>
					</table>
					</div>
				</td>
			</tr>
			<tr>
				<td valign="top" style="text-align:right;">
					<?php if($num > 0) { ?><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=<?php echo $item; ?>&num=<?php echo $num - 1; ?>"><- Previous</a><?php } ?>
					<?php if($num > 0 and count($buy_array) > $end) { echo " | "; } ?>
					<?php if(count($buy_array) > $end) { ?><a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=buy&item=<?php echo $item; ?>&num=<?php echo $num + 1; ?>">Next -></a><?php } ?>
				</td>
			</tr>
		</table>
	<?php } else if($action == "sell") { ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="150"><h3 style="padding: 0px 0px 5px 0px;">&nbsp;&nbsp;Item</h3></td>
				<td width="108"><h3 style="padding: 0px 0px 5px 0px;">Sell Qty</h3></td>
				<td width="140"><h3 style="padding: 0px 0px 5px 0px;">Lowest Price</h3></td>
				<td width="90"><h3 style="padding: 0px 0px 5px 0px;">Selling Price</h3></td>
			</tr>
		</table>
		<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
		<table>
			<?php 
				$booty = get_booty($user); 
			
				foreach($booty as $key=>$value) {
					$stuff_id = $value['stuff_id'];	
					$booty_data = get_booty_data_from_id($stuff_id);	

					$count = $value['how_many'];
					if($count != 0) {
					echo "
						<tr>
							<td width=\"140\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\">$booty_data[0] x$count</td>
							<td width=\"105\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\">x1</td>
							<td width=\"140\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\">";
					$low_price = lowest_buy_price($user, $stuff_id);
					if($low_price == 0 or $low_price == false) {
						echo "Unknown";
						$low_price = 50;
					} else {
						echo $low_price . " gold";
					}
					echo	"</td><td width=\"200\" style=\"padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;\"><form action=\"$facebook_canvas_url/item_exchange.php\" method=\"post\">
							<input type=\"hidden\" name=\"u\" value=\"$stuff_id\"><span style='float:left'><input type=\"text\" name=\"price\" size=\"5\" value=\"$low_price\"> gold</span><input type=\"Submit\" class='inputsubmit' style='float:right' value=\"sell\" name=\"sell\"></form></td>
							
						</tr>
						";
					}
				}			
			
			?>
		</table>
		</div>
	<?php } else if($action == "sale_confirmation" or $action == "update_confirmation") { ?>
	
		<?php 
			$booty_data = get_booty_data_from_id($item);
		?>
		<center>
		<h3><?php if($action == "sale_confirmation") { echo "Sale"; } else { echo "Update"; } ?> Confirmation</h3><br>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="150"><h3 style="padding: 0px 0px 5px 0px;">Item</h3></td>
				<td width="108"><h3 style="padding: 0px 0px 5px 0px;">Sell Qty</h3></td>
				<td width="140"><h3 style="padding: 0px 0px 5px 0px;">Selling Price</h3></td>
				<td width="130"><h3 style="padding: 0px 0px 5px 0px;">Posting Fee 5%</h3></td>
			</tr>
		</table>
		<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
		<table>
			<tr>
					<td width="150" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><?php echo $booty_data[0]; ?></td>
					<td width="108" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;">x1</td>
					<td width="140" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><?php echo $price; ?> gold</td>
					<td width="130" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><b><?php echo ceil($price * .05); ?> gold</b></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
					<td colspan="4" style="padding: 0px 0px 5px 0px;"><center><b>You have: <?php echo get_coin_total($user); ?> coins<br><?php echo get_coin_total_buried($user); ?> coins buried</b></center></td>
			</tr>
			<tr><td>&nbsp;</td></tr>
			<tr>
				<?php if(get_coin_total($user) >= ceil($price * .05)) { ?>
					<td colspan="4" style="padding: 0px 0px 5px 0px;">
						<form action="<?php echo "$facebook_canvas_url/item_exchange.php" ?>" method="post">
							<input type="hidden" name="u" value="<?php echo $item; ?>">
							<input type="hidden" name="price" value="<?php echo $price; ?>">
							<input type="hidden" name="id" value="<?php echo $uid; ?>"><h3>Pay the listing fee of <?php echo ceil($price * .05); ?> gold?&nbsp;&nbsp;&nbsp;<input type="Submit" value="Decline" name="<?php if($action == "sale_confirmation") { echo "sale_declined"; } else { echo "update_declined"; } ?>">&nbsp;&nbsp;&nbsp;
							<input type="Submit" value="Approve" name="<?php if($action == "sale_confirmation") { echo "sale_confirmation"; } else { echo "update_confirmation"; } ?>">
						</form>
					</td>
				<?php } else { ?>
					<td colspan="4" style="padding: 0px 0px 5px 0px;"><center><h3>You do not have enough coins to pay the posting fee.<br><a href="<?php echo $facebook_canvas_url; ?>/retrieve_coins.php">Dig up some coins</a> or <a href="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=sell">Cancel</a></h3></center></td>
				<?php } ?>
			</tr>
			
		</table>
	
		</div>
		</center>
	<?php } else if($action == "manage") { ?>
		<table cellpadding="0" cellspacing="0">
			<tr>
				<td width="150"><h3 style="padding: 0px 0px 5px 0px;">&nbsp;&nbsp;Item</h3></td>
				<td width="170"><h3 style="padding: 0px 0px 5px 0px;">Time Left</h3></td>
				<td width="140"><h3 style="padding: 0px 0px 5px 0px;">Price</h3></td>
			</tr>
		</table>
		<div style="background-color: #FFFFFF; border: 1px solid grey; padding: 5px 5px 5px 5px;">
			<?php
				$item_array[0] = "";
				$use_item_array = false;
				$indexing_string = get_sell_item_indexing_string($user);
//				echo "index string: " . $indexing_string . "<br>";
				if($indexing_string == false) {
					$use_item_array = true;
					$return_array = create_memcache_sell_items_from_db($user);
					$indexing_string = $return_array[1];
//					echo "index string rebuild: " . $indexing_string . "<br>";
					$item_array = $return_array[0];
//					echo "count rebuild: " . count($item_array) . "<br>";
				}
				
				if($indexing_string != "") {
					$indexing_string_array = explode(",", $indexing_string);
				}
				else {
					echo "<br><center>You currently have no active sales.</center><br>";
				}
				//echo "index string array count: " . count($indexing_string_array) . "<br>";
				//print_r($indexing_string_array);
				
				for($i = 0; $i < count($indexing_string_array); $i++) {
					if($use_item_array == false) {
						$stuff_id = get_sell_item_result($user, ":silsi", $indexing_string_array[$i], $use_item_array, $item_array);
					}
					if($stuff_id == false or $use_item_array == true) {
						if($use_item_array == false) {
							$return_array = create_memcache_sell_items_from_db($user);
							$item_array = $return_array[0];
						}
						$use_item_array = true;
						$stuff_id = get_sell_item_result($user, "stuff_id", $i, $use_item_array, $item_array);
					}
					if($use_item_array == false) {
						$price = get_sell_item_result($user, ":silp", $indexing_string_array[$i], $use_item_array, $item_array);
					}
					if($price == false or $use_item_array == true) {
						if($use_item_array == false) {
							$return_array = create_memcache_sell_items_from_db($user);
							$item_array = $return_array[0];
						}
						$use_item_array = true;
						$price = get_sell_item_result($user, "price", $i, $use_item_array, $item_array);					
					}
					if($use_item_array == false) {
						$created_at = get_sell_item_result($user, ":silca", $indexing_string_array[$i], $use_item_array, $item_array);
					}
					if($created_at == false or $use_item_array == true) {
						if($use_item_array == false) {
							$return_array = create_memcache_sell_items_from_db($user);
							$item_array = $return_array[0];
						}
						$use_item_array = true;
						$created_at = get_sell_item_result($user, "created_at", $i, $use_item_array, $item_array);					
					}
					
					
				?>
				
											<form action="<?php echo "$facebook_canvas_url/item_exchange.php?action=manage"; ?>" method="post">

								<table>

					<tr>
							
							<td width="140" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;">
							<input type="hidden" name="id" value="<?php echo $stuff_id; ?>">
							<input type="hidden" name="u" value="<?php echo $indexing_string_array[$i]; ?>">

							
							<?php $result = get_booty_data_from_id($stuff_id); echo $result[0]; ?></td>
							<td width="170" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><?php echo time_left_of_24hours($created_at); ?></td>
							<td width="140" style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><input type="text" name="price" size="5" value="<?php echo $price; ?>"> gold</td>
							<td style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><input type="Submit" class='inputsubmit' value="Cancel" name="manage_delete">&nbsp;</td>
							<td style="padding: 0px 0px 5px 0px; border-bottom: 1px dotted #CCC;"><input type="Submit" class='inputsubmit' value="Update" name="manage_update"></td>
					</tr>
		</table></form>	
			<?php } ?>
															

		
		</div>
	<?php } else if($action == "discussion") { ?>
		<?php
			$moderators = get_moderators();
			$banned = get_banned();

			if (in_array($user, $moderators )) {
				$candelete = 'true';
			}
			else {
				$candelete = 'false';
			}

			if (in_array($user, $banned )) {
				$canpost = 'false';
			}
			else {
				$canpost = 'true';
			}
		?>

		<fb:comments showform="true" xid="pirates_trading_wall" canpost="<?php echo $canpost; ?>" candelete="<?php echo $candelete; ?>" returnurl="<?php echo $facebook_canvas_url; ?>/item_exchange.php?action=discussion">
   			<fb:title>Trading Post Discussion Board</fb:title>
 		</fb:comments>
	<?php } ?>
</div>

<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px"><a href="harbor.php">Go to the  harbor</a></h3>

<h3 style="text-align:center; padding-top: 5px; padding-bottom: 10px"><a href="index.php">Go back to sea</a>  -adventure, danger and treasure await</h3>


<?php 
	//print user_has_item(1807687, 1);
	
	print adsense_468($user);
	require_once 'footer_nolinks.php';
?>
</div>
