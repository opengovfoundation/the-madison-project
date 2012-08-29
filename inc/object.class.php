<?php

class Object {
	
	var $id = 0;
	var $table;

	var $post = array();
	
	var $error;
	var $success;
	var $warning;
	
	var $db;
	
	public function initialize($id = 0, $table = '', $is_slug = false) 
	{
		if(!$id || $table == '')
			return false;

		$r = mysql_query("SELECT * FROM ".$table." WHERE ".($is_slug ? 'slug' : 'id')." LIKE '".mysql_real_escape_string($id)."'", $this->db->mySQLconnR);
		
		if(mysql_num_rows($r) < 1)
			return false;
		
		$item = mysql_fetch_assoc($r);
		
		foreach($item as $key=>$val)
		{
			if(strpos($key, 'phone') !== false)
				$val = strlen($val) == 11 ? '+1 ('.substr($val, 1, 3).') '.substr($val, 4, 3).'-'.substr($val, 7, 4) : '('.substr($val, 0, 3).') '.substr($val, 3, 3).'-'.substr($val, 6, 4);

			$this->{$key} = stripslashes($val);
		}
		
		$this->table = $table;
	}
	
	public function respond($data = array())
	{
		if($this->error != NULL)
		   	$response = array('type'=>'error', 'message'=>$this->error);	
		elseif($this->success != NULL)
			$response = array('type'=>'success', 'message'=>$this->success);
		else
		 	$response = array('type'=>'error', 'message'=>'No Function Preformed');	
		
		$this->error   = NULL;
		$this->success = NULL;
		$this->warning = NULL;
		
		return array_merge($response, $data, array('warning'=>$this->warning));
	}
	
	public function form_check()
	{
		$this->post  = $_POST;
		$this->error = '';
		
		foreach($this->post as $k=>$v)
		{
			$required  = strpos($k, '-required') !== false;

			if($required && $v == '')
				$this->error = 'Please Complete Required Fields';
				
			elseif(strpos($k, 'email') !== false && !$this->is_valid_email($v))
				$this->error = 'A Valid Email Address is Required';
				
			elseif($required && strpos($k, 'phone') !== false && !$this->is_valid_phone($v))
				$this->error = 'A Valid Phone Number is Required';
				
			elseif(strpos($k, 'zip') !== false && !$this->is_valid_zip($v))
				$this->error = 'A Valid Zip Code is Required';
				
			elseif($required && strpos($k, 'repassword') !== false && strlen($v) < 6)
				$this->error = 'Your Password Must be More than 5 Characters';
				
			elseif($required && strpos($k, 'repassword') !== false && $v != $this->post['password-required'])
				$this->error = 'Your Passwords do no Match';
				
			if($this->error != '')
				return false;
		}
		
		foreach($this->post as $k=>$v)
			$this->post[str_replace('-required', '', $k)] = $v;
		
		return true;
	}
	
	private function is_valid_email($email) 
	{
		return preg_match('/^([a-zA-Z0-9._%-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,4})*$/', $email);
	}
	private function is_valid_phone($phone) 
	{
		return preg_match('/^((([0-9]{1})*[- .(]*([0-9]{3})[- .)]*[0-9]{3}[- .]*[0-9]{4})+)*$/', $phone);
	}
	private function is_valid_zip($zip) 
	{
		return preg_match('/^[0-9]{5}(-[0-9]{4}){0,1}$/', $zip);
	}
}
	
?>