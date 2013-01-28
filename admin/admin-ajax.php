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
		}
	}
	else{
		error_log("UNAUTHORIZED POST: " . print_r($_POST, true));
	}
	
	exit;
?>