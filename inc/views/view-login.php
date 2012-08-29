<h1>Login</h1>
<div id="generic-content">
	<?php if(isset($response) && $response['type'] == 'error') : ?>
    <div class="error">Error: <?=$response['message']?></div>
    <?php endif; ?>
    <?php if(isset($response) && $response['type'] == 'success') : ?>
    <div class="success"><?=$response['message']?></div>
    <?php endif; ?>
    <?php if(isset($_GET['activation-successful'])) : ?>
    <div class="success">You've Successfully Activated Your KeeptheWebOpen.com Account.  Login Below.</div>
    <?php endif; ?>
    <form method="post" action="<?=SERVER_URL?>/login<?=isset($_GET['redirect']) ? '?redirect='.$_GET['redirect'] : ''?>" id="frm">
        <table width="100%" border="0" cellspacing="0" cellpadding="5">
          <tr>
            <th width="20%"><label>Email:</label></th>
            <td><input name="email"  type="text" value="" style="width:200px;" /></td>
          </tr>
          <tr>
            <th><label>Password:</label></th>
            <td><input name="password" type="password" value=""  style="width:200px;" /></td>
          </tr>
        </table>
        <div id="vl-login">
            <input type="submit" value="Login" style="margin:20px 0 40px 188px;"  />
            <!--<div class="label-description" style="margin-top:5px;"><a href="<?=SERVER_URL?>/forgot-password">forgot your password?</a></div>-->
        </div>
        <input name="action" type="hidden" value="user-login" />
    </form>
    <div style="margin-bottom:10px;">Don't have an account?  <a href="<?=SERVER_URL?>/signup">Create one now</a>.</div>
    <div style="margin-bottom:25px;">Want to resend your confirmation email?  Attempt to login and you'll be given the option to resend.</div>
</div>