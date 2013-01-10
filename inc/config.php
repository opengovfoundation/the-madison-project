<?php
/**
 * Madison Config File
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

	/** 
	 *	Site Configurations
	 */
	define('DB_HOST', '');
	define('DB_NAME', '');
	define('DB_USER', '');
	define('DB_PASS', '');
	define('SITE_TITLE', 'Madison');

	/**
	 *	Server Definitions
	 */
	define('SERVER_ABS', $_SERVER['DOCUMENT_ROOT']);
	define('SERVER_URL', 'http://'.$_SERVER['HTTP_HOST']);

	/** 
	 * Postmark Definitions
	 */
	define('POSTMARK_APIKEY', 	'');
	define('POSTMARK_EMAIL',  	'');
	define('POSTMARK_NAME',		'');

	/**
	 *	Error Reporting Settings
	 */ 
	ini_set('log_errors', 'on');
	ini_set('display_errors', 'off');
	ini_set('display_startup_errors', 'off');
	ini_set('error_log', '');
	
	/**
	 * 	Database Name Definitions
	 */
	define('DB_PREFIX', '');
	define('DB_TBL_BILLS',			DB_PREFIX.'bills');
	define('DB_TBL_BILL_CONTENT',	DB_PREFIX.'bill_content');
	define('DB_TBL_NOTES',			DB_PREFIX.'notes');
	define('DB_TBL_TOP_NOTES',		DB_PREFIX.'top_notes');
	define('DB_TBL_USERS', 			DB_PREFIX.'users');
	define('DB_TBL_USER_META', 		DB_PREFIX.'user_meta');
	
	/**
	 * 	Database Credentials Array
	 */
	$db_creds = array(
		'rw'=>array('host'=>DB_HOST, 'name'=>DB_NAME, 'user'=>DB_USER, 'pass'=>DB_PASS)
	);