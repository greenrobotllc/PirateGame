<?php 
require_once("adodb/adodb-exceptions.inc.php");
require_once("adodb/adodb.inc.php");

$db_name = 'CHANGEME';
$db_ip='CHANGEME';
$db_user = $db_name;
$db_name = $db_user;
$db_pass = 'CHANGEME';


$connect= "mysql://$db_user:$db_pass@$db_ip/$db_name?persist";
$DB = NewADOConnection($connect);
    $memcache = new Memcache();
    $memcache->addServer('10.12.198.194', 11211);

function get_total_joke_count() {
	global $memcache, $DB;
	$r = $memcache->get("total_joke_count_profile");
	if($r == FALSE) {
		$sql = "SELECT max(id) from jokes_approved"; 
		$r = $DB->GetOne($sql);
		$memcache->set("total_joke_count_profile", $r, false, 60);
	}
	//echo "total user count: $r";
	return $r;
	
}
$total_joke_count = get_total_joke_count();
//echo $total_joke_count;
$ra = rand(1,$total_joke_count);

/*
if($memcache) {
		$r = $memcache->get('joke_random');
		if($r == FALSE) {
			*/

$r = $DB->GetRow('select * from jokes_approved where id = ?', array($ra));

/*			$memcache->set('joke_random', 10);
		}
	}
	else {
		$r = $DB->GetRow('select * from jokes where id = ?', array($ra));
	}
*/
	
	//print_r($r);
	
	//$size = count($r);
	
	//echo "size is $size";
	
//$r = $DB->GetRow('select * from jokes limit 1');
	$question =$r['question'];
	$answer =$r['answer'];
	$user =$r['user'];
	
	if($question == '' || $question == FALSE) {
		$question = 'Why are Pirates so Cool?';
		$answer = 'They just arrrr!!!';
	}
	
	

?>

<div id='lolinner'>

<div style='margin:5px; border: 1px dotted black'><p style='text-align:center; font-size:125%'><strong>Q:  </strong><?php echo $question; ?></p>
</div>
<div style='margin:5px; border: 1px dotted black'>
<p style='text-align:center; font-size:125%'><strong>A:  </strong><?php echo $answer; ?></p>

</div>

</div>