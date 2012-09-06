<?php

class User extends Object {
	
	protected $salt = 'hoba';
	
	var $loggedin    = false;
	var $approved	 = false;
	var $user_level	 = 3;
	var $meta 	     = array();

	/* USER CONSTRUCTOR
	=====================================================================*/
	public function __construct($id = 0, $db = NULL) 
	{
		$this->db = $db;

		if($id)
		{
			parent::initialize($id, DB_TBL_USERS);
			
			//CREATE DISPLAY NAME 
			$this->display_name = $this->company != '' ? $this->company : $this->fname.' '.strtoupper(substr($this->lname, 0, 1)).'.';
			
			//GRAB ALL META PROPERTIES
			$r = mysql_query("SELECT meta_key,meta_value FROM ".DB_TBL_USER_META." WHERE user='".$this->db->clean($this->id)."'", $this->db->mySQLconnR);
			while($u = mysql_fetch_assoc($r))
				$this->meta[$u['meta_key']] = $u['meta_value'];
		}
	}
	
	/* CREATE USER
	=====================================================================*/
	public function create()
	{	
		if(parent::form_check()) // Check form
		{
			if($this->email_exists($this->post['email'])) // Check if Email already exists in DB
			{
				$this->error = '\''.$this->post['email'].'\' already exists in our database';
				return parent::respond();
			}
			
			//Insert User Record
			$this->id = $this->db->insert(DB_TBL_USERS, array('email'	=> $this->post['email'],
												 			 'password'	=> md5($this->salt.$this->post['password'].$this->salt),
												 			 'fname'	=> $this->post['fname'],
												 			 'lname'	=> $this->post['lname'],
															 'company'	=> $this->post['company'],
															 'position'	=> $this->post['position'],
															 'phone'	=> preg_replace('/[^\d]/', '', $this->post['phone']),
															 'url'		=> $this->post['url'],
												 			 'zip'		=> $this->post['zip'],
												 			 'created'	=> time()));
			
			$this->meta['_account_hash'] = md5($this->salt.$this->post['email'].$this->salt);
			$this->db->insert(DB_TBL_USER_META, array('user'=>$this->id, 'meta_key'=>'_account_hash', 'meta_value'=>$this->meta['_account_hash']));
			
			if($this->post['company'] !== '') // If Company Delay Account Activation
			{
				$this->db->insert(DB_TBL_USER_META, array('user'=>$this->id,'meta_key'=>'company_approved','meta_value'=>'0'));
				$this->success = 'Account Created Successfully!<br>
								  You\'ve created an Organization Account but it is not yet activated.  Once we verify your organization you will receive an email notification'; 
			}
			else
			{
				//$this->login($this->post['email'], $this->post['password']); // If Not Company Auto-Login
				$this->success = 'Account Created Successfully!<br>
								  You\'ll recieve an email soon to confirm your address and activate your account.';
			}

			return parent::respond();
		}
		return parent::respond();	
	}
	
	/* EDIT USER DETAILS
	=====================================================================*/
	public function edit()
	{	
		if(parent::form_check()) // Check Form
		{
			if($this->post['email'] != $this->email && $this->email_exists($this->post['email'])) // If email changes check if exists already
			{
				$this->error = '\''.$this->post['email'].'\' already exists in our database';
				return parent::respond();
			}
			
			//Update User Details in DB
			$this->db->update(DB_TBL_USERS, array('email'	=> $this->post['email'], 
												  'fname'	=> $this->post['fname'], 
												  'lname'	=> $this->post['lname'], 
												  'zip'		=> $this->post['zip'], 
												  'phone'	=> (isset($this->post['phone']) ? preg_replace('/[^\d]/', '', $this->post['phone']) : ''), 
												  'position'=> (isset($this->post['position']) ? $this->post['position'] : $this->position), 
												  'url'		=> (isset($this->post['url']) ? $this->post['url'] : $this->url)), "id='".$this->db->clean($this->id)."'");

			if($this->post['password'] != '') // Update Password if not Blank
			{
				if(strlen($this->post['password']) < 6)
					$this->error = 'Your Password Must be More than 5 Characters';
				
				elseif($this->post['password'] != $this->post['repassword'])
					$this->error = 'Your Passwords do not Match';
				
				else
				{
					$this->password = md5($this->salt.$this->post['password'].$this->salt);
					$this->db->update(DB_TBL_USERS, array('password'=>$this->password), "id='".$this->db->clean($this->id)."'");
				}
			}
			
			parent::initialize($this->id, DB_TBL_USERS);
			$this->display_name = $this->company != '' ? $this->company : $this->fname.' '.strtoupper(substr($this->lname, 0, 1)).'.';
			
			$this->success = 'Account Updated Successfully!';
		}
		return parent::respond();	
	}
	
