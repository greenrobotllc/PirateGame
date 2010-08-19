<?php

require_once 'includes.php';
global $DB;

$trade_decline = $_REQUEST['trade_decline'];
$trade_accept = $_REQUEST['trade_accept'];

redirect_to_index_if_not($user, "trade_ship_merchant");

if($trade_decline == 'Decline') {
	//clear the trade so next one is new
	$memcache->set($user . 'mtrade', false);
	
	//clear consecutive trades
	$DB->Execute('update users set consecutive_merchant_trades = 0 where id = ?', array($user));

	update_action($user, 'NULL');
	
	$facebook->redirect('index.php?msg=trade-declined');
}
else if($trade_accept == 'Accept') {
	$trade = get_merchant_trade($user);
  $what_you_give_name = $trade['what_you_give_name'];
  $what_you_give_amount = $trade['what_you_give_amount'];
  $what_you_give_id = $trade['what_you_give_id'];
   
  $what_you_get_name = $trade['what_you_get_name'];
  $what_you_get_amount = $trade['what_you_get_amount'];
  $what_you_get_id = $trade['what_you_get_id'];
	$gold_amount = $trade['gold_amount'];
	
	$your_coin_total = get_coin_total($user);	
	//do you have enough to do the trade?
	$how_many_you_have = $DB->GetOne('select how_many from stuff where stuff_id = ? and user_id = ?', array($what_you_give_id, $user));
	if($how_many_you_have < $what_you_give_amount || $gold_amount > $your_coin_total) {
		$facebook->redirect('trade_ship_merchant.php?msg=not-enough');
	}
	
	
	//merchant gives you booty
	$DB->Execute('update stuff set how_many = how_many + ? where user_id = ? and stuff_id = ?', array($what_you_get_amount, $user, $what_you_get_id));
	
	//you give merchant booty
	$DB->Execute('update stuff set how_many = how_many - ? where user_id = ? and stuff_id = ?', array($what_you_give_amount, $user, $what_you_give_id));

	//take away coins if not 0
	if($gold_amount != 0) {
		$DB->Execute('update users set coin_total = coin_total - ? where id = ?', array($gold_amount, $user));
		
	}
	
	//increment total trades and consecutive trades
	$DB->Execute('update users set total_merchant_trades = total_merchant_trades + 1, consecutive_merchant_trades = consecutive_merchant_trades + 1 where id = ?', array($user));
	
	
	
	//clear the trade so next one is new
	$memcache->set($user . 'mtrade', false);

	//exchange the items
	update_action($user, 'NULL');
	
	$facebook->redirect('index.php?msg=trade-accepted');
	
}
else {
	$facebook->redirect('trade_ship_merchant.php');

}


?>
