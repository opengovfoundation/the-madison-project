<?php

/**
 * db.class.php
 *
 * Purpose is to simplify commonly used db code
 *
 * @package inc
 */
class db {

	var $mySQLconnRW;
	var $mySQLconnR;

	function __construct($creds) {
		
		$this->mySQLconnRW = mysql_connect($creds['rw']['host'],$creds['rw']['user'],$creds['rw']['pass'],1);
		mysql_select_db($creds['rw']['name'], $this->mySQLconnRW) or die ("Cannot connect to database ".mysql_error());
		
		if(!IS_STAGE)
		{
			ini_set('mysql.connect_timeout', '30');
			
			$rs = array(2);
	
			$this->mySQLconnR = mysql_connect($creds['r'.$rs[0]]['host'],$creds['r'.$rs[0]]['user'],$creds['r'.$rs[0]]['pass'],1) or die(mysql_error());
			if(empty($this->mySQLconnR))
			{
				sleep(3);
				$this->mySQLconnR = @mysql_connect($creds['r'.$rs[1]]['host'],$creds['r'.$rs[1]]['user'],$creds['r'.$rs[1]]['pass'],1);
			}
			mysql_select_db($creds['r'.$rs[0]]['name'], $this->mySQLconnR);
		} 
		else 
		{
			$this->mySQLconnR = $this->mySQLconnRW;
			mysql_select_db($creds['rw']['name'], $this->mySQLconnR) or die ("Cannot connect to database ".mysql_error());
		}
    }
	
	public function clean($i)
	{
		$o = $i;	
		$o = mysql_real_escape_string(stripslashes($i));
		$o = preg_replace("/(<script.*>.*<\/script>)/i", '', $o);
		$o = preg_replace("/src\=([^jJ]*)javascript/i", '', $o);
		return $o;
	}
	
	public function insert($t, $i)
	{
		if(empty($i))
			return 0;
		
		$fns  = '';
		$vals = '';
		
		foreach($i as $k=>$v)
		{
			$fns  .= ",`".$k."`";
			$vals .= ",'".$this->clean($v)."'";	
		}

		mysql_query("INSERT INTO ".$t." (".substr($fns,1).") VALUES (".substr($vals,1).")", $this->mySQLconnRW);
		
		return mysql_insert_id($this->mySQLconnRW);
	}
	
	public function update($t, $u, $w = '')
	{
		if(empty($u))
			return 0;
		
		$update = '';
		
		foreach($u as $k=>$v)
			$update .= ",`".$k."`=".($v == $k.'='.$k.'+1' || $v == $k.'='.$k.'-1' ? $this->clean($v) : "'".$this->clean($v)."'");

		mysql_query("UPDATE ".$t." SET ".substr($update, 1).($w == '' ? "" : " WHERE ".$w), $this->mySQLconnRW) or error_log('MYSQL ERROR: '.mysql_error());
		
		return mysql_affected_rows($this->mySQLconnRW);
	}
}
?>
