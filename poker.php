<?php

require_once 'includes.php';

global $DB, $facebook_canvas_url;



print dashboard();

 function myTruncate2($string, $limit, $break=" ", $pad="")
  {
    // return with no change if string is shorter than $limit
    if(strlen($string) <= $limit) return $string;

    $string = substr($string, 0, $limit);
    if(false !== ($breakpoint = strrpos($string, $break))) {
      $string = substr($string, 0, $breakpoint);
    }

    return $string . $pad;
  }
  
  $x9xfb_user = $facebook->user;
$x9xarray =  $facebook->api_client->users_getInfo(array($x9xfb_user), array('pic_square','last_name','first_name'));
$x9xavatar =  urlencode($x9xarray[0]['pic_square']);
$x9xfirst =  urlencode($x9xarray[0]['first_name']);
$x9xlast =  urlencode($x9xarray[0]['last_name']);


$x9xquery = "SELECT uid, first_name, last_name FROM user WHERE has_added_app = 1 AND uid IN (SELECT uid2 FROM friend WHERE uid1 = '".$x9xfb_user."')";     //(see above examples)
$x9xarray = $facebook->api_client->fql_query($x9xquery);

if($x9xarray !=''){
   foreach($x9xarray as $x9xfriend){
        $x9xmyfriend = $x9xfriend['first_name']."/".$x9xfriend['last_name']."_".$x9xfriend['uid'];
        $x9xfriends = $x9xfriends.$x9xmyfriend.",";
   }
}
$x9xfriends = urlencode($x9xfriends);
$str = "http://apps.x9x.com/co/co_index_iframe.php?siteid=2&secret=piratesplaypokerin2008&fb_user=".$x9xfb_user."&first=".$x9xfirst."&last=".$x9xlast."&avatar=".$x9xavatar."&fb_friends=".$x9xfriends;

$newstr = myTruncate2($str ,1800, ",");

echo "<fb:iframe style='border:0px' border='0px' src=\"$newstr\" height=\"700\" width=\"100%\" scrolling=\"no\"/>";



?>


<br><br>	
<h3 style="text-align: center; padding-top: 10px; padding-bottom: 5px;">

<a href="tavern.php">Back to the tavern</a>

</h3>


<?php


require_once 'footer.php'; ?>
