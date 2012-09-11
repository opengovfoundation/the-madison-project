<?php
/**
 * 	Madison View Template
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
?>
<h1>Forgot Your Password?</h1>
<?php if(isset($response) && $response['type'] == 'error') : ?>
<div class="error">Error: <?=$response['message']?></div>
<?php elseif(isset($response) && $response['type'] == 'success') : ?>
<div class="success"><?=$response['message']?></div>
<?php endif; ?>
<form method="post" action="" id="frm">
	<table width="100%" border="0" cellspacing="0" cellpadding="5">
      <tr>
        <th width="20%"><label>Enter Your Email or Phone Number:</label></th>
        <td><input name="recover" type="text" value="" style="width:200px;" /></td>
      </tr>
    </table>
    <div id="fp-submit">
    	<input type="submit" value="CONFIRM RESET" />
    	<div class="label-description" style="margin-top:5px;"><a href="<?=SERVER_URL?>/login">LOG IN</a></div>
    </div>
    <input name="action" type="hidden" value="user-forgot-password" />
</form>