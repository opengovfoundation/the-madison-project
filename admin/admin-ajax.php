<?php
	switch($_POST['action']){
		case 'save-nav':
			$nav = $_POST['nav-menu'];
			$serialized = serialize($nav);
			
			$rows = $db->update('site_info', array('meta_value'=>$serialized), "meta_key = 'nav_menu'");
			
			$ret = array('rows'=>$rows);
			echo json_encode($ret);
			break;
	}

	exit;
?>