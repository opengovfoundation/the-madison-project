<?php
/**
 * 	Madison Administrative Ajax Handler
 * 
 * 	@copyright Copyright &copy; 2013 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

	//User must be logged in as an administrator
	if(isset($u->loggedin) && $u->loggedin && $u->user_level == 1){
		switch($_POST['action']){
			//Save Navigation Menu
			case 'save-nav':
				$nav = $_POST['nav-menu'];
				$serialized = serialize($nav);
				$rows = $db->insertOrUpdate('site_info', array('meta_key'=>'nav_menu', 'meta_value'=>$serialized));
				$ret = array('rows'=>$rows);
				echo json_encode($ret);
				break;
			//Create New Document
			case 'create-doc':
				$ret = create_doc($_POST['title'], $_POST['slug']);
				echo json_encode(array('doc_id'=>$ret));
				break;
			//Save Document Edits
			case 'save-doc':
				$doc_id = $_POST['doc_id'];
				$doc_items = $_POST['doc_items'];
				
				//Begin constructing query
				$query = "INSERT INTO " . DB_TBL_BILL_CONTENT . " (id, bill_id, parent, child_priority, content) VALUES ";
				
				//Add each document item to the query
				foreach($doc_items as $doc_item){
					$query .= "('$doc_item[id]', '$doc_id', '$doc_item[parent_id]', '$doc_item[child_priority]', '" . mysql_real_escape_string($doc_item[content]) . "'), ";
				}
				$query = rtrim($query, ", ");
				
				//Only insert if a new item, otherwise update
				$query .= " ON DUPLICATE KEY UPDATE parent = VALUES(parent), child_priority = VALUES(child_priority), content = VALUES(content)";
				
				if(!mysql_query($query, $db->mySQLconnRW)){
					error_log("DOCUMENT SAVE ERROR: " . mysql_error());
					$msg = "Failed to save document.";
					$success = false;
				}
				else{
					$msg = "Document saved.";
					$success = true;
				}
				
				echo json_encode(array('msg'=>$msg, 'success'=>$success));
				break;
			//Add new section to the document and return its id in a JSON string
			case 'add-doc-section':
				$doc_id = $_POST['doc_id'];
				$parent_id = $_POST['parent_id'];
				$query = "INSERT INTO " . DB_TBL_BILL_CONTENT . " (bill_id, parent, content) VALUES ('$doc_id', '$parent_id', 'New Content')";
				
				//Insert item and retrieve the new id
				if(!mysql_query($query, $db->mySQLconnRW)){
					error_log("ERROR CREATING NEW DOCUMENT SECTION: " . mysql_error());
					$msg = "Failed to create section";
					$success = false;
					$new_id = -1;
				}
				else{
					$msg = "Created section";
					$success = true;
					$new_id = mysql_insert_id();
				}
				echo json_encode(array('msg'=>$msg, 'success'=>$success, 'new_id'=>$new_id));
				break;
				
			//Delete document section and all children sections
			case 'delete-doc-section':
				$id = $_POST['id'];
				$query = "DELETE FROM " . DB_TBL_BILL_CONTENT . " WHERE (id = '$id' or parent='$id')";
				if(!mysql_query($query, $db->mySQLconnRW)){
					error_log("ERROR DELETING NEW DOCUMENT SECTION: " . mysql_error());
					$msg = "Failed to delete section";
					$success = false;
				}
				else{
					$msg = "Section Deleted.";
					$success = true;
				}
				echo json_encode(array('msg'=>$msg, 'success'=>$success));
				break;
				
		}
	}
	else{
		error_log("UNAUTHORIZED POST: " . print_r($_POST, true));
	}
	
	exit;
?>
