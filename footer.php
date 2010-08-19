<?php 

global $network_id;

if($network_id == 0) {
?>

<fb:google-analytics uacct = "UA-2275721-1" page ="<?php echo $_SERVER['PHP_SELF']; ?>" />

<?php

}
?>

<?php if(false) { ?>
<center><a href='http://apps.facebook.com/playscribble'>Play Scribble 
- a multiplayer draw and guess game similar to Pictionary</a></center>
<br>
<?php } ?>

