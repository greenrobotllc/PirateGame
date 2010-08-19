<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;
require_once 'header.php';

$type= get_team($user);

//print_r(get_audited_users());

$team_listing = $_REQUEST['team'];
$sort_listing = $_REQUEST['sort'];

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

<div style='padding:20px'>
<h1>Pirate Rules</h1>

<p>
(1) Players must play the game fairly and honestly, not engaging in any syndicate play or the use of any auto-mated tools, whether they are your own or made by someone else.
</p>
<p>
(2) Any player movements can be logged at any time to verify their play patterns.
</p>
<p>
(3) Where a playing pattern may be deeemd as unusual, the "captcha" functionality can be implemented for this player for an undetermined amount of time to verfiy their playing of the game fairly. (this assumes you can do this! say every 50 miles?) 
</p>
<p>
(4) Playing as a syndicate is not allowed within the game; the definition of syndicate would be any players who are amassing benefits and passing these on to other players in a pattern determined to be consistent by the Developers.
</p>
<p>
(5) Playing patterns unexplicably changing during any monitoring, which is not to the satisfaction of the Developers, will be deemed as breaking the rules of the game.
</p>
<p>
(6) Players are allowed to "gift" other players with their coins through bombing in the event of, for example retiring from the game, to a maximum of 100k, providing this does not materially affect their position within the game (materially in this definition will be determined by the Developers).
</p>
<p>
(7) The Developers reserve the right to remove levels, coins, upgrades and booty for any player determined to have broken the rules. Players will normally be given 24 hours to justify their play patterns before any action is taken although this is not a requirement in all cases.
</p>
<p>
(8) Developers reserve the right to ban any player found to have broken any of the rules.
</p>
<p>
(9) Players are not allowed to spam the game or other players and must treat all other players with respect while posting messages.
</p>
<p>
(10) The Developers reserve the right to change the rules at any point without giving notice.
</p>
</div>
<center><h2><a href='index.php'>Back to sailin'</a></h2></center>
<br>
<?php
require_once 'footer_nolinks.php'; ?>
