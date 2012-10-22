<?php
/**
 * Madison Ajax Controller
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */ 

	require('config.php');

	require("db.class.php");
	require("object.class.php");
	require("bill.class.php");
	require("user.class.php");
	
	session_start();

	$db = new db($db_creds);
	$u  = $_SESSION['user'];
	$b  = isset($_SESSION['bill']) ? $_SESSION['bill'] : new Bill(10, $db);
	//$b  = $_SESSION['bill'];
	
	$u->db = $db;
	$b->db = $db;
	
	$error	  = false;
	$response = array('status'=>'Error', 'message'=>'Action Not Set', 'data'=>array());
	
	if(isset($_POST['action']))
	{
		if(strpos($_POST['action'], 'add-') !== false && !$u->loggedin)
		{
			$error = true;
			$response['message'] = 'You Must Be Logged in to Preform this Action.';
		} 
		else
			$response['message'] = 'Action Not Recognized';
		
		if(!$error)
		{
			switch($_POST['action'])
			{
				case 'preview-suggestion' :
					$sec 	  = $b->get_section_part($_POST['part_id'], $b->content);
					$response = array('status'=>'OK', 'message'=>'Note Added Successfully.', 'data'=>$b->edit_diff(stripslashes($sec['content']), stripslashes($_POST['note'])));
					break;
				case 'add-note' :	
					$note_id = $b->add_note($_POST['part_id'], $_POST['type'], $_POST['note'], $u->id);
					
					if($_POST['type'] == 'suggestion' && $note_id > 0)
					{
						if($_POST['why'] != '')
							$b->add_note($_POST['part_id'], 'comment', $_POST['why'], $u->id, $note_id);
							
						$b->get_section($_POST['sect_id'], true, $u->id);
					}
					
					$b->refresh_notes();
					$response = array('status'=>'OK', 'message'=>'Note Added Successfully.', 'data'=>array());
					
					break;
				case 'add-note-comment' :
					$b->add_note($_POST['part_id'], $_POST['type'], $_POST['note'], $u->id, $_POST['parent']);
					$b->refresh_notes();
					$response = array('status'=>'OK', 'message'=>'Note Comment Added Successfully.', 'data'=>array());

					break;
				case 'add-ldf-note' :
					$b->ldf_note($_POST['note'], $_POST['type'], $u->id);
					$b->refresh_notes();
					$response = array('status'=>'OK', 'message'=>'Note Tool Submitted Successfully.', 'data'=>array());

					break;
				case 'get-notes-by-part' :

					$b->refresh_notes($_POST['part_id']);

					$suggestions = $b->get_notes_by_part($_POST['part_id'], 'suggestions', true);
					$comments	 = $b->get_notes_by_part($_POST['part_id'], 'comments', true);
					$response 	 = array('status'=>'OK', 'message'=>'', 'data'=>array('suggestions'	=>array('total'=>count($suggestions), 'suggestions'=>$suggestions), 
																					  'comments'	=>array('total'=>count($comments), 'comments'=>$comments)));
					break;
				
				case 'get-section' :

					$sec 	  = $b->get_section($_POST['sect_id'], false, ($u->loggedin ? $u->id : 0));
					$content  = $b->section_to_html($sec, $_POST['view'], $sec['children']);

					$response = array('status'=>'OK', 'message'=>'', 'data'=>array('content'=>$content));
					break;
				
				case 'get-short-url' :
					$ch = curl_init('https://www.googleapis.com/urlshortener/v1/url');
					
					curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
					curl_setopt($ch, CURLOPT_POST      ,1);
					curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array('longUrl'=>SERVER_URL.$_POST['base_uri'].'?sec='.$_POST['sect_id'].'&sel='.$_POST['selected'])));
					curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
				
					$output = curl_exec($ch);
					curl_close($ch); 
					
					$response = array('status'=>'OK', 'message'=>'', 'data'=>json_decode(stripslashes($output)));
					
					break;
					
				case 'subscribe' :
				  
				  $response = $db->insert('hoba_emails', array('email'=>$_POST['email'], 'bill'=>$_POST['bill']));
			}
		}
		
		$_SESSION['user'] = $u;
		$_SESSION['bill'] = $b;
	}
	
	function utf8_encode_deep(&$input) {
	    if (is_string($input)) {
	        $input = utf8_encode($input);
	    } else if (is_array($input)) {
	        foreach ($input as &$value) {
	            utf8_encode_deep($value);
	        }

	        unset($value);
	    } else if (is_object($input)) {
	        $vars = array_keys(get_object_vars($input));

	        foreach ($vars as $var) {
	            utf8_encode_deep($input->$var);
	        }
	    }
	}
	
	utf8_encode_deep($response);
	echo json_encode($response);
	
?>