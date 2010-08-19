<?php

include_once '../client/facebook.php';
// some basic library functions
include_once 'lib.php';
// this defines some of your basic setup
include_once 'config.php';
global $DB;

//get sword

//get pistol

//check if there is an entry user_id_2 null in barfight table

//if its null
//insert user_id_1, team, sword, pistol into barfight table
//display waiting and refresh message

//else insert user_id_2, team, sword, pistol
//select user_id_1, team_enemy, sword_enemy, pistol_enemy from barfights
$action = 'pistols';


if($action == 'pistols') {
    //check for pistols
    //if one of them doesnt have pistols, move on
    //50% 50% chance for each pirate
    if(rand(0,1) ==0) {
        //loser
        //loses up to 50 coins
        //takes weapon
        $facebook->redirect("tavern.php?msg=?lose&coins=$coins&weapon=pistol&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
    else {
        //winner
        //wins up to 50 coins, 
        //takes weapon
        $facebook->redirect("tavern.php?msg=?win&coins=$coins&weapon=pistol&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
}
else if($action == 'swords') {
    //check for swords
    //if one of them doesnt have swords, move on

    if(rand(0,1) == 1) {
        //loser
        //loses up to 50 coins
        //takes weapon
        $facebook->redirect("tavern.php?msg=?lose&coins=$coins&weapon=pistol&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
    else {
        //winner
        //wins up to 50 coins, 
        //takes weapon
        $facebook->redirect("tavern.php?msg=?win&coins=$coins&weapon=pistol&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
}
else {  
    //if no pistols or swords
    if(rand(0,1) == 1) {
        //loser
        //loses up to 50 coins
        $facebook->redirect("tavern.php?msg=?lose&coins=$coins&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
    else {
        //winner
        //wins up to 50 coins, 
        $facebook->redirect("tavern.php?msg=?win&coins=$coins&your_exp=$your_exp&enemy_exp=$enemy_exp");
    }
}

?>