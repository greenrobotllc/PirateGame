<?php 
require_once 'includes.php';
global $user;
$team = get_team($user); 
$postcount = get_postcount($user);
?>

<div class="wallkit_frame clearfix" id="comments_barbary_tavern">

<h3 class="wallkit_title"><?php echo ucwords($team); ?> Tavern</h3>
<div class="wallkit_subtitle clearfix">
<div style="float:left;">Displaying 10 of <?php echo $postcount; ?> posts.</div>

<div style="float:right;"><a href="tavernseeall.php?team=<?php echo $team; ?>">See All</a></div></div>

<div class="wallkit_form">
<form method="post" action="wallpost.php">
<textarea id="wall_text" name="wall_text"></textarea>
<input type="hidden" id="app_id" name="app_id" value="2342084241" />
<input type="hidden" id="xid" name="xid" value="barbary_tavern" />
<input type="hidden" id="c_url" name="c_url" value="http%3A%2F%2Fapps.facebook.com%2Fpirates%2Ftavern.php" />
<input type="hidden" id="r_url" name="r_url" value="http%3A%2F%2Fapps.facebook.com%2Fpirates%2Ftavern.php" />
<input type="hidden" id="sig" name="sig" value="333769a624317316d612aefffd795aa3" />
<input type="hidden" id="ret_now" name="ret_now" value="1" />
<input type="hidden" id="post_form_id" name="post_form_id" value="bb5d70b1d4b9d013fa6d7a16f39e2fc5" />
<input type="submit" class="inputsubmit" value="Post" />
</form>
</div>

<div class="wallkit_post">
<div class="wallkit_profilepic">
<a href="user_profile.php?user=660612581">
<img src="http://profile.ak.facebook.com/v222/712/88/t660612581_7638.jpg" alt=""  class="" /></a></div>
<div class="wallkit_postcontent">

	
	</div
</div>

<div class="wallkit_post"><div class="wallkit_profilepic"><a href="javascript:void(show_search_profile(512647676));//"><img src="http://profile.ak.facebook.com/profile5/458/26/t512647676_2981.jpg" alt=""  class="" /></a></div><div class="wallkit_postcontent"><h4><a href="javascript:void(show_search_profile(512647676));//">Sameer A. Saeed</a> wrote<span class="wall_time">at 2:10pm</span></h4><div>time fer me to be tieing up the SS undertow, it&#039;s time fer me dinner (it be 11:09pm over ere), but i shall return,...ARRR</div><div class="wallkit_actionset"><a href="http://www.facebook.com/inbox/?compose&id=512647676">message</a> - <a href="http://www.facebook.com/editwall.php?app_id=2342084241&xid=barbary_tavern&c_url=http%253A%252F%252Fapps.facebook.com%252Fpirates%252Ftavern.php&r_url=http%253A%252F%252Fapps.facebook.com%252Fpirates%252Ftavern.php&cdel=83609&action=delete&sig=280fa4c84214f29b7f7f41f927fae017">delete</a></div></div></div>


<p class="wallkit_extra"><a href="tavernseeall.php?team=<?php echo $team; ?>">See all posts &raquo;</a></p>
</div>
<br /><br />
<br /><br />