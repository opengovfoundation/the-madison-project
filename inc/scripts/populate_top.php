<?php

$user = '';

if(strpos($_SERVER['HTTP_HOST'], '.yourdomain.com') !== false)
{
	$host   = '';
	$pass   = ''; 
	$dbname = '';
}
else
{
	$host   = '';
	$pass   = ''; 
	$dbname = '';
}

$conn = mysql_connect($host,$user,$pass,1) or die ("Error connecting to mysql server");
mysql_select_db($dbname, $conn) or die ("Cannot connect to database ".mysql_error());



//$r 	  = mysql_query("SELECT * FROM hoba_note_meta");
//while($n = mysql_fetch_assoc($r))
//{
//	mysql_query("UPDATE hoba_notes SET ".$n['meta_key']."s='".$n['meta_value']."' WHERE id='".$n['note_id']."'");
//}
//	
$data = array();

$r 	  = mysql_query("SELECT id FROM hoba_bills");
while($b = mysql_fetch_row($r))
{
	$data[$b[0]] = array('suggestion'=>array(), 'comment'=>array());
	
	foreach($data[$b[0]] as $type=>$da)
	{
		$ri   = mysql_query("SELECT id,likes,dislikes,flags FROM hoba_notes WHERE bill_id='".$b[0]."' AND type='".$type."'");	
		while($s = mysql_fetch_assoc($ri)) 
			$data[$b[0]][$type][$s['id']] = compute_score($s['likes'], $s['dislikes'], $s['flags']);

		arsort($data[$b[0]][$type]);
	}
	
	
}

print_r($data);

mysql_query("TRUNCATE TABLE hoba_top_notes;");

foreach($data as $bid=>$types)
{
	foreach($types as $type=>$scores)	
	{
		$i = 0;
		foreach($scores as $nid=>$score) 
		{
			if ($i > 100) continue;
			mysql_query("INSERT INTO hoba_top_notes (bill_id, note_id, score) VALUES ('".$bid."', '".$nid."', '".$score."')");
  			$i++;
		}
	}
}

// roddy's hot sauce
function compute_score($likes, $dislikes, $flags) { 
  return $likes / ($likes + $dislikes + $flags + 1);
}
