<?php
/**
 * 	Madison Email Template
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */

?>


<html>
<body marginheight="0" marginwidth="0" style="margin:0px; background:#EFEFEF" background="#EFEFEF">
<table width="600" border="0" cellspacing="0" cellpadding="0" align="center" style="font-family:Arial, Helvetica, sans-serif; font-size:12px;">
  <tr>
    <td height="89"><?php echo SITE_TITLE; ?></td>
  </tr>
  <tr>
    <td bgcolor="#FFFFFF" style="background:#FFF; padding:20px;"><?php echo urldecode($_GET['message']); ?></td>
  </tr>
  <tr>
    <td bgcolor="#081C3A" style="background:#081C3A; padding:20px; color:#FFF; text-align:center; font-size:10px;" align="center">
        	<div style="margin-bottom:10px;">
            <a href="<?php echo SITE_TITLE; ?>/contact-us" style="color:#FFF">Contact Us</a> &nbsp; | &nbsp; 
            <a href="<?php echo SITE_TITLE; ?>/privacy-policy" style="color:#FFF">Privacy Policy</a> &nbsp; | &nbsp; 
            <a href="<?php echo SITE_TITLE; ?>/terms-conditions" style="color:#FFF">Terms &amp; Conditions</a>
            </div>
        </td>
  </tr>
</table>
</body>
</html>