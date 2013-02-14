<?php
/**
 * 	Madison Update Script
 * 
 *  Update the database if necessary
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

//Upgrade Database if necessary
$fields = mysql_list_fields(DB_NAME, DB_TBL_BILL_CONTENT);
$columns = mysql_num_fields($fields);
for($i = 0; $i < $columns; $i++){
	$field_array[] = mysql_field_name($fields, $i);
}
if(!in_array('child_priority', $field_array)){
	$result = mysql_query('ALTER TABLE ' . DB_TBL_BILL_CONTENT . ' ADD COLUMN child_priority INT DEFAULT 0 AFTER parent', $db->mySQLconnRW);
	if(!$result){
		die('Could not update the database: ' . mysql_error());
	}
}

?>