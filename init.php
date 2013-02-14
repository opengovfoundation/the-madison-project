<?php
/**
 *	Madison controller
 *
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

	/* REQUIRE CONFIGURATION SCRIPT
	=====================================================================*/
	require("inc/config.php");
	
	/* CLASSES TO INCLUDE
	=====================================================================*/
	
	require("inc/db.class.php");
	require("inc/object.class.php");
	require("inc/bill.class.php");
	require("inc/user.class.php");
	require("inc/functions.php");
	require("inc/facebook.php");
	

	
	/* START SESSION
	=====================================================================*/
	session_start();
	
	
	/* INSTANTIATE GLOBALS - user and bill
	=====================================================================*/
	global $u, $b, $db;
	
	/* LOG USER OUT
	=====================================================================*/
	if(isset($_GET['logout']))
	{
		unset($_SESSION['user'], $_SESSION['bill']);
		setcookie("user", '0', time() - (3600 * 8), "/", $_SERVER['HTTP_HOST']);
		
		redirect(SERVER_URL.(isset($_GET['redirect']) ? urldecode($_GET['redirect']) : ''));
	}

	
	/* INSTANTIATE INITIAL CLASSES
	=====================================================================*/
	$db = new db($db_creds);
	
	//Update Database
	require('update/update-db.php');

	// Force home page to be OPEN bill for initial launch.
	//$_GET['page'] = $type == 'index' || $_SERVER['REQUEST_URI'] == '/' || strpos($_SERVER['REQUEST_URI'], '/?') !== false ? 'digital-bill-of-rights' : $_GET['page'];

	$u  = isset($_SESSION['user']) ? $_SESSION['user'] : (isset($_COOKIE['user']) ? new User($_COOKIE['user'], $db) : new User(0, $db));
	if(!isset($_SESSION['bill'])){
		$firstId = mysql_query("SELECT id from " . DB_TBL_BILLS . " limit 1");
		$firstId = mysql_result($firstId, 0);
		
		$b = $_SESSION['bill'] = new Bill($firstId, $db);
	}
	else{
		$b = $_SESSION['bill'];
	}
	
	//print_r($b);
	
	/* CHECK IF REQUESTED BILL IS DIFFERENT THAN SESSION BILL
	=====================================================================*/
	if(isset($b->slug) && $b->slug != $_GET['page'] && $_SERVER['REQUEST_URI'] != '/' && $bill_id = get_bill_by_slug($_GET['page']))
		$b = new Bill($bill_id, $db);

	$u->db = $db;
	$b->db = $db;

	/* CONFIRM ACCOUNT HASH
	=====================================================================*/
	if(isset($_GET['activate']))
	{
		mysql_query("DELETE FROM ".DB_TBL_USER_META." WHERE meta_key='_account_hash' AND meta_value='".$db->clean($_GET['activate'])."'", $db->mySQLconnRW);
		
		$redirect = '';
		if(mysql_affected_rows($db->mySQLconnRW) > 0)
		{
			unset($u->meta['_account_hash']);
			$redirect = '?activation-successful';
		}
		redirect(SERVER_URL.'/login'.$redirect); 
	}
	
	if(isset($_GET['resend-confirmation']))
	{
		$message = '<h3>Thank you for creating a ' . SITE_TITLE . ' Account</h3><br>
					<a href="'.SERVER_URL.'/login?activate='.$u->meta['_account_hash'].'">Click Here to Activate Your Account.</a>';
		email($u->email, SITE_TITLE . ' Confirmation', $message);	
		
		$response = array('type'=>'success', 'message'=>'Your Confirmation Email has been Resent');
	}
	
	/* HANDLE FACEBOOK LOGINS 
	=====================================================================*/
	if(isset($_GET['via']) && $_GET['via'] == 'fb'){
	  global $facebook;
	  
	  $fb_user = $facebook->getUser();
  
    //Authentication
	  if($fb_user){
	    try{
	      $fb_user_profile = $facebook->api('/me', 'GET');
	    }
	    catch(FacebookApiException $e){
	      error_log("Facebook Connect Error (" . $fb_user . "): " . $e->getType() . ":: " . $e.getMessage());
	      $fb_user = null;
	    }
	  }
	  else{
	    error_log("Unable to retrieve user id");
	    $u->error = "There was an error connecting to Facebook";
	    $response = $u->respond();
	  }
	  
	  if($fb_user == null){
	    error_log("Unable to retrieve user's Facebook profile.");
	    $u->error = "There was an error connecting to Facebook";
	    $response = $u->respond();
	  }
	  else{
	    $response = $u->fb_login($fb_user_profile['email'], $fb_user_profile['first_name'], $fb_user_profile['last_name']);
	  }
	  
	  if($response['type'] == 'success'){
	    unset($_SESSION['bill']); // Force Bill to reload with user edits - if any
			redirect(SERVER_URL.(!isset($_GET['redirect']) || strpos($_GET['redirect'], 'login') !== false ? '' : $_GET['redirect']));
		}
	}
	
	/* HANDLE ALL POST SUBMISSIONS
	=====================================================================*/
	if($_SERVER['REQUEST_METHOD'] === 'POST') 
	{
		//Switch on the action value.
		switch($_POST['action'])
		{
			//User login
			case 'user-login'  :
			
				$response = $u->login($_POST['email'], $_POST['password']);

				if($response['type'] == 'success')
				{
					unset($_SESSION['bill']); // Force Bill to reload with user edits - if any
					redirect(SERVER_URL.(!isset($_GET['redirect']) || strpos($_GET['redirect'], 'login') !== false ? '' : $_GET['redirect']));
				}

				break;

			//Create User
			case 'create-user' :
			
				if(!isset($_POST['accept-terms']))
				{
					$u->error = 'You must accept the Terms and Conditions of Use to create an account.';
					$response = $u->respond();
				}
				else
				{
					$response = $u->create();

					if($response['type'] == 'success' && $_POST['company'] == '')
					{
						$message = '<h3>Thank you for creating a ' . SITE_TITLE . ' Account</h3><br>
									<a href="'.SERVER_URL.'/login?activate='.$u->meta['_account_hash'].'">Click Here to Activate Your Account.</a>';
						email($u->post['email'], SITE_TITLE . ' Confirmation', $message);
					}
					elseif($response['type'] == 'success' && $_POST['company'] != '')
					{
						$message = '<h3>'.$u->post['company'].' Has Requested a ' . SITE_TITLE . ' Account</h3><br>
									<div>Name: '.$u->post['fname'].' '.$u->post['fname'].'</div>
                                    <div>Email: '.$u->post['email'].'</div>
                                    <div>Phone: '.$u->post['phone'].'</div>
                                    <div>Position: '.$u->post['position'].'</div>
                                    <div>URL: '.$u->post['url'].'</div><br />
									<a href="'.SERVER_URL.'/company-approval">Click Here to Approve This Account.</a>';
						email('your_email', 'An Organization Has Signed Up on ' . SITE_TITLE, $message);
					}
				}
				break;
			
			//Edit User Profile
			case 'edit-user' :
			
				$response = $u->edit();
				break;
				
			//Approve Company Accounts
			case 'company-approval' :
			
				approve_companies($_POST['companies']);
				break;
				
			case 'company-archival' :
				archive_companies($_POST['companies']);
				redirect('company-approval');
				break;
				
			case 'feedback-submit' :
			
				$message  = '<strong>Name</strong><hr>';
				$message .= $_POST['name'].'<br><br>';
				$message .= '<strong>Email</strong><hr>';
				$message .= $_POST['email'].'<br><br>';
				$message .= '<strong>Purpose</strong><hr>';
				$message .= $_POST['purpose'].'<br><br>';
				$message .= '<strong>Feedback</strong><hr>';
				$message .= $_POST['feedback'].'<br><br>';
				$message .= '<strong>Known Info</strong><hr>';
				$message .= 'IP:'.$_SERVER['REMOTE_ADDR'].'<br>';
				$message .= 'USER ID:'.$u->id.'<br>';
				$message .= 'TIME:'.date('Y-d-m g:i:s a').'<br>';
				
				
				email('user@yourdomain.com', 'MADISON FEEDBACK', $message);
				
				$response = array('type'=>'success', 'message'=>'Feedback Submitted Successfully.');
				
				break;
			case 'save-nav' :
			case 'create-doc' :
			case 'save-doc' :
			case 'add-doc-section' :
			case 'delete-doc-section' :
				require_once('admin/admin-ajax.php');
				exit;
		}
	}

	$_SESSION['user'] = $u;
	$_SESSION['bill'] = $b;

	/* DETERMINE VIEW
	=====================================================================*/
	$action = isset($_GET['action']) ? $_GET['action']  : 'view';
	$type   = isset($_GET['type'])   ? $_GET['type'] 	: 'index';


	/* DISPLAY VIEW
	=====================================================================*/
	
	//Display Header
	get_header();

	if($type == 'note' && isset($_GET['note'])) // View Individual Note
	{		
		$note = isset($b->{$_GET['note_type'].'s'}[$_GET['note']]) ? $b->{$_GET['note_type'].'s'}[$_GET['note']] : false;
		
		if(!$note)
		{
			$sql = "SELECT n.*, u.id AS uid, u.fname, u.lname, u.company FROM ".DB_TBL_NOTES." as n, ".DB_TBL_USERS." AS u 
					WHERE u.id = n.user AND n.part_id='".$db->clean($_GET['note'])."'";
			$r 	 = mysql_query($sql, $db->mySQLconnR);
			
			if(mysql_num_rows($r) == 0)
				redirect(SERVER_URL);
			
			$note 				 = mysql_fetch_assoc($r);
			$note['user'] 		 = $note ['company'] != '' ? $note ['company'] : $note ['fname'].' '.strtoupper(substr($note ['lname'], 0, 1)).'.';
			$note['user'] 		 = '<a href="'.SERVER_URL.'/user/'.$note ['uid'].'">'.$note ['user'].'</a>';
			$note['time_stamp']  = date('M jS, Y g:i a', $note ['time_stamp']);
		}

		$orig = mysql_result(mysql_query("SELECT content FROM ".DB_TBL_BILL_CONTENT." WHERE id='".$note['part_id']."'", $db->mySQLconnR), 0);  #WORK AROUND FOR GET SECTION PART FUNCTION
		include('inc/views/view-note.php');
	}
	elseif($type == 'note' && isset($_GET['user'])) // View Notes by USER ID 
	{		
		$user = new User($_GET['user'], $db);
		$user->db = $db;

		if(!$user->id) // User not found
			include('inc/views/view-404.php');
		else // User found - show parent notes
		{
			$notes = $b->get_notes_by_user($_GET['user']);
			include('inc/views/view-notes.php');
		}
	}
	elseif(($type == 'page' && isset($b->slug) && $_GET['page'] == $b->slug) || ($_SERVER['REQUEST_URI'] == '/' && isset($b->slug))) // Show Bill Reader App
		include('inc/views/'.$action.'-reader.php');
	elseif($type == 'admin'){
		if($u->loggedin && $u->user_level == 1){
			if($_GET['page'] == 'index'){
				include('admin/view-index.php');
			}
			elseif($_GET['action'] == 'edit'){
				include('admin/edit-doc.php');
			}
			elseif($_GET['page'] == 'docs'){
				include('admin/list-docs.php');
			}
			elseif($_GET['page'] == 'nav'){
				include('admin/edit-nav.php');
			}
			elseif($_GET['page'] == 'company-approval'){
				include('admin/approve-company.php');
			}
			else{
				include ('inc/views/view-404.php');
			}
		}
		else{
			include('inc/views/view-404.php');
		}
	}
	elseif(file_exists(SERVER_ABS.'/inc/views/'.$action.'-'.$type.'.php')) // Show Special Pages
		include('inc/views/'.$action.'-'.$type.'.php');
	elseif(file_exists(SERVER_ABS.'/inc/views/'.$action.'-'.$_GET['page'].'.php')) // Show Non-Special Page : terms-conditions, contact, about, etc...
		include('inc/views/'.$action.'-'.$_GET['page'].'.php');
	else
		include('inc/views/view-404.php'); // Show 404 Page not found
	
	//Display Footer
	get_footer();
	
	exit();
?>