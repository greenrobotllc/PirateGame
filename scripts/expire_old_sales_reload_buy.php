<?php
require_once '../config_network.php';
include_once '../../client/facebook.php';
include_once 'script_config_facebook.php';

function get_expired_uids($user, $now_time) {
	global $memcache, $DB;
	
	//since we're not actually deleting yet, look 1 minutes ahead for more expires so that when we delete we'll account
	//for onces that are now ready to be deleted - this is just to be safe
	$sql = "select id, stuff_id from items_sell where created_at + INTERVAL 1 DAY <= ?;";

	try {
		$returned_array = $DB->GetArray($sql, array($now_time));
	}
	catch (exception $e) {
//		echo "No expired!/n";
		return false;
	}

/*	for($i = 0; $i < count($returned_array); $i++) {
		echo $i . " " . $returned_array[$i]['id'] . ":";
		echo $i . " " . $returned_array[$i]['stuff_id'] . "\n";
	}	*/

	return $returned_array;
}

function delete_expired_uids_from_db($user, $now_time) {
	global $memcache, $DB;

	$sql = "delete from items_sell where created_at + INTERVAL 1 DAY <= ?;";
	
	try {
		$result = $DB->Execute($sql, array($now_time));
	}
	catch (exception $e) {
		return false;
	}	
	
	return true;
}

$sql = "select now()";
$now_time = $DB->GetOne($sql);

$user_array = get_expired_uids($user, $now_time);
if($user_array == false) {
	//done, no items have expired
}
else {
	//delete items from memcache etc
	$success = delete_expired_uids_from_db($user, $now_time);
	$success = true;
	if($success == true) {
//		echo "Granting items back: \n";
		for($i = 0; $i < count($user_array); $i++) {
			increment_booty($user_array[$i]['id'], $user_array[$i]['stuff_id']);
			
			$returned_data = get_booty_data_from_id($user_array[$i]['stuff_id']);
			$item_name = $returned_data[0];
			
			try {
				$sql = "select session_key from users where id = ?";
				$seller_session_key = $DB->GetOne($sql, $user_array[$i]['id']);
			}
			catch (exception $e) {
				//maybe no longer in our db, ignore error
			}
		
			try {
				$facebook_new = new Facebook($api_key, $secret);
				$facebook_new->set_user($user_array[$i]['id'], $seller_session_key);
				$facebook_new->api_client->notifications_send(array($user_array[$i]['id']) , " just had an item expire on the <a href='$facebook_canvas_url'>Pirates trading post</a>. A $item_name was not sold and was returned to your inventory!");
			} catch (FacebookRestClientException $fb_e) {
				//do nothing here, might not have a valid infinite session key to notify the person
			}			
		}		
	
		$unique_array = array_unique($user_array);

/*		echo "\n\n";
		for($i = 0; $i < count($unique_array); $i++) {
			echo $i . " " . $unique_array[$i]['id'] . ":";
			echo $i . " " . $unique_array[$i]['stuff_id'] . "\n";
		}*/

		for($i = 0; $i < count($unique_array); $i++) {
			create_memcache_sell_items_from_db($unique_array[$i]['id']);
		}		
	}
	else { // do nothing, error has occured
	}
}

/***************************************************************************************************/
// Done Delete Expired, Start repopulate BUY system
/***************************************************************************************************/
$num_items = 13;

for($i = 1; $i < $num_items + 1; $i++) {
	$return_array = generate_memcache_for_buying($user, $i);
	store_index_string_in_db($return_array[1], $i);
}

echo "\nDone Script...\n";
?>
