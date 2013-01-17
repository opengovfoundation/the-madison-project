<?php
	switch($_POST['action']){
		case 'save-nav':
			$nav = $_POST['nav-menu'];
			$serialized = serialize($nav);
			
			$rows = $db->insertOrUpdate('site_info', array('meta_key'=>'nav_menu', 'meta_value'=>$serialized));
			
			$ret = array('rows'=>$rows);
			echo json_encode($ret);
			break;
	}

	exit;
?>