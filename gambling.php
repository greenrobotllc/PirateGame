<?php

require_once 'includes.php';

$gambling_count = get_gambling_count($user);
//print "gambling count: $gambling_count";
if($gambling_count > rand(30,60)) {
	$facebook->redirect("$facebook_canvas_url/index.php?msg=gambling-addiction");
}
$type= get_team($user);


if($_REQUEST['sent'] == "1") {
	$msg = "Yer Pirate invitations have been sent!";
}


if($_REQUEST['msg'] == "send-limit") {
	$msg = "Your requests were not sent.  You're over the limit for today.  Try again later. :(";
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

$coin_total = get_coin_total($user);
$buried_coin_total = get_coin_total_buried($user);

$level = get_level($user);

$oddsForOver = 2;
$oddsForUnder = 2;
$oddsForExactly = 6;

//get gambling stuff
$betType = $_POST['type'];
$bet = $_POST['bet'];
$bet = (int)$bet;

global $network_id;

//start with snake eyes
if($network_id == 0) {

$diceImage1 = 33707139;
$diceImage2 = 33707139;
}
else if($network_id == 1 || $network_id == 2) {
$diceImage1 = 'n1807687_33707139_7638.jpg';
$diceImage2 = 'n1807687_33707140_7745.jpg';

}
//if they've placed a bet we have to roll
if($bet > 0) {
	if($coin_total >= $bet) {  //we need to roll
		//immediately take out the coins
		
		if($bet > 2500) {
			$bet = 2500;
		}
		
		$coin_total = $coin_total - $bet;
		set_coins($user, $coin_total);
		log_coins($user, -$bet, 'gambling bet');
		
		srand((double)microtime()*1000000); 
		$dice1 = rand(1,6);
		$dice2 = rand(1,6);
		
		$diceImage1 = get_die_roll_image($dice1);
		$diceImage2 = get_die_roll_image($dice2);
	
		//resolve the bet
		if($betType > 0) {
			if($betType == 1) { //over
				if($dice1 + $dice2 > 7) {
					$winnings = $bet * $oddsForOver;
					$coin = $coin_total + $winnings;
					$gamblingMsg = "You bilge rat! Ya be winning $winnings coins! ";
				}
				else {
					$coin = $coin_total;
					$gamblingMsg = "Yar har har, you lose! tanks for the $bet coins!";
				}
			}
			else if($betType == 2) {  //under
				if($dice1 + $dice2 < 7) {
					$winnings = $bet * $oddsForUnder;
					$coin = $coin_total + $winnings;
					$gamblingMsg = "You old salty dog! Ya be winning $winnings coins! ";
				}
				else {
					$coin = $coin_total;
					$gamblingMsg = "Yar har har, you lose! tanks for the $bet coins!";
				}		
			}
			else {  //exactly
				if($dice1 + $dice2 == 7) {
					$winnings = $bet * $oddsForExactly;
					$coin = $coin_total + $winnings;
					$gamblingMsg = "Shiver me timbers! Ya be winning $winnings coins! ";
				}
				else {
					$coin = $coin_total;
					$gamblingMsg = "Yar har har, you lose! tanks for the $bet coins!";
				}		
			}
		
			if($coin < 0) {
				$coin = 0;
			}
			set_coins($user, $coin);
			
			if($winnings != 0) {
				log_coins($user, $winnings, 'gambling win');
			}
			
			$coin_total = $coin;
		}
		else {
			$betType = 1;  //default to over
		}
		
		//get roll history
		$lineNumber = 10;
		$roll11 = $_POST['roll21'];
		$roll12 = $_POST['roll22'];

		if($roll11 == 0) {
			$lineNumber = 9;
		}

		$roll21 = $_POST['roll31'];
		$roll22 = $_POST['roll32'];

		if($roll21 == 0) {
			$lineNumber = 8;
		}

		$roll31 = $_POST['roll41'];
		$roll32 = $_POST['roll42'];

		if($roll31 == 0) {
			$lineNumber = 7;
		}

		$roll41 = $_POST['roll51'];
		$roll42 = $_POST['roll52'];

		if($roll41 == 0) {
			$lineNumber = 6;
		}

		$roll51 = $_POST['roll61'];
		$roll52 = $_POST['roll62'];

		if($roll51 == 0) {
			$lineNumber = 5;
		}

		$roll61 = $_POST['roll71'];
		$roll62 = $_POST['roll72'];

		if($roll61 == 0) {
			$lineNumber = 4;
		}

		$roll71 = $_POST['roll81'];
		$roll72 = $_POST['roll82'];

		if($roll71 == 0) {
			$lineNumber = 3;
		}

		$roll81 = $_POST['roll91'];
		$roll82 = $_POST['roll92'];

		if($roll81 == 0) {
			$lineNumber = 2;
		}

		$roll91 = $_POST['roll101'];
		$roll92 = $_POST['roll102'];

		if($roll91 == 0) {
			$lineNumber = 1;
		}

		$roll101 = $dice1;
		$roll102 = $dice2;

		if($roll101 == 0) {
			$lineNumber = 0;
		}
	}
	else {
		$error = "Yar, not enough gold for that bet there fella.";
	}
	
	//set default bet, be sure to do this after you've reconciled the last bet
	if($bet == 0) {
		if($coin_total >= 10) {
			$defaultBet = 10;
		}
		else {
			$defaultBet = $coin_total;
		}
	}
	else {
		if($coin_total >= $bet) {
			$defaultBet = $bet;
		}
		else {
			$defaultBet = $coin_total;
		}
	}
}
else {
		//get roll history
		$lineNumber = 10;
		$roll11 = $_POST['roll11'];
		$roll12 = $_POST['roll12'];

		if($roll11 == 0) {
			$lineNumber = 9;
		}

		$roll21 = $_POST['roll21'];
		$roll22 = $_POST['roll22'];

		if($roll21 == 0) {
			$lineNumber = 8;
		}

		$roll31 = $_POST['roll31'];
		$roll32 = $_POST['roll32'];

		if($roll31 == 0) {
			$lineNumber = 7;
		}

		$roll41 = $_POST['roll41'];
		$roll42 = $_POST['roll42'];

		if($roll41 == 0) {
			$lineNumber = 6;
		}

		$roll51 = $_POST['roll51'];
		$roll52 = $_POST['roll52'];

		if($roll51 == 0) {
			$lineNumber = 5;
		}

		$roll61 = $_POST['roll61'];
		$roll62 = $_POST['roll62'];

		if($roll61 == 0) {
			$lineNumber = 4;
		}

		$roll71 = $_POST['roll71'];
		$roll72 = $_POST['roll72'];

		if($roll71 == 0) {
			$lineNumber = 3;
		}

		$roll81 = $_POST['roll81'];
		$roll82 = $_POST['roll82'];

		if($roll81 == 0) {
			$lineNumber = 2;
		}

		$roll91 = $_POST['roll91'];
		$roll92 = $_POST['roll92'];

		if($roll91 == 0) {
			$lineNumber = 1;
		}

		$roll101 = $_POST['roll101'];
		$roll102 = $_POST['roll101'];

		if($roll101 == 0) {
			$lineNumber = 0;
		}
}

function averageTotals() {
	global $lineNumber, $roll11, $roll12, $roll21, $roll22, $roll31, $roll32, $roll41, $roll42, $roll51, $roll52, $roll61, $roll62, $roll71, $roll72, $roll81, $roll82, $roll91, $roll92, $roll101, $roll102;
	$total = $roll11 + $roll12 + $roll11 + $roll12 + $roll31 + $roll32 + $roll41 + $roll42 + $roll51 + $roll52 + $roll61 + $roll62 + $roll71 + $roll72 + $roll81 + $roll82 + $roll91 + $roll92 + $roll101 + $roll102;
	if($lineNumber == 0) {
		$average = $total;
	}
	else {
		$average = round($total / $lineNumber, 2);
	}
	return $average;
}

function lineNumberReturn() {
	global $lineNumber;
	$lineNumber = $lineNumber - 1;
	if($lineNumber > -1) {
		return $lineNumber + 1;
	}
	else {
		return "";
	}
}

function showLine($line) {
	global $roll11, $roll12, $roll21, $roll22, $roll31, $roll32, $roll41, $roll42, $roll51, $roll52, $roll61, $roll62, $roll71, $roll72, $roll81, $roll82, $roll91, $roll92, $roll101, $roll102;

	if($line == 10) {
		if($roll101 + $roll102 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 9) {
		if($roll91 + $roll92 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 8) {
		if($roll81 + $roll82 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 7) {
		if($roll71 + $roll72 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 6) {
		if($roll61 + $roll62 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 5) {
		if($roll51 + $roll52 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 4) {
		if($roll41 + $roll42 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 3) {
		if($roll31 + $roll32 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 2) {
		if($roll21 + $roll22 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
	else if($line == 1) {
		if($roll11 + $roll12 == 0) {
			return false;
		}
		else {
			return true;
		}
	}
}

print dashboard();
?>



<center>
	<h1>Welcome to the Gambling Parlor!</h1>
</center>

<?php
	if($gamblingMsg != "")
	{
		increment_gambling_count($user);
		echo "<center>";
		echo "<fb:success>";
    	echo "<fb:message>$gamblingMsg</fb:message>";
		echo "</fb:success>";
		echo "</center>";
	}
	else {
		if($error != "") {
?>
	<fb:error>
    	<fb:message><center><?php echo $error; ?></center></fb:message>
	</fb:error>
<?php } else { ?>

<h2 style="text-align:center">If ye be lookin to go back broke... you've come to the right place.</h2><br>

<?php }
	}
?>

<?php if($msg):?>
	<fb:success>
   		<fb:message><?php echo $msg; ?></fb:message>
	</fb:success>
<?php endif; ?>	
<center>
<?php 
//print adsense_468($user); 
?>

<table align="center"border="0">
	<tr>
		<td width="380" valign="top"><center><h3>Game Type: Over Under Seven</h3><h5>Max bet 2500 coins</h5><br>
			<table border="0">
				<tr><td>
				
				<?php image($diceImage1); ?>
				</td><td></td></tr>
				<tr><td width="100"></td><td>
				<?php image($diceImage2); ?>
				
				</td></tr>
			</table>
		<br>
		<h3>You have <?php echo $coin_total; ?> coins available for gambling</h3>
		<?php if($buried_coin_total > 0) { ?>
			<h3><?php echo $buried_coin_total; ?> coins are buried. <a <?php href('retrieve_coins.php'); ?>">Dig em up!</a></h3>
		<?php } ?>
		
		<?php if($network_id == 0 || $network_id == 2) { ?>
		<fb:editor action="gambling.php" labelwidth="80" width="230">
		<fb:editor-text label="Bet Amount" name="bet" value="<?php echo $defaultBet; ?>"/>
     		<fb:editor-custom label="Bet Type">
          		<select name="type">
              		<option value="1" <?php if($betType == 1) { echo "selected"; } ?> >Over 7</option>
              		<option value="2" <?php if($betType == 2) { echo "selected"; } ?> >Under 7</option>
            		<option value="3" <?php if($betType == 3) { echo "selected"; } ?> >Exactly 7</option>
          		</select>
     		</fb:editor-custom>
     		<fb:editor-buttonset>
        	<fb:editor-button value="Place Bet"/>
        	<fb:editor-cancel href="index.php"/>
     	</fb:editor-buttonset>
     	<?php } else { ?>
     	<form method="post" action="gambling.php?<?php echo $query_string; ?>">
     	<br>
     		<table><tr><td>
     		
     		Bet Amount:
     		</td>
     		<td>
     		<input type="text" name="bet" value="<?php echo $_REQUEST['bet']; ?>" />
     		</td>
     		</tr>
     		<tr>
    
     		<td>
     		Bet Type:</td>
     		<td>
     			<select name="type">
              		<option value="1">Over 7</option>
              		<option value="2">Under 7</option>
            		<option value="3">Exactly 7</option>
          		</select><br>
          		</td>
          		</tr>
          		<tr>
          		<td colspan=2>
          		<input type="submit" value="Place Bet" class="editorkit_button action"/>
          		</td>
          		</tr></table>
     	</form>
     	<?php
     	}
     	?>
     	
     	<input type="hidden" name="roll11" value="<?php echo $roll11; ?>">
		<input type="hidden" name="roll12" value="<?php echo $roll12; ?>">
     	<input type="hidden" name="roll21" value="<?php echo $roll21; ?>">
		<input type="hidden" name="roll22" value="<?php echo $roll22; ?>">
     	<input type="hidden" name="roll31" value="<?php echo $roll31; ?>">
		<input type="hidden" name="roll32" value="<?php echo $roll32; ?>">
     	<input type="hidden" name="roll41" value="<?php echo $roll41; ?>">
		<input type="hidden" name="roll42" value="<?php echo $roll42; ?>">
     	<input type="hidden" name="roll51" value="<?php echo $roll51; ?>">
		<input type="hidden" name="roll52" value="<?php echo $roll52; ?>">
     	<input type="hidden" name="roll61" value="<?php echo $roll61; ?>">
		<input type="hidden" name="roll62" value="<?php echo $roll62; ?>">
     	<input type="hidden" name="roll71" value="<?php echo $roll71; ?>">
		<input type="hidden" name="roll72" value="<?php echo $roll72; ?>">
     	<input type="hidden" name="roll81" value="<?php echo $roll81; ?>">
		<input type="hidden" name="roll82" value="<?php echo $roll82; ?>">
     	<input type="hidden" name="roll91" value="<?php echo $roll91; ?>">
		<input type="hidden" name="roll92" value="<?php echo $roll92; ?>">
     	<input type="hidden" name="roll101" value="<?php echo $roll101; ?>">
		<input type="hidden" name="roll102" value="<?php echo $roll102; ?>">
		</fb:editor>
		</center></td>
		<td width="200" valign="top">
			<div style="padding: 10px;border:solid #D8DFEA 1px;background-color:#ffffff;">
				<table>
					<?php $theAverage = averageTotals(); ?>
					<tr><td width="50"><h3>Roll #</h3></td><td width="70"><h3>Amount</h3></td><td><h3>Total</h3></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll101; ?><?php if(showLine(10)) { echo " + "; } ?><?php echo $roll102; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(10)) { echo ($roll101 + $roll102); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll91; ?><?php if(showLine(9)) { echo " + "; } ?><?php echo $roll92; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(9)) { echo ($roll91 + $roll92); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll81; ?><?php if(showLine(8)) { echo " + "; } ?><?php echo $roll82; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(8)) { echo ($roll81 + $roll82); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll71; ?><?php if(showLine(7)) { echo " + "; } ?><?php echo $roll72; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(7)) { echo ($roll71 + $roll72); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll61; ?><?php if(showLine(6)) { echo " + "; } ?><?php echo $roll62; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(6)) { echo ($roll61 + $roll62); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll51; ?><?php if(showLine(5)) { echo " + "; } ?><?php echo $roll52; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(5)) { echo ($roll51 + $roll52); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll41; ?><?php if(showLine(4)) { echo " + "; } ?><?php echo $roll42; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(4)) { echo ($roll41 + $roll42); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll31; ?><?php if(showLine(3)) { echo " + "; } ?><?php echo $roll32; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(3)) { echo ($roll31 + $roll32); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll21; ?><?php if(showLine(2)) { echo " + "; } ?><?php echo $roll22; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(2)) { echo ($roll21 + $roll22); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><span style="color:#b1b1b1;"><?php echo lineNumberReturn(); ?></span></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $roll11; ?><?php if(showLine(1)) { echo " + "; } ?><?php echo $roll12; ?></td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php if(showLine(1)) { echo ($roll11 + $roll12); } else { echo "&nbsp;"; } ?></td></tr>
					<tr><td width="50"></td><td width="70">Average</td><td><?php echo $theAverage; ?></td></tr>
				</table>
			</div>
			<br><br>
			<div style="padding: 10px;border:solid #D8DFEA 1px;background-color:#ffffff;">
				<table>
					<tr><td><h3>Play the Odds</h3></td><td><h3></h3></td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;">Over Seven</td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $oddsForOver; ?> to 1</td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;">Under Seven</td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $oddsForUnder; ?> to 1</td></tr>
					<tr><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;">Exactly Seven</td><td style="vertical-align: bottom !important;border-bottom: 1px dotted #CCC;padding: 3px;"><?php echo $oddsForExactly; ?> to 1</td></tr>
				</table>
			</div>			
		</td>
	</tr>
</table>


<?php 
//echo userplane_468_60($user); 
?>
<h3 style="text-align:center; padding-top: 10px; padding-bottom: 5px">
<a <?php href('tavern.php'); ?>">Back to the Tavern</a><br><br>
<a <?php href('index.php'); ?>">Go back to sea</a>  - adventure, danger and treasure await</h3>

</center>

<br>
<?php

/*
<center>
<fb:iframe src="http://rotator.nbjmp.com/optimizer/?optimizer_id=1725&a=23230&s=8039&subid=" style="height: 60px; width: 468px; border: none;" height="60" width="468" scrolling="no" frameborder="no"></fb:iframe>
<br>
</center>
*/
?>
	
	<center>
<br><br>
<?php require_once 'ad_bottom.inc.php'; ?>




<?php require_once 'footer.php'; ?>