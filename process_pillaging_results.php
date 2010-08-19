<?php

require_once 'includes.php';



$crew_count = get_crew_count($user); 

if($crew_count < 1) {
    $facebook->redirect('shipyard.php');
}


$in = $_REQUEST['i'];
if(isset($in)) {
	$facebook->redirect("$facebook_canvas_url/install.php?i=$in");
}
//print_r($user);
$user_in_db = is_user_in_db($user);
if($user_in_db ==0) {
	$facebook->redirect("$facebook_canvas_url/install.php");
}

//hack attack!
redirect_to_index_if_not($user, "enemy_base");

//stuff has been granted, prevent them from reloading this page to get more.
update_action($user, "NULL");

$houseItem = get_item_from_memcache($user);

//print dashboard();

$houseCount = $_POST['houseCount'];

if($crew_count < $houseCount) {
        $houseCount = $crew_count;
}

if($houseCount > 15) {
	$houseCount = 15;
}

for($i = 1; $i <= $houseCount; $i++) {
	$currentHouse = "house$i";
	$houseList[$i - 1] = $_POST[$currentHouse];
}

$your_coins = get_coin_total($user);
$pay_total = 0;

$crew_per_house = get_crew_per_house($user, $houseCount);

$results_array = get_winnings_per_house($user, $houseCount, $houseList, $houseItem, $crew_per_house);

$houseSelected = false;
for($t = 0; $t < $houseCount; $t++) {
	if($results_array[$t] != "nothing") {
		$houseSelected = true;
	}
}
if($houseSelected == false) {
	$facebook->redirect("$facebook_canvas_url/pillage_town.php?msg=selecterror");
}

$house1 = "nohouse";
$house2 = "nohouse";
$house3 = "nohouse";
$house4 = "nohouse";
$house5 = "nohouse";
$house6 = "nohouse";
$house7 = "nohouse";
$house8 = "nohouse";
$house9 = "nohouse";
$house10 = "nohouse";
$house11 = "nohouse";
$house12 = "nohouse";
$house13 = "nohouse";
$house14 = "nohouse";
$house15 = "nohouse";
$stuff_id = "none";

for($g = 0; $g < $houseCount; $g++) {
	if($results_array[$g] == "gold") {
		$houseResult = rand(1,25 + 10 * $crew_per_house);
		if($houseResult > rand(80,100)) {
		  $houseResult = rand(60,90);  
		}
		
		update_coins($user, $houseResult);  //set their coins
		
	}
	else if($results_array[$g] == "item") {
		$houseResult = "item";
		$stuff_id = grant_item($user);  //grant them an item
	}
	else if($results_array[$g] == "failed") {
		if($your_coins > 0) {
			$ra = rand(1,2500);
			if($ra <= 1) {
				//crew member dies
				$houseResult = "killed";
				kill_crewmember($user);
			}
			else if($ra <= 1250) {
				$houseResult = "escapes";
			}
			else {
				if($your_coins > 20) {
					$pay = rand(1, 20);
				}
				else {
					$pay = rand(1, $your_coins);
				}
				$your_coins = $your_coins - $pay;
				if($your_coins < 0) {
					$your_coins = 0;
				}
				$pay_total = $pay_total + $pay;
				$houseResult = -$pay;
			}
		}
		else {
			$ra = rand(1,100);
			if($ra <= 2) {
				//crew member dies
				$houseResult = "killed";
				kill_crewmember($user);
			}
			else {
				$houseResult = "escapes";
			}
		}
	}
	else {  //nothing
		$houseResult = "nothing";
	}
	
	if($g == 0) {
		$house1 = $houseResult;
	} else if($g == 1) {
		$house2 = $houseResult;
	} else if($g == 2) {
		$house3 = $houseResult;
	} else if($g == 3) {
		$house4 = $houseResult;
	} else if($g == 4) {
		$house5 = $houseResult;
	} else if($g == 5) {
		$house6 = $houseResult;
	} else if($g == 6) {
		$house7 = $houseResult;
	} else if($g == 7) {
		$house8 = $houseResult;
	} else if($g == 8) {
		$house9 = $houseResult;
	} else if($g == 9) {
		$house10 = $houseResult;
	} else if($g == 10) {
		$house11 = $houseResult;
	} else if($g == 11) {
		$house12 = $houseResult;
	} else if($g == 12) {
		$house13 = $houseResult;
	} else if($g == 13) {
		$house14 = $houseResult;
	} else if($g == 14) {
		$house15 = $houseResult;
	}
}

$your_coins2 = get_coin_total($user);
if($your_coins2 - $pay_total < 0) {
	set_coins($user, 0);
}
else {
	$subtract_coins = $pay_total * -1;
	update_coins($user, $subtract_coins);  //make them pay
}



$house_total = $house1 + $house2 + $house3 + $house4 + $house5 + $house6 + $house7 + $house8 + $house9 + $house10 + $house11 + $house12 + $house13 + $house14 + $house15;

log_coins($user, $house_total, 'pillaging');



$facebook->redirect("$facebook_canvas_url/report_pillaging_results.php?stuffid=$stuff_id&house1=$house1&house2=$house2&house3=$house3&house4=$house4&house5=$house5&house6=$house6&house7=$house7&house8=$house8&house9=$house9&house10=$house10&house11=$house11&house12=$house12&house13=$house13&house14=$house14&house15=$house15");

?>