	/* FACEBOOK LOGIN 
	=====================================================================*/
	public function fb_login($email, $fname, $lname){
	  
	  if(!$this->email_exists($email))//If the email doesn't exist
	  {  
	    //Insert User Record
			$this->id = $this->db->insert(DB_TBL_USERS, array('email'	=> $email,
												 			 'fname'	=> $fname,
												 			 'lname'	=> $lname,
												 			 'created'	=> time()));					 			 
			$id = $this->id;
			
			if(!isset($id) || $id == null || $id == 0){
  	    error_log("Could not create account for $email");
  		  $this->error = "There was an error signing up with Facebook";
    		return parent::respond();
  		}
	  }
	  else{
	    $r = mysql_query("SELECT id FROM ".DB_TBL_USERS." WHERE email='".$email."'", $this->db->mySQLconnR); // CHECK LOGIN CREDS
	    
	    if(mysql_num_rows($r) < 1)
    	{
    	  error_log("Could not sign in account for $email");
    		$this->error = "There was an error signing in with Facebook";
    		return parent::respond();
    	}
    	
    	$id = mysql_result($r,0);
    	
    	//UPDATE LAST LOGIN TIMESTAMP
    	$this->db->update(DB_TBL_USERS, array('last_login'=>time()), "id='".$this->db->clean($this->id)."'");
	  }
	  
	  
	
  	//GRAB ALL OBJECT PROPERTIES
  	parent::initialize($id, DB_TBL_USERS);
	
  	//CREATE DISPLAY NAME 
  	$this->display_name = $this->company != '' ? $this->company : $this->fname.' '.strtoupper(substr($this->lname, 0, 1)).'.';
	
  	//GRAB USER META PROPERTIES
  	$r = mysql_query("SELECT meta_key,meta_value FROM ".DB_TBL_USER_META." WHERE user='".$this->db->clean($this->id)."'", $this->db->mySQLconnR);
  	while($u = mysql_fetch_assoc($r))
  		$this->meta[$u['meta_key']] = $u['meta_value'];
	
  	//CHECK IF ACCOUNT IS COMPANY - IF IT IS AND THE COMPANY HAS NOT BEEN APPROVED, ERROR OUT
  	if(isset($this->meta['company_approved']) && $this->meta['company_approved'] == '0')
  	{
  		$this->error = "Your Organization Account has Not Yet Been Approved.";
  		return parent::respond();
  	}
	  
	  //SET LOGIN TO TRUE AND SAVE USER ID AS COOKIE
  	$this->loggedin = true;
  	setcookie("user", $this->id, time() + (3600 * 24 * 365), "/", $_SERVER['HTTP_HOST']);
	
  	$this->success = 'Login Successful.';

  	return parent::respond();
	}
	
	/* LOGIN
	=====================================================================*/
	public function login($email, $pass)
	{
	  $r = mysql_query("SELECT id, user_level FROM ".DB_TBL_USERS." WHERE email='".$email."' AND password='".md5($this->salt.$pass.$this->salt)."'", $this->db->mySQLconnR); // CHECK LOGIN CREDS
		
	  if(mysql_num_rows($r) < 1) // BAD LOGIN CREDS
  	{
  		$this->error = "Email/Password combination not found";
  		return parent::respond();
  	}
	
  	$id = mysql_result($r,0, 0);

	$this->user_level = mysql_result($r, 0, 1);
	
  	//UPDATE LAST LOGIN TIMESTAMP
  	$this->db->update(DB_TBL_USERS, array('last_login'=>time()), "id='".$this->db->clean($this->id)."'");
	
  	//GRAB ALL OBJECT PROPERTIES
  	parent::initialize($id, DB_TBL_USERS);
	
  	//CREATE DISPLAY NAME 
  	$this->display_name = $this->company != '' ? $this->company : $this->fname.' '.strtoupper(substr($this->lname, 0, 1)).'.';
	
  	//GRAB USER META PROPERTIES
  	$r = mysql_query("SELECT meta_key,meta_value FROM ".DB_TBL_USER_META." WHERE user='".$this->db->clean($this->id)."'", $this->db->mySQLconnR);
  	while($u = mysql_fetch_assoc($r))
  		$this->meta[$u['meta_key']] = $u['meta_value'];
	
  	//CHECK IF ACCOUNT IS COMPANY - IF IT IS AND THE COMPANY HAS NOT BEEN APPROVED, ERROR OUT
  	if(isset($this->meta['company_approved']) && $this->meta['company_approved'] == '0')
  	{
  		$this->error = "Your Organization Account has Not Yet Been Approved.";
  		return parent::respond();
  	}
  	elseif(isset($this->meta['_account_hash']))
  	{
  		$this->error = "Your Account Has Not Yet Been Activated.  Please Check Your Inbox For Your Confirmation Email.<br>
  						<a href='".SERVER_URL."/login?resend-confirmation'>Click Here to resend your confirmation email</a>.";
  		return parent::respond();
  	}
	
  	//SET LOGIN TO TRUE AND SAVE USER ID AS COOKIE
  	$this->loggedin = true;
  	setcookie("user", $this->id, time() + (3600 * 24 * 365), "/", $_SERVER['HTTP_HOST']);
	
  	$this->success = 'Login Successful.';

  	return parent::respond();
  }
	
	/* DELETE THIS USER
	=====================================================================*/
	public function delete()
	{
		mysql_query("DELETE FROM ".DB_TBL_USERS." 	  WHERE id='".$this->db->clean($this->id)."'", $this->db->mySQLconnRW);
		mysql_query("DELETE FROM ".DB_TBL_USER_META." WHERE user='".$this->db->clean($this->id)."'", $this->db->mySQLconnRW);
	}
	
	/* CHECK IF USER EXISTS IN DB
	=====================================================================*/
	private function email_exists($e)
	{
		$r = mysql_query("SELECT id FROM ".DB_TBL_USERS." WHERE email LIKE '".$this->db->clean($e)."'", $this->db->mySQLconnR);
		return mysql_num_rows($r);
	}
	
}
	
?>