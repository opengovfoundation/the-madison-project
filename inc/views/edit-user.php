<?php
/**
 * 	Madison View Template
 * 
 * 	@copyright Copyright &copy; 2012 by The OpenGov Foundation
 *	@license http://www.gnu.org/licenses/ GNU GPL v.3
 */
?>
<?php $create = strpos($_SERVER['REQUEST_URI'], '/edit/user') === false; ?>
<?php if($create && !isset($_POST['rep_org'])) : ?>
<style>.org_info{display:none}</style>
<?php endif; ?>
<script type="text/javascript" src="<?=SERVER_URL?>/assets/js/edit_user.js"></script>
<h1><?=$create ? 'Create an Account' : 'Edit Your Profile'?></h1>
<div id="generic-content">
<?php if(isset($response) && $response['type'] == 'error') : ?>
<div class="error">Error: <?=$response['message']?></div>
<?php elseif(isset($response) && $response['type'] == 'success') : ?>
<div class="success"><?=$response['message']?></div>
<?php endif; ?>
<?php if(!$create || !isset($response) || $response['type'] != 'success') : ?>
<form method="post" action="" id="frm">
  <table id="ue" width="100%" border="0" cellspacing="0" cellpadding="5">
  	<tr>
      <td colspan="2"><h1>Your Information</h1></td>
    </tr>
    <tr>
      <th width="20%"><label>First Name:</label></th>
      <td><input id="fname" name="fname-required" type="text" value="<?=isset($_POST['fname-required']) ? $_POST['fname-required'] : ($u->loggedin ? $u->fname : '')?>" /></td>
    </tr>
    <tr>
      <th><label>Last Name:</label></th>
      <td><input id="lname" name="lname-required" type="text" value="<?=isset($_POST['lname-required']) ? $_POST['lname-required'] : ($u->loggedin ? $u->lname : '')?>" /></td>
    </tr>
    <tr>
      <th valign="top"><label>Email:</label></th>
      <td><input name="email-required" type="text" value="<?=isset($_POST['email-required']) ? $_POST['email-required'] : ($u->loggedin ? $u->email : '')?>" />
        <div class="label-description">The address you'll use to login.</div></td>
    </tr>
    <tr>
      <th><label>Zip:</label></th>
      <td><input name="zip-required" type="text" value="<?=isset($_POST['zip-required']) ? $_POST['zip-required'] : ($u->loggedin ? $u->zip : '')?>" /></td>
    </tr>
	<?php if($create || $u->company != '') : ?>
    <tr>
      <td colspan="2">
      	<h1 style="margin:20px 0 5px 0;"><?=$create ? 'Is this an Organization / Company Account?' : 'Orginization'?></h1>
        <div class="label-description">Organization accounts must be approved to be activated to prevent fraud.</div>
      </td>
    </tr>
    <?php if($create) : ?>
    <tr>
      <th width="20%"><label>I am representing an organization</label></th>
      <td><input id="rep_org" name="rep_org" type="checkbox" onClick="toggleCompanyInfo();" <?= isset($_POST['rep_org']) ? 'checked="yes"' : ''?>/></td>
    </tr>
    <?php endif; ?>
    <tr class="org_info">
      <th width="20%"><label>Organization Phone Number:</label></th>
      <td><input id="org_phone" name="phone<?=!$create ? '-required' : ''?>" type="text" value="<?=isset($_POST['phone'.(!$create ? '-required' : '')]) ? $_POST['phone'.(!$create ? '-required' : '')] : ($u->loggedin ? $u->phone : '')?>" /></td>
    </tr>
    <tr class="org_info">
      <th width="%20"><label>Position With Organization:</label></th>
      <td><input id="org_position" name="position<?=!$create ? '-required' : ''?>" type="text" value="<?=isset($_POST['position'.(!$create ? '-required' : '')]) ? $_POST['position'.(!$create ? '-required' : '')] : ($u->loggedin ? $u->position : '')?>" />
      </td>
    </tr>
    <tr class="org_info">
      <th width="%20"><label>Organization URL:</label></th>
      <td><input id="org_url" name="url<?=!$create ? '-required' : ''?>" type="text" value="<?=isset($_POST['url'.(!$create ? '-required' : '')]) ? $_POST['url'.(!$create ? '-required' : '')] : ($u->loggedin ? $u->url : '')?>" />
      </td>
     <?php if($create) : ?>
    <tr class="org_info">
       <th width="20%"><label>Organization:</label></th>
       <td><input id="company" name="company<?=!$create ? '-required' : ''?>" type="text" value="<?=isset($_POST['company'.(!$create ? '-required' : '')]) ? $_POST['company'.(!$create ? '-required' : '')] : ($u->loggedin ? $u->company : '')?>" /></td>
    </tr>
    <?php endif; ?>
	<?php endif; ?>
    <tr>
      <td colspan="2"><h1 style="margin:20px 0 5px 0;">Password</h1>
        <div class="label-description" style="margin-bottom:10px;">Your login password. Minimum 6 characters.
          <?php if(!$create) : ?>
          Leave blank if you do not want to change your password.
          <?php endif; ?>
        </div></td>
    </tr>
    <tr>
      <th><label>Password:</label></th>
      <td><input name="password<?=!$create ? '' : '-required'?>" type="password" value="" /></td>
    </tr>
    <tr>
      <th><label>Retype Password:</label></th>
      <td><input name="repassword<?=!$create ? '' : '-required'?>" type="password" value="" /></td>
    </tr>
    <?php if($create) : ?>
    <tr>
      <th></th>
      <td style="padding-top:20px;"><input type="checkbox" name="accept-terms" value="1" /> Check box if you accept the <a href="<?=SERVER_URL?>/terms-conditions" target="_blank">Terms of Use Conditions</a>.</td>
    </tr>
    <?php endif; ?>
    <tr>
  </table>
  <input type="submit" value="<?=!$create ? 'Update Profile' : 'Signup'?>" style="margin:20px 0 40px 188px;" />
  <input name="action" type="hidden" value="<?=!$create ? 'edit' : 'create'?>-user" />
</form>
<?php if($create) : ?>
	<div style="margin-bottom:25px;">Already have an account?  <a href="<?=SERVER_URL?>/login">Login</a>.</div>
<?php endif; ?>
<?php endif; ?>
</div>