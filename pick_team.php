<?php

require_once 'includes.php';
global $DB;

?>


<style>
#friends {
background:#FFFFFF none repeat scroll 0%;
border:1px solid #CCCCCC;
height:250px;
overflow:auto;
padding:10px;
}
.friend_box {
display:inline;
float:left;
height:50px;
margin-bottom:10px;
margin-right:20px;
overflow:hidden;
width:120px;
}
.friend_box input {
margin-left:5px;
}
.friend_image {
display:inline;
float:left;
height:50px;
overflow:hidden;
width:50px;
}
.friend_image img {
cursor:pointer;
width:50px;
}
.friend_name {
color:#555555;
display:inline;
float:left;
font-weight:bold;
height:30px;
margin-left:5px;
margin-top:3px;
overflow:hidden;
width:65px;
}
.img_fill_width {
width:50px;
}

</style>
<center>

<h1 style="font-size:150%; padding-top: 20px; text-align: center;">Arr... Pick a team, ya landlubber</h1>

<table style="width:100%; text-align: center; padding:10px;" cellpadding=5>

<tr>

<td style="border: 1px solid gray">
<h2 style="text-align:center">Buccaneers</h2>
	<a href="install.php?side=bucaneer&i=0">
    	<?php image($ship_bucaneer_175_image); ?>
	</a>
</td>

<td style="border: 1px solid gray">
<h2 style="text-align:center">Corsairs</h2>

	<a href="install.php?side=corsair&i=0">
      <?php image($ship_corsair_175_image); ?>  
	</a>
</td>

<td style="border: 1px solid gray">
<h2 style="text-align:center">Barbary pirates</h2>

	<a href="install.php?side=barbary&i=0">
    <?php image($ship_barbary_175_image); ?>
    </a>

</td>

</tr>

</table>

</center>

<?php require_once 'footer_nolinks.php'; ?>