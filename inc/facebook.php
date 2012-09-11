<?php
/**
 * 	Madison Facebook Configuration File
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */


	//TODO: Create Madison Facebook App and plug in ID and Secret
  
  /* Live */
  define('YOUR_APP_ID', '');
  define('YOUR_APP_SECRET', '');
  
  global $facebook, $loginUrl;
  
  require('facebook/php-sdk/src/facebook.php');
  
  $facebook = new Facebook(array(
    'appId' => YOUR_APP_ID,
    'secret' => YOUR_APP_SECRET,
    'cookie' => true
  ));  
  
  //$loginUrl = $facebook->getLoginUrl(array(
  //  'scope' => 'email',
  //  'redirect_uri' => FB_BASEURL . 'login?&via=fb'
  //));
?>