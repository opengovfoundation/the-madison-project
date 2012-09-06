<?php
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