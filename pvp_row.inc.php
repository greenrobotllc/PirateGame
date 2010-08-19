
<?php

$pvp_toggle = $_REQUEST['pvp'];
//print "aaa $pvp_toggle bbb";
if(!empty($pvp_toggle)) {
    if($pvp_toggle == 'off') {
          $memcache->set($user .'pvp', $pvp_toggle);
          $DB->Execute('update users set pvp_off = 1 where id = ?', array($user));
    }
    else if($pvp_toggle == 'on') {
          //echo 'setting to on';
          $memcache->set($user .'pvp', $pvp_toggle);        
          $DB->Execute('update users set pvp_off = 0 where id = ?', array($user));
    }
  }

$pvp_toggle = $memcache->get($user . 'pvp');
//print "pvp toggle $pvp_toggle";
if($pvp_toggle == 'off') {
?>
<center>

<h2 style="text-align:center; padding-bottom: 0px; margin-bottom:0px;">Exploring safe waters <a title='click to turn pirate vs pirate fighting off' href='index.php?pvp=on'>sail towards enemy waters</a>


<?php

}
else {
?>
<center>
<h2 style="text-align:center; margin-bottom:0px">Exploring enemy waters! <a title='click to turn pirate vs pirate fighting on' href='index.php?pvp=off'>sail away from enemy</a>



<?php

}

?>
