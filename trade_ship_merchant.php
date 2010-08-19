<?php

require_once 'includes.php';
global $DB;

redirect_to_index_if_not_or($user, "found_merchant_ship", "trade_ship_merchant");


update_action($user, "trade_ship_merchant");

?>

<style>

.whitelink a {
	color: #FFFFFF;
}

</style>

<?php

print dashboard();


$memcache->set($user . 'merchant_ship_limit', 1, false, 60 * 2);

if($_REQUEST['msg'] == "not-enough") {
	$msg = error_msg_return("You don't have enough booty for that trade!");
}

?>

<center>


<?php if($msg):?>
<?php echo $msg; ?>
<?php endif; ?>	





<table style='text-align:center; padding:5px; margin:10px;' cellspacing=5 cellpadding=5 border=0>
<tr>

<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>


<?php /*
<fb:photo pid='<?php echo $ship_barbary_blue_image; ?>' uid="<?php echo $image_uid; ?>" />
*/
$team = get_team($user);
//echo $team;
?>
<img src='<?php echo $base_url; ?>/images/<?php echo $team; ?>_blue_175.jpg' />

</td>

		<td valign='top' width='180px'>
	
		<h1>The ship captain offers you the following trade:</h1>
		<br>
		
		  <table border=0>
		  <tbody>
            <?php
                	
            	$trade = get_merchant_trade($user);
            	
            	
            	$what_you_give_name = $trade['what_you_give_name'];
            	$what_you_give_amount = $trade['what_you_give_amount'];
            	$what_you_give_pic = $trade['what_you_give_pic'];
            	$what_you_give_id = $trade['what_you_give_id'];
            	
            	$what_you_get_name = $trade['what_you_get_name'];
            	$what_you_get_amount = $trade['what_you_get_amount'];
            	$what_you_get_pic = $trade['what_you_get_pic'];
            	$what_you_get_id = $trade['what_you_get_id'];

            	$gold_amount = $trade['gold_amount'];
            	
       
            ?>   
               
                            <tr><td style="text-align: center;" colspan="3">

              <h4>I'll give you:</h4>
              
        
              </td></tr>
                             
              <tr>
                    <td width="50" valign="center" style="border-top: 0px dotted rgb(204, 204, 204); padding: 5px">
                    <div style="border: 1px solid grey; padding: 0px; height: 50px;">
                    <?php image($what_you_get_pic); ?>
                    
                    </div></td>
                    <td valign="center" style="border-top: 0px dotted rgb(204, 204, 204); padding: 5px; text-align: left;">

            
 
              

                    <h2><?php echo $what_you_get_name; ?></h2></td>
                    <td valign="center" style="padding: 0px 5px 0px 0px; text-align: center;">
                    
                    <h2>(x<?php echo $what_you_get_amount; ?>)</h2>
                    
                    
                    </td>
              </tr>
              
              <tr><td style="text-align: center;" colspan="3">
              <br>
              <?php if($gold_amount != 0) { ?>
              <h4>If you give me <strong><?php echo $gold_amount; ?> gold</strong> and:</h4>
              
             <?php
              }
              else {
              ?>
              <h4>If you give me:</h4>
              
              <?php
              }
              ?>
              </td></tr>
              
              <tr>
                    <td width="50" valign="center" style="border-top: 0px dotted rgb(204, 204, 204); padding: 5px;">
                    <div style="border: 1px solid grey; padding: 0px; height: 50px;">
                    
                    <?php image($what_you_give_pic); ?>

                    </div></td>
                    <td valign="center" style="border-top: 0px dotted rgb(204, 204, 204); padding: 5px; text-align: left;">
                    <h2><?php echo $what_you_give_name; ?></h2></td>
                    <td valign="center" style="padding: 0px 5px 0px 0px; text-align: center;">

                    <h2>(x<?php echo $what_you_give_amount; ?>)</h2>
                    
                    
                    </td>
               </tr>
                
                
                <tr>
                	<td style="border: 0px dotted gray; text-align: center;" colspan="3">
                   
                   <form method="post" action="merchant_trade_action.php">
										<?php
										
										//echo $what_you_give_id;
										$how_many_you_have = $DB->GetOne('select how_many from stuff where user_id = ? and stuff_id = ?', array($user, $what_you_give_id));
										
										
										$coin_total = get_coin_total($user);
										if($coin_total >=  $gold_amount && $how_many_you_have >= $what_you_give_amount) {
											$blue_or_gray = '#3B5998';
										}
										else {
											$blue_or_gray = 'gray';									
										}
										?>
										
                    <input type="submit" name="trade_accept" value="Accept" class="inputsubmit" style="background-color: <?php echo $blue_or_gray; ?>"/>    
                    
                    <input type="submit" name="trade_decline" value="Decline" class="inputsubmit"/>
                     </form>

                    </td>
                    
                </tr>
                
                
                </tbody></table>
        
	
	
	
	
	
	

		</td>



<td align='center' style='border: 1px dotted black; background-color: #3B5998; color: #FFFFFF'>

<span style='text-align:center'>

<?php image($merchant_image); ?>




</td>

</tr>


</table>


			
		


<?php

echo adsense_468($user);
echo '<br>';


?>


<?php require_once 'footer.php'; ?>
</center>
