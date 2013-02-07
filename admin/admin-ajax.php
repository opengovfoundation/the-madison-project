<?php
	if(isset($u->loggedin) && $u->loggedin && $u->user_level == 1){
		switch($_POST['action']){
			case 'save-nav':
				$nav = $_POST['nav-menu'];
				$serialized = serialize($nav);

				$rows = $db->insertOrUpdate('site_info', array('meta_key'=>'nav_menu', 'meta_value'=>$serialized));

				$ret = array('rows'=>$rows);
				echo json_encode($ret);
				break;
			case 'create-doc':
				$ret = create_doc($_POST['title'], $_POST['slug']);
				
				echo json_encode(array('doc_id'=>$ret));
				break;
			case 'save-doc':
				$doc_id = $_POST['doc_id'];
				$doc_items = $_POST['doc_items'];
				$query = "INSERT INTO " . DB_TBL_BILL_CONTENT . " (id, bill_id, parent, content) VALUES ";
				foreach($doc_items as $doc_item){
					$query .= "('$doc_item[id]', '$doc_id', '$doc_item[parent_id]', '$doc_item[content]'), ";
				}
				$query = rtrim($query, ", ");
				$query .= " ON DUPLICATE KEY UPDATE parent = VALUES(parent), content = VALUES(content)";
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
		}
	}
	else{
		error_log("UNAUTHORIZED POST: " . print_r($_POST, true));
	}
	
	exit;
?>